<?php

namespace App\Filament\Resources\Admission;

use App\Enums\PaymentMethod;
use App\Filament\Components as AppComponents;
use App\Filament\Resources\Admission\RegistrantPaymentResource\Pages;
use App\Filament\Resources\Admission\RegistrantPaymentResource\RelationManagers;
use App\Models\Admission\Registrant;
use App\Models\Admission\RegistrantBill;
use App\Models\Admission\RegistrantPayment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class RegistrantPaymentResource extends Resource
{
    protected static ?string $model = RegistrantPayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?int $navigationSort = 4;

    public static function getNavigationGroup(): ?string
    {
        return __('Admission');
    }

    public static function getModelLabel(): string
    {
        return __('Payment');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make([
                        Forms\Components\Select::make('registrant_id')
                            ->relationship('registrant', 'id')
                            ->getOptionLabelFromRecordUsing(fn($record) => $record->user->name)
                            ->required()
                            ->preload()
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn($state, callable $set) => $set('payer_id', Registrant::find($state)?->user_id)),
                    ]),
                    Forms\Components\Section::make()
                        ->visible(fn(callable $get) => filled($get('registrant_id')))
                        ->columns(2)
                        ->schema([
                            Forms\Components\Select::make('bill_id')
                                ->relationship('bill', 'name', fn($query, callable $get) => $query->where('registrant_id', $get('registrant_id')))
                                ->required()
                                ->preload()
                                ->searchable()
                                ->live()
                                ->afterStateUpdated(fn($state, callable $set) => $set('amount', RegistrantBill::find($state)?->items()?->sum('amount') ?? 0)),
                            Forms\Components\TextInput::make('code')
                                ->required()
                                ->maxLength(255)
                                ->default('TRX-' . time()),
                            Forms\Components\TextInput::make('name')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('amount')
                                ->required()
                                ->numeric(),
                            Forms\Components\Select::make('method')
                                ->options(PaymentMethod::class)
                                ->preload()
                                ->searchable()
                                ->default(PaymentMethod::Transfer),
                            Forms\Components\DateTimePicker::make('paid_at')
                                ->required()
                                ->default(now()),
                            Forms\Components\Select::make('payer_id')
                                ->relationship('payer', 'name')
                                ->searchable()
                                ->required(),
                            Forms\Components\Select::make('receiver_id')
                                ->required()
                                ->relationship('receiver', 'name')
                                ->getOptionLabelFromRecordUsing(fn($record) => $record->user->name)
                                ->preload()
                                ->searchable()
                                ->default(Auth::user()->employee?->id),
                        ]),
                ])->columnSpan(['lg' => 2]),
                Forms\Components\Group::make([
                    Forms\Components\Section::make()
                        ->relationship('note', fn(callable $get) => strlen($get('note.content')) > 0)
                        ->schema([
                            Forms\Components\Textarea::make('content')
                                ->label(__('Note')),
                            Forms\Components\Hidden::make('issuer_id')
                                ->default(Auth::id()),
                        ]),
                    AppComponents\Forms\TimestampPlaceholder::make()
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                AppComponents\Columns\IDColumn::make(),
                Tables\Columns\TextColumn::make('registrant.user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bill.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('paid_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payer.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('receiver.user.name')
                    ->numeric()
                    ->sortable(),
                AppComponents\Columns\LastModifiedColumn::make(),
                AppComponents\Columns\CreatedAtColumn::make()
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRegistrantPayments::route('/'),
            'create' => Pages\CreateRegistrantPayment::route('/create'),
            'edit' => Pages\EditRegistrantPayment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
