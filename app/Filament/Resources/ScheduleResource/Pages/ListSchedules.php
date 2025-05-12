<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Resources\ScheduleResource;
use App\Models\Schedule;
use App\Models\ScheduleTemplateDetail;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSchedules extends ListRecords
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Jadwal')
                ->modalHeading('Tambah Jadwal Kelas')
                ->after(function (Schedule $record, array $data) {
                    if ($data['template_id']) {
                        $templates = ScheduleTemplateDetail::where('schedule_template_id', $data['template_id'])->get();

                        $record->scheduleDetails()->createMany($templates->toArray());
                    }
                }),
        ];
    }
}
