<?php

namespace App\Filament\Resources\PackageTransactionResource\Pages;

use App\Filament\Resources\PackageTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPackageTransactions extends ListRecords
{
    protected static string $resource = PackageTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Member')
                ->successRedirectUrl(route('filament.admin.resources.package-transactions.index')),
        ];
    }
}
