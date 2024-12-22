<?php

namespace App\Filament\Admission\Pages;

use App\Filament\Components as AppComponents;
use App\Filament\Resources\UserResource\Components\UserProfileForm;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Concerns\InteractsWithRecord;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Js;

class Profile extends Page implements HasForms, HasActions
{
    use InteractsWithForms, InteractsWithActions, InteractsWithFormActions;
    use InteractsWithRecord {
        configureAction as configureActionRecord;
    }

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static string $view = 'filament.admission.pages.profile';

    public ?array $data = [];

    public function mount(): void
    {
        $user = Auth::user()->load('profile');

        $this->form->fill($user->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(100)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('nik')
                            ->required()
                            ->numeric()
                            ->label('NIK')
                            ->live()
                            ->hint(fn($state) => strlen($state) . '/16')
                            ->length(16)
                            ->maxLength(16),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->maxLength(15),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->disabled()
                            ->required()
                    ])->columns(3),
                    Forms\Components\Section::make()
                        ->statePath('profile')
                        ->columns(3)
                        ->schema([
                            UserProfileForm::getSexField(),
                            UserProfileForm::getNisnField(),
                            UserProfileForm::getKKNumberField(),
                            UserProfileForm::getPobField(),
                            UserProfileForm::getDobField(),
                            UserProfileForm::getReligionField(),
                            UserProfileForm::getSiblingsCountField(),
                            UserProfileForm::getChildOrderField(),
                            UserProfileForm::getNationalityField()
                                ->default('Indonesia'),
                            UserProfileForm::getAspirationField()
                                ->columnSpanFull(),
                        ])
                ])->columnSpan(['lg' => 2]),
                Forms\Components\Group::make([
                    Forms\Components\Section::make()
                        ->schema([
                            Forms\Components\Placeholder::make('created_at')
                                ->content(fn(): string => Auth::user()?->created_at->diffForHumans()),
                            Forms\Components\Placeholder::make('updated_at')
                                ->label('Last modified')
                                ->content(fn(): string => Auth::user()?->updated_at->diffForHumans()),
                        ])
                ])
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $user = Auth::user();

        $form = $this->form->getState();

        $user->fill(Arr::except($form, ['profile']))->save();

        $user->profile()->updateOrCreate([], $form['profile'] ?? []);

        Notification::make()
            ->success()
            ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label(__('filament-panels::resources/pages/edit-record.form.actions.cancel.label'))
            ->alpineClickHandler('document.referrer ? window.history.back() : (window.location.href = ' . Js::from($this->previousUrl ?? url()->previous()) . ')')
            ->color('gray');
    }
}
