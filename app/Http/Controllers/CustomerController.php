<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Gallery;
use App\Models\GroupClassType;
use App\Models\Package;
use App\Models\PackageSchedule;
use App\Models\PackageTransaction;
use App\Models\Schedule;
use App\Models\ScheduleDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Midtrans\Notification;
use Midtrans\Snap;

class CustomerController extends Controller
{
    public function index()
    {
        return view('index', [
            'galleryImages' => Gallery::all(),
        ]);
    }

    public function schedule(Request $request)
    {
        if (!$request->private_schedule) {
            $groupTypeID = GroupClassType::where('name', 'umum')->first()->id;
        } else {
            $groupTypeID = GroupClassType::where('name', 'privat')->first()->id;
        }

        $schedules = Schedule::with('scheduleDetails.classes.classType.groupClassType')
            ->where('date', '>=', Carbon::today())
            ->get();

        if (!$request->private_schedule) {
            return view('schedule-first', [
                'schedules' => $schedules,
                'groupTypeID' => $groupTypeID,
            ]);
        }

        return view('schedule-second', [
            'schedules' => $schedules,
            'groupTypeID' => $groupTypeID,
        ]);
    }

    public function getPackagesByClassTypeID(Request $request)
    {
        if ($request->class_type_id) {
            $packages = Package::with('classType')
                ->where('class_type_id', $request->class_type_id)
                ->get();

            return response()->json($packages);
        }

        response()->json();
    }

    public function bookClass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'schedule_detail_id' => 'required',
            'package_id' => 'required',
            'customer_name' => 'required',
            'phone_num' => 'required',
            'email' => 'nullable',
        ], [
            'schedule_detail_id.required' => 'ID detail jadwal wajib ada',
            'package_id.required' => 'Wajib memilih paket',
            'customer_name.required' => 'Nama wajib diisi',
            'phone_num.required' => 'Nomor WhatApp wajib diisi',
        ]);

        if ($validator->fails()) return redirect()
            ->back()
            ->withInput()
            ->with('toast_error', join(', ', $validator->messages()->all()));


        $data = collect($validator->safe()->all());

        $scheduleDetail = ScheduleDetail::with('schedule')->find($request->schedule_detail_id);

        if (!$scheduleDetail) {
            return redirect()
                ->back()
                ->with('error', 'Jadwal Tidak Ditemukan');
        }

        if ($scheduleDetail->quota <= 0) {
            return redirect()
                ->back()
                ->with('error', 'Kuota Jadwal Habis');
        }

        $package = Package::with('classType.groupClassType')->find($request->package_id);

        if ($package->is_trial) {
            if (PackageTransaction::where('phone_num', $request->phone_num)->where('payment_status', 'success')->exists()) {
                return redirect()
                    ->back()
                    ->with('error', 'Anda hanya dapat daftar paket trial ini sebnyak satu kali');
            }
        }

        $data = $data->merge($scheduleDetail->toArray())->merge($package->toArray());

        $scheduleDetailDate = $scheduleDetail->schedule->date;
        $packageDuration = $package->duration;
        if ($package->duration_unit == 'day') {
            $data['valid_until'] = Carbon::make($scheduleDetailDate)->addDay($packageDuration);
        } else if ($package->duration_unit == 'week') {
            $data['valid_until'] = Carbon::make($scheduleDetailDate)->addWeek($packageDuration);
        } else if ($package->duration_unit == 'month') {
            $data['valid_until'] = Carbon::make($scheduleDetailDate)->addMonth($packageDuration);
        } else if ($package->duration_unit == 'year') {
            $data['valid_until'] = Carbon::make($scheduleDetailDate)->addYear($packageDuration);
        } else {
            $data['valid_until'] = Carbon::make($scheduleDetailDate)->addWeek($packageDuration);
        }

        $data = $data->toArray();
        $data['number_of_session_left'] = $package->number_of_session;
        $data['group_class_type'] = $package->classType->groupClassType->name;
        $data['transaction_code'] = Str::random(12);
        $data['class_type_name'] = $package->classType->name;

        $newTransaction = PackageTransaction::create($data);

        PackageSchedule::create(
            [
                "package_transaction_id" => $newTransaction->id,
                "schedule_detail_id" => $scheduleDetail->id
            ]
        );

        $midtransRedirectUrl = $this->midtransTransaction($newTransaction);

        return redirect($midtransRedirectUrl);
    }

    public function midtransTransaction(PackageTransaction $transaction)
    {
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = (bool) env('MIDTRANS_IS_PRODUCTION');
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = (bool) env('MIDTRANS_IS_SANITIZE');
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = (bool) env('MIDTRANSA_IS_3DS');

        $params = array(
            'transaction_details' => array(
                'order_id' => $transaction->transaction_code,
                'gross_amount' => $transaction->price,
            ),
            'customer_details' => array(
                'customer_name' => $transaction->customer_name,
                'last_name' => $transaction->customer_name,
                'email' => $transaction->email,
            ),
        );

        $createMidtransTransaction = Snap::createTransaction($params);
        // dd($createMidtransTransaction);
        $midtransRedirectUrl = $createMidtransTransaction->redirect_url;
        // dd($midtransRedirectUrl);

        return $midtransRedirectUrl;
    }

    public function redeemBookCode(Request $request)
    {
        $request->validate([
            'schedule_detail_id' => 'required',
            'redeem_code' => 'required',
        ], [
            'redeem_code.required' => 'Kode redeem wajib ada',
            'redeem_code.required' => 'Kode redeem minimal ada :min karakter',
            'schedule_detail_id.required' => 'ID detail jadwal wajib ada',
        ]);

        $packageTransaction = PackageTransaction::with('package.classType')->where('redeem_code', $request->redeem_code)->first();

        if (!$packageTransaction) {
            return redirect()
                ->back()
                ->with('error', 'Kode redeem tidak valid');
        }

        if ($packageTransaction->payment_status != 'success') {
            return redirect()
                ->back()
                ->with('error', 'Status pembayaran booking ' . $packageTransaction->ConvertedPaymentStatus);
        }

        if ($packageTransaction->number_of_session_left <= 0) {
            return redirect()
                ->back()
                ->with('error', 'Kuota Member Habis');
        }

        $scheduleDetail = ScheduleDetail::with('classes.classType')->find($request->schedule_detail_id);

        if ($packageTransaction->valid_until) {
            if (Carbon::today()->greaterThan($packageTransaction->valid_until)) {
                return redirect()
                    ->back()
                    ->with('error', 'Kode booking kadaluarsa');
            }
        } else {
            if ($packageTransaction->number_of_session == $packageTransaction->number_of_session_left) {
                $scheduleDetailDate = $scheduleDetail->schedule->date;
            } else {
                $packageSchedule = PackageSchedule::with('scheduleDetail.schedule')->where("package_transaction_id", $packageTransaction->id)->first();

                $scheduleDetailDate = $packageSchedule->scheduleDetail->schedule->date;
            }

            $packageDuration = $packageTransaction->duration;

            if ($packageTransaction->duration_unit == 'day') {
                $data['valid_until'] = Carbon::make($scheduleDetailDate)->addDay($packageDuration);
            } else if ($packageTransaction->duration_unit == 'week') {
                $data['valid_until'] = Carbon::make($scheduleDetailDate)->addWeek($packageDuration);
            } else if ($packageTransaction->duration_unit == 'month') {
                $data['valid_until'] = Carbon::make($scheduleDetailDate)->addMonth($packageDuration);
            } else if ($packageTransaction->duration_unit == 'year') {
                $data['valid_until'] = Carbon::make($scheduleDetailDate)->addYear($packageDuration);
            } else {
                $data['valid_until'] = Carbon::make($scheduleDetailDate)->addWeek($packageDuration);
            }
        }


        if (!$scheduleDetail) {
            return redirect()
                ->back()
                ->with('error', 'Jadwal Tidak Ditemukan');
        }

        if ($scheduleDetail->quota <= 0) {
            return redirect()
                ->back()
                ->with('error', 'Kuota Jadwal Habis');
        }

        if ($packageTransaction->package->classType->id != $scheduleDetail->classes->classType->id) {
            return redirect()
                ->back()
                ->with('error', 'Paket kelas ' . $packageTransaction->package->classType->name . ' anda tidak dapat digunakan untuk meredeem paket kelas ' . $scheduleDetail->classes->classType->name);
        }

        PackageSchedule::create(
            [
                "package_transaction_id" => $packageTransaction->id,
                "schedule_detail_id" => $scheduleDetail->id
            ]
        );

        $data['number_of_session_left'] = ($packageTransaction->number_of_session_left - 1);

        $packageTransaction->update($data);

        $scheduleDetail->decrement('quota');

        return redirect()->route('index')->with('success', 'Redeem berhasil!');
    }

    public function handlerWebhook()
    {
        \Midtrans\Config::$isProduction = (bool) env('MIDTRANS_IS_PRODUCTION');
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');;
        $notif = new Notification();

        $transactionStatus = $notif->transaction_status;
        $transactionCode = $notif->order_id;

        $status = '';

        if ($transactionStatus == 'capture') {
            $status = 'success';
        } else if ($transactionStatus == 'settlement') {
            $status = 'success';
        } else if (
            $transactionStatus == 'cancel' ||
            $transactionStatus == 'deny' ||
            $transactionStatus == 'expire'
        ) {
            $status = 'failure';
        } else if ($transactionStatus == 'pending') {
            $status = 'pending';
        }


        $transaction = PackageTransaction::where('transaction_code', $transactionCode)
            ->orderBy('id', 'desc')->first();

        if ($status == 'success') {
            $packageSchedule = PackageSchedule::where('package_transaction_id', $transaction->id)->orderBy('id', 'desc')->first();

            $transaction->update(
                [
                    'payment_status' => $status,
                    'number_of_session_left' => ($transaction->number_of_session - 1),
                    'redeem_code' => Str::random(12)
                ]
            );
            ScheduleDetail::find($packageSchedule->schedule_detail_id)->decrement('quota');
        } else {
            $transaction->update(
                [
                    'payment_status' => $status,
                ]
            );
        }
    }
}
