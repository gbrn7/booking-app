<?php

namespace App\Filament\Resources\PackageTransactionResource\Pages;

use App\Filament\Resources\PackageTransactionResource;
use App\Models\Package;
use Illuminate\Support\Str;
use Filament\Resources\Pages\EditRecord;

class EditPackageTransaction extends EditRecord
{
    protected static string $resource = PackageTransactionResource::class;


    public function getTitle(): string
    {
        return 'Edit Paket Member'; // Your custom page title
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $package = Package::with(['classes.groupClassType', 'classes.classType'])->find($data['package_id']);
        $collectionData = collect($data);
        $mergedData = $collectionData->merge($package);
        $mergedData->put("class_name", $package->classes->name);
        $mergedData->put("number_of_session_left", $package->number_of_session);
        $mergedData->put("group_class_type", $package->classes->groupClassType->name);
        $mergedData->put("instructure_name", $package->classes->instructure_name);
        $mergedData->put("class_type", $package->classes->classType->name);

        return $mergedData->toArray();
    }
}
