<?php

namespace App\Filament\Admission\Pages\User;

use App\Filament\Admission\Pages\Profile as Page;
use App\Filament\Resources\UserResource\Components\UserForm;
use App\Filament\Resources\UserResource\Components\UserProfileForm;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;

class Father extends Page
{
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public function mount(): void
    {
        $user = Auth::user()->father?->load('profile');

        $this->form->fill($user?->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make([
                        UserForm::getNameField()
                            ->columnSpanFull(),
                        UserForm::getNikField(),
                        UserForm::getPhoneField(),
                        UserForm::getEmailField()
                    ])->columns(3),
                    Forms\Components\Section::make()
                        ->statePath('profile')
                        ->columns(3)
                        ->schema([
                            UserProfileForm::getSexField(),
                            UserProfileForm::getBloodTypeField(),
                            UserProfileForm::getKKNumberField(),
                            UserProfileForm::getPobField(),
                            UserProfileForm::getDobField(),
                            UserProfileForm::getReligionField(),
                            UserProfileForm::getNationalityField(),
                            UserProfileForm::getIsAliveField(),
                        ])->columns(2)
                ])->columnSpan(['lg' => 2]),
                Forms\Components\Group::make([
                    Forms\Components\Section::make()
                        ->schema([
                            Forms\Components\Placeholder::make('updated_at')
                                ->label('Last modified')
                                ->content(fn(): string => Auth::user()?->father?->updated_at?->diffForHumans() ?? __('Not yet filled')),
                        ])
                ])
            ])
            ->statePath('data');
    }
}
