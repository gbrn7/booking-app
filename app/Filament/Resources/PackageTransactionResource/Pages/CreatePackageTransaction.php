<?php

namespace App\Filament\Resources\PackageTransactionResource\Pages;

use App\Filament\Resources\PackageTransactionResource;
use App\Models\Package;
use App\Models\PackageTransaction;
use Illuminate\Support\Str;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreatePackageTransaction extends CreateRecord
{
    protected static string $resource = PackageTransactionResource::class;

    public function getTitle(): string
    {
        return 'Tambah Member'; // Your custom page title
    }

    protected function getTableQuery()
    {
        return parent::getTableQuery()->where('payment_status', 'success');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $package = Package::with('classType.groupClassType')->find($data['package_id']);
        $collectionData = collect($data);
        $mergedData = $collectionData->merge($package);
        $mergedData->put("class_type_name", $package->classType->name);
        $mergedData->put("number_of_session_left", $package->number_of_session);
        $mergedData->put("group_class_type", $package->classType->groupClassType->name);
        $mergedData->put("transaction_code", Str::random(12));
        $mergedData->put("price", $package->price);
        $mergedData->put("redeem_code", Str::random(12));

        return $mergedData->toArray();
    }

    protected function beforeCreate(): void
    {
        $data = $this->data;

        $package = Package::find($data['package_id']);

        $newData = collect($data);

        if ($package->is_trial) {
            $data = PackageTransaction::where('phone_num', $newData->get('phone_num'))
                ->where('package_id', $newData->get('package_id'))
                ->where('payment_status', 'success')
                ->first();

            if ($data) {
                Notification::make()
                    ->warning()
                    ->title('Gagal!')
                    ->body('Paket trial ini telah dibeli sebelumnya oleh nomor hp ini')
                    ->persistent()
                    ->send();

                $this->halt();
            }
        }
    }
}
