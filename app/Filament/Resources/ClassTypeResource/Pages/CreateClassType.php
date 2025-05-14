<?php

namespace App\Filament\Resources\ClassTypeResource\Pages;

use App\Filament\Resources\ClassTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateClassType extends CreateRecord
{
    protected static string $resource = ClassTypeResource::class;

    public function getTitle(): string
    {
        return 'Tambah Jenis Kelas'; // Your custom page title
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
