<?php

namespace App\Filament\Resources\Admission\RegistrantPaymentResource\Pages;

use App\Filament\Resources\Admission\RegistrantPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRegistrantPayment extends EditRecord
{
    protected static string $resource = RegistrantPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
