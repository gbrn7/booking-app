<?php

namespace App\Filament\Resources\PackageTransactionResource\RelationManagers;

use App\Models\Package;
use App\Models\PackageSchedule;
use App\Models\PackageTransaction;
use App\Models\Schedule;
use App\Models\ScheduleDetail;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;

class PackageSchedulesRelationManager extends RelationManager
{
    protected static string $relationship = 'packageSchedules';

    public function getTableHeading(): string
    {
        return 'Jadwal Member'; // Custom heading
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('schedule_id')
                    ->required()
                    ->options(function () {
                        return Schedule::where('date', '>=', Carbon::today())->get()
                            ->mapWithKeys(function ($schedule) {
                                return [
                                    $schedule->id => $schedule->FormattedDate
                                ];
                            });
                    })
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        $set('schedule_detail_id', null);
                    })
                    ->label('Hari/Tanggal'),
                Forms\Components\Select::make('schedule_detail_id')
                    ->required()
                    ->options(
                        function (Get $get) {
                            return ScheduleDetail::query()
                                ->where('schedule_id', $get('schedule_id'))
                                ->whereRelation('classes', 'class_type_id', Package::with('classType')->find($this->ownerRecord->package_id)->classType->id)
                                ->where('quota', '>', 0)
                                ->get()
                                ->mapWithKeys(function ($scheduleDetail) {
                                    return [
                                        $scheduleDetail->id => $scheduleDetail->FormattedTime
                                    ];
                                });
                        }
                    )
                    ->live()
                    ->searchable()
                    ->native(false)
                    ->label('Jam'),
            ]);
    }



    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('day')
            ->columns([
                Tables\Columns\TextColumn::make('scheduleDetail.schedule.formattedDate')
                    ->label('Hari/Tanggal'),
                Tables\Columns\TextColumn::make('scheduleDetail.FormattedTime')
                    ->label('Jam'),
                Tables\Columns\TextColumn::make('scheduleDetail.classes.name')
                    ->label('Kelas'),
                Tables\Columns\TextColumn::make('scheduleDetail.classes.classType.name')
                    ->label('Jenis Kelas'),
                Tables\Columns\TextColumn::make('scheduleDetail.classes.classType.groupClassType.name')
                    ->label('Tipe Kelas'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Jadwal')
                    ->modalHeading('Tambah Jadwal Member')
                    ->using(function (array $data, string $model, Tables\Actions\CreateAction $action): PackageSchedule {
                        $packageTransaction = PackageTransaction::find($this->ownerRecord->id);

                        if ($packageTransaction->number_of_session_left <= 0) {
                            Notification::make()
                                ->warning()
                                ->title('Gagal!')
                                ->body('Kuota member habis')
                                ->send();

                            $action->cancel();
                        }

                        if ($packageTransaction->valid_until) {
                            if (Carbon::now() > Carbon::make($packageTransaction->valid_until)) {
                                Notification::make()
                                    ->warning()
                                    ->title('Gagal!')
                                    ->body('Paket member telah kadaluarsa')
                                    ->send();

                                $action->cancel();
                            }
                        }

                        $data['package_transaction_id'] = $this->ownerRecord->id;
                        $newData = $model::create($data);

                        if ($packageTransaction->number_of_session == $packageTransaction->number_of_session_left) {
                            $validUntil = "";
                            $firstClassDate = Carbon::make(Schedule::find($data['schedule_id'])->date);
                            if ($packageTransaction->duration_unit == 'day') {
                                $validUntil = $firstClassDate->addDay($packageTransaction->duration)->endOfDay();
                            } else if ($packageTransaction->duration_unit == 'week') {
                                $validUntil = $firstClassDate->addWeek($packageTransaction->duration)->endOfDay();
                            } else if ($packageTransaction->duration_unit == 'month') {
                                $validUntil = $firstClassDate->addMonth($packageTransaction->duration)->endOfDay();
                            } else {
                                $validUntil = $firstClassDate->addYear($packageTransaction->duration)->endOfDay();
                            }

                            $packageTransaction->update([
                                'valid_until' => $validUntil,
                                'number_of_session_left' => ($packageTransaction->number_of_session - 1),
                            ]);
                        } else {
                            $packageTransaction->decrement('number_of_session_left');
                        }
                        ScheduleDetail::find($newData->schedule_detail_id)->decrement('quota');

                        return $newData;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Edit Jadwal Member')
                    ->using(function (PackageSchedule $record, array $data): PackageSchedule {
                        $oldData = $record->toArray();

                        $record->update($data);

                        if ($data['schedule_detail_id'] != $oldData['schedule_detail_id']) {
                            ScheduleDetail::find($oldData['schedule_detail_id'])->increment('quota');
                            ScheduleDetail::find($data['schedule_detail_id'])->decrement('quota');
                        }

                        return $record;
                    }),
            ])
            ->emptyStateHeading('Jadwal tidak ditemukan');
    }
}
