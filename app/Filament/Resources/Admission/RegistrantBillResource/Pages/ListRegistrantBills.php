<?php

namespace App\Filament\Resources\Admission\RegistrantBillResource\Pages;

use App\Filament\Resources\Admission\RegistrantBillResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRegistrantBills extends ListRecords
{
    protected static string $resource = RegistrantBillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
