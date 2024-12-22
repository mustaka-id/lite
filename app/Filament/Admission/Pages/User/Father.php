<?php

namespace App\Filament\Admission\Pages\User;

use App\Enums\Parentship\ParentType;
use App\Enums\Sex;
use App\Filament\Admission\Pages\Profile as Page;
use App\Filament\Resources\UserResource\Components\UserForm;
use App\Filament\Resources\UserResource\Components\UserProfileForm;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class Father extends Page
{
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public function getParentType(): ParentType
    {
        return ParentType::Father;
    }

    public function mount(): void
    {
        $user = Auth::user()->{$this->getParentType()->value}?->parent?->load('profile');

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
                    ])->columns(2),
                    Forms\Components\Section::make()
                        ->statePath('profile')
                        ->columns(3)
                        ->schema([
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
                                ->content(fn(): string => Auth::user()?->{$this->getParentType()->value}?->updated_at?->diffForHumans() ?? __('Not yet filled')),
                        ])
                ])
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $user = Auth::user();

        $form = $this->form->getState();

        $parent = User::updateOrCreate([
            'nik' => $form['nik']
        ], Arr::only($form, [
            'name',
            'phone'
        ]));

        $form['profile']['sex'] = Sex::Male;
        $parent->profile()->updateOrCreate([], $form['profile'] ?? []);

        $user->{$this->getParentType()->value}()->updateOrCreate([
            'user_id' => $user->id,
            'type' => $this->getParentType()
        ], [
            'parent_id' => $parent->id,
        ]);

        Notification::make()
            ->success()
            ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
            ->send();
    }
}
