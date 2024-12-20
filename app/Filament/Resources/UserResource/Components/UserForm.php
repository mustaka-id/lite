<?php

namespace App\Filament\Resources\UserResource\Components;

use App\Models\User;
use Filament\Forms;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserForm
{
    public static function createUserSimpleForm(): array
    {
        return [
            self::getNameField(),
            self::getNikField(),
            self::getPhoneField(),
        ];
    }

    public static function createUserCredentialForm(): array
    {
        return [
            self::getEmailField(),
            self::getEmailVerifiedField(),
            self::getPasswordField(),
            self::getPasswordConfirmationField(),
        ];
    }

    //All fields
    public static function getNameField($name = 'name'): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make($name)
            ->maxLength(100)
            ->required();
    }

    public static function getNikField($name = 'nik'): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make($name)
            ->label('NIK')
            ->unique(ignoreRecord: true)
            ->numeric()
            ->rules(['digits:16'])
            ->required()
            ->live(onBlur: true)
            ->hint(fn($state) => 'Currently ' . strlen($state) . ' digits.');
    }

    public static function getEmailField($name = 'email'): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make($name)
            ->email()
            ->unique(ignoreRecord: true);
        // ->hint(fn(Model $record) => $record->email_verified_at);
    }

    public static function getEmailVerifiedField($name = 'email_verified_at'): Forms\Components\DateTimePicker
    {
        return Forms\Components\DateTimePicker::make($name)
            ->seconds(false);
    }

    public static function getPhoneField($name = 'phone'): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make($name)
            ->tel()
            ->numeric()
            ->unique(ignoreRecord: true);
    }

    public static function getPasswordField($name = 'password'): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make($name)
            ->label('Password')
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->rule(Password::default())
            ->autocomplete('new-password')
            ->dehydrated(fn($state): bool => filled($state))
            ->dehydrateStateUsing(fn($state): string => Hash::make($state))
            ->live(debounce: 500)
            ->same('passwordConfirmation');
    }

    public static function getPasswordConfirmationField($name = 'passwordConfirmation'): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make($name)
            ->label('Password confirmation')
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->visible(fn(Forms\Get $get): bool => filled($get('password')))
            ->dehydrated(false);
    }
}
