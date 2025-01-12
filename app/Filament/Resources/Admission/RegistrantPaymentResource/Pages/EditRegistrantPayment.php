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

    protected function afterSave(): void
    {
        if (isset($this->record->registrant)) {
            $is_paid_off = $this->record->registrant->bills->load('items')->pluck('items.*.amount')->flatten()->sum() <= $this->record->registrant->payments->sum('amount');

            $this->record->registrant->update([
                'paid_at' => $is_paid_off ? now() : null,
            ]);
        }
    }
}
