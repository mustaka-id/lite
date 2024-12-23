<?php

namespace App\Filament\Admission\Pages;

use App\Filament\Components as AppComponents;
use App\Filament\Resources\UserResource\Components\UserForm;
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
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Js;

class Profile extends Page implements HasForms, HasActions
{
    use InteractsWithForms, InteractsWithActions, InteractsWithFormActions;
    use InteractsWithRecord {
        configureAction as configureActionRecord;
    }

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static string $view = 'filament.admission.pages.profile';

    public ?array $data = [];

    public static function getNavigationGroup(): ?string
    {
        return __('Data');
    }

    public static function getNavigationLabel(): string
    {
        $label = str(class_basename(static::class))
            ->kebab()
            ->replace('-', ' ')
            ->title();

        return __((string) $label);
    }

    public function getTitle(): string | Htmlable
    {
        return self::getNavigationLabel();
    }

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
