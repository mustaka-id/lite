<?php

namespace App\Filament\Resources\UserResource\Components;

use App\Enums\MonthlyIncome;
use App\Enums\Religion;
use App\Enums\Sex;
use App\Enums\Support\Grade;
use Carbon\Month;
use Filament\Forms;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserProfileForm
{
    //all fields
    public static function getPobField($name = 'pob'): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make($name)
            ->label('Birth place');
    }

    public static function getDobField($name = 'dob'): Forms\Components\DatePicker
    {
        return Forms\Components\DatePicker::make($name)
            ->label('Date of birth')
            ->maxDate(today());
    }

    public static function getSexField($name = 'sex'): Forms\Components\Radio
    {
        return Forms\Components\Radio::make($name)
            ->options(Sex::class)
            ->inline()
            ->columnSpanFull();
    }

    public static function getNisnField($name = 'nisn'): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make($name)
            ->numeric()
            ->required()
            ->label('NISN')
            ->hint(fn($state) => 'Min 10 digits. (' . strlen($state) . ' digits.)');
    }

    public static function getIsAliveField($name = 'is_alive'): Forms\Components\ToggleButtons
    {
        return Forms\Components\ToggleButtons::make($name)
            ->boolean()
            ->colors([
                false => 'danger',
                true => 'primary',
            ])
            ->label('Alive')
            ->inline()
            ->default(true);
    }

    public static function getSiblingsCountField($name = 'siblings_count'): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make($name)
            ->numeric();
    }

    public static function getChildOrderField($name = 'child_order'): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make($name)
            ->numeric();
    }

    public static function getReligionField($name = 'religion'): Forms\Components\Select
    {
        return Forms\Components\Select::make($name)
            ->options(Religion::class);
    }

    public static function getAspirationField($name = 'aspiration'): Forms\Components\TagsInput
    {
        return Forms\Components\TagsInput::make($name)
            ->placeholder('')
            ->splitKeys(['Tab', ',']);
    }

    public static function getLastEducationField($name = 'last_education'): Forms\Components\Select
    {
        return Forms\Components\Select::make($name)
            ->options(Grade::class);
    }

    public static function getMonthlyIncomeField($name = 'monthly_income'): Forms\Components\Select
    {
        return Forms\Components\Select::make($name)
            ->options(MonthlyIncome::class);
    }

    public static function getNationalityField($name = 'nationality'): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make($name)
            ->maxLength(100);
    }
}
