<?php

namespace App\Filament\Resources\Admission\RegistrantPaymentResource\Pages;

use App\Filament\Resources\Admission\RegistrantPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRegistrantPayment extends CreateRecord
{
    protected static string $resource = RegistrantPaymentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        if (isset($this->record->registrant)) {
            $is_paid_off = $this->record->registrant->bills->load('items')->pluck('items.*.amount')->flatten()->sum() <= $this->record->registrant->payments->sum('amount');

            $this->record->registrant->update([
                'paid_at' => $is_paid_off ? now() : null,
            ]);
        }
    }
}
