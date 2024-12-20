<?php

namespace App\Filament\Resources\Admission\RegistrantBillResource\Pages;

use App\Filament\Resources\Admission\RegistrantBillResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRegistrantBill extends EditRecord
{
    protected static string $resource = RegistrantBillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
