<?php

namespace App\Filament\Admission\Pages;

use Indonesia;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Js;

class Address extends Profile
{
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    public ?array $data = [];

    public static function getNavigationLabel(): string
    {
        return __('Home Address');
    }

    public function getTitle(): string | Htmlable
    {
        return self::getNavigationLabel();
    }

    public function mount(): void
    {
        $user = Auth::user()->load('address');

        $this->form->fill($user->address?->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make()
                        ->columns(6)
                        ->schema([
                            Forms\Components\TextInput::make('primary')
                                ->label(__('Name and number of street or name of residence'))
                                ->required()
                                ->columnSpan(2),
                            Forms\Components\TextInput::make('secondary')
                                ->label(__('Hamlet'))
                                ->columnSpan(2),
                            Forms\Components\TextInput::make('rt')
                                ->label('RT')
                                ->numeric()
                                ->maxLength(3)
                                ->required(),
                            Forms\Components\TextInput::make('rw')
                                ->label('RW')
                                ->numeric()
                                ->maxLength(3),
                            Forms\Components\Select::make('village_id')
                                ->label(__('Village'))
                                ->searchable()
                                ->getSearchResultsUsing(function (string $search): array {
                                    $villages = Indonesia::search($search)->paginateVillages(20, ['district.city.province']);
                                    return $villages->mapWithKeys(fn($village) => [$village->id => implode(', ', array_filter([
                                        $village->name,
                                        $village->district->name,
                                        $village->district->city->name,
                                        $village->district->city->province->name
                                    ]))])->toArray();
                                })
                                ->getOptionLabelUsing(function ($value): ?string {
                                    $village = Indonesia::findVillage($value, ['district.city.province']);
                                    return implode(', ', array_filter([
                                        $village->name,
                                        $village->district->name,
                                        $village->district->city->name,
                                        $village->district->city->province->name
                                    ]));
                                })
                                ->required()
                                ->columnSpanFull(),
                            Forms\Components\TextInput::make('country')
                                ->default('Indonesia')
                                ->required()
                                ->columnSpan(3),
                            Forms\Components\TextInput::make('zipcode')
                                ->numeric()
                                ->required()
                                ->columnSpan(2)
                                ->maxLength(5),
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

        $user->address()->updateOrCreate([], $form ?? []);

        Notification::make()
            ->success()
            ->title(__('Data saved successfully'))
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
