<?php

namespace App\Filament\Resources\ScheduleTemplateResource\Pages;

use App\Filament\Resources\ScheduleTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateScheduleTemplate extends CreateRecord
{
    protected static string $resource = ScheduleTemplateResource::class;

    public function getTitle(): string
    {
        return 'Tambah Template Jadwal'; // Your custom page title
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
