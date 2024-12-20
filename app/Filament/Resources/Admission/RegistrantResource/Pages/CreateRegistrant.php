<?php

namespace App\Filament\Resources\Admission\RegistrantResource\Pages;

use App\Filament\Resources\Admission\RegistrantResource;
use App\Models\Admission\RegistrantBill;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Arr;

class CreateRegistrant extends CreateRecord
{
    protected static string $resource = RegistrantResource::class;

    protected function afterCreate(): void
    {
        if (count($this->record->wave->meta['payment_components'])) {
            $this->record->bills()->saveMany(
                Arr::map(
                    $this->record->wave->meta['payment_components'],
                    fn($component, $index) => new RegistrantBill([
                        'wave_id' => $this->record->wave_id,
                        'sequence' => $index + 1,
                        ...$component,
                    ])
                )
            );
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
