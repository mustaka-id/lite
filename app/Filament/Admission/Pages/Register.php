<?php

namespace App\Filament\Admission\Pages;

use App\Models\Admission\RegistrantBillItem;
use App\Models\Admission\Wave;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Register extends Page
{
    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('wave_id')
                ->label('Wave')
                ->options(Wave::opened()->pluck('name', 'id'))
                ->required(),
            $this->getNameFormComponent(),
            $this->getEmailFormComponent(),
            $this->getPasswordFormComponent(),
            $this->getPasswordConfirmationFormComponent(),
        ]);
    }

    protected function handleRegistration(array $data): Model
    {
        $user = $this->getUserModel()::create($data);

        if ($registrant = $user->registrants()->create([
            'wave_id' => $data['wave_id'],
            'registered_at' => now(),
            'registered_by' => $user->id
        ]))
            if (isset($registrant->wave->meta['payment_components']) && count($registrant->wave->meta['payment_components']))
                if ($bill = $registrant->bills()->create([
                    'name' => "Pembayaran PSB {$registrant->wave->name}"
                ]))
                    $bill->items()->saveMany(Arr::map(
                        $registrant->wave->meta['payment_components'],
                        fn($component, $index) => new RegistrantBillItem([
                            'sequence' => $index + 1,
                            ...$component,
                        ])
                    ));

        return $user;
    }
}
