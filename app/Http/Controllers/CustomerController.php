<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Gallery;
use App\Models\GroupClassType;
use App\Models\Package;
use App\Models\Schedule;
use App\Models\ScheduleDetail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $schedules = Schedule::with('scheduleDetails.classes.groupClassType')
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

    public function getPackagesByClassID(Request $request)
    {
        if ($request->class_id) {
            $packages = Package::with('classes')
                ->where('class_id', $request->class_id)->get();
            return response()->json($packages);
        }

        response()->json();
    }
}
