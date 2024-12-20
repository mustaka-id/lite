<?php

namespace App\Filament\Resources\Admission\RegistrantBillResource\Pages;

use App\Filament\Resources\Admission\RegistrantBillResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRegistrantBill extends CreateRecord
{
    protected static string $resource = RegistrantBillResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
