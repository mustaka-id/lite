<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Forms;
use Filament\Actions;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Hash;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\Rules\Password;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Components\UserForm;

class EditCredential extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected static ?string $title = 'Credential';

    protected static ?string $navigationIcon = 'heroicon-o-key';

    public function form(Form $form): Form
    {
        return $form
            ->columns(2)
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make([
                        UserForm::getEmailField(),
                        UserForm::getEmailVerifiedField(),
                    ])->columnSpan(1),
                    Forms\Components\Section::make([
                        UserForm::getPasswordField(),
                        UserForm::getPasswordConfirmationField()
                    ]),
                ]),
            ]);
    }
}
