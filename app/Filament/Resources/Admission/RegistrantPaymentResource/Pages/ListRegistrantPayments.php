<?php

namespace App\Filament\Resources\Admission\RegistrantPaymentResource\Pages;

use App\Filament\Resources\Admission\RegistrantPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRegistrantPayments extends ListRecords
{
    protected static string $resource = RegistrantPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
