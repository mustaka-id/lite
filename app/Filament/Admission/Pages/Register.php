<?php

namespace App\Filament\Admission\Pages;

use App\Models\Admission\Registrant;
use App\Models\Admission\RegistrantBill;
use App\Models\Admission\RegistrantBillItem;
use App\Models\Admission\Wave;
use App\Models\UserFile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Register extends Page
{
    public $waves;

    protected function beforeFill(): void
    {
        $this->waves = Wave::opened()->get();
    }

    public function form(Form $form): Form
    {
        return $form
            ->disabled($this->waves->count() == 0)
            ->schema([
                Forms\Components\Select::make('wave_id')
                    ->label('Wave')
                    ->options($this->waves->pluck('name', 'id'))
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
            'registered_by' => $user->id
        ])) {
            $bill = static::assignBills($registrant);
            static::assignFiles($registrant);
        }

        return $user;
    }

    public static function assignBills(Registrant $registrant): RegistrantBill | null
    {
        if (isset($registrant->wave->meta['payment_components']) && count($registrant->wave->meta['payment_components']))
            if ($bill = $registrant->bills()->create([
                'name' => "Pembayaran PPDB {$registrant->wave->name}"
            ]))
                $bill->items()->saveMany(Arr::map(
                    $registrant->wave->meta['payment_components'],
                    fn($component, $index) => new RegistrantBillItem([
                        'sequence' => $index + 1,
                        ...$component,
                    ])
                ));

        return $bill ?? null;
    }

    public static function assignFiles(Registrant $registrant): void
    {
        if (isset($registrant->wave->meta['files']) && count($registrant->wave->meta['files']))
            $registrant->files()->saveMany(Arr::map(
                $registrant->wave->meta['files'],
                fn($component, $index) => new UserFile($component)
            ));
    }
}
