<?php

namespace App\Filament\Resources\Admission\RegistrantResource\Pages;

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
        if (isset($this->record->wave->meta['payment_components']) && count($this->record->wave->meta['payment_components']))
            if ($bill = $this->record->bills()->create([
                'name' => "Pembayaran PSB {$this->record->wave->name}"
            ]))
                $bill->items()->saveMany(Arr::map(
                    $this->record->wave->meta['payment_components'],
                    fn($component, $index) => new RegistrantBillItem([
                        'sequence' => $index + 1,
                        ...$component,
                    ])
                ));
    }


    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
