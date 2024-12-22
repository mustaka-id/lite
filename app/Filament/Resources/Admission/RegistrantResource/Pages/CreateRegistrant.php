<?php

namespace App\Filament\Resources\Admission\RegistrantResource\Pages;

use App\Filament\Admission\Pages\Register;
use App\Filament\Resources\Admission\RegistrantResource;
use App\Models\Admission\RegistrantBill;
use App\Models\Admission\RegistrantBillItem;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Arr;

class CreateRegistrant extends CreateRecord
{
    protected static string $resource = RegistrantResource::class;

    protected function afterCreate(): void
    {
        if (isset($this->record->wave->meta['payment_components']) && count($this->record->wave->meta['payment_components'])) {
            $bill = Register::assignBills($this->record);
            Register::assignFiles($this->record);
        }
    }


    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
