<?php

namespace App\Filament\Resources\UserResource\Components;

use App\Enums\BloodType;
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

    public static function getBloodTypeField($name = 'blood_type'): Forms\Components\Radio
    {
        return Forms\Components\Radio::make($name)
            ->options(BloodType::class)
            ->inline()
            ->columnSpanFull();
    }

    public static function getNisnField($name = 'nisn'): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make($name)
            ->numeric()
            ->label('NISN')
            ->live()
            ->hint(fn($state) => strlen($state) . '/10')
            ->length(10)
            ->maxLength(10);
    }

    public static function getKKNumberField($name = 'kk_number'): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make($name)
            ->numeric()
            ->label('KK Number')
            ->live()
            ->hint(fn($state) => strlen($state) . '/16')
            ->length(16)
            ->maxLength(16);
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
            ->numeric()
            ->maxLength(1);
    }

    public static function getChildOrderField($name = 'child_order'): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make($name)
            ->numeric()
            ->maxLength(1);
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

    public static function getLastEducationField($name = 'last_education_grade'): Forms\Components\Select
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
            ->maxLength(100)
            ->default('Indonesia');
    }
}
