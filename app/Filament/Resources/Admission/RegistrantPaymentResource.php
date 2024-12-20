<?php

namespace App\Filament\Resources\Admission;

use App\Enums\PaymentMethod;
use App\Filament\Components as AppComponents;
use App\Filament\Resources\Admission\RegistrantPaymentResource\Pages;
use App\Filament\Resources\Admission\RegistrantPaymentResource\RelationManagers;
use App\Models\Admission\RegistrantPayment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                            ->required(),
                        Forms\Components\Select::make('bill_id')
                            ->relationship('bill', 'name')
                            ->required(),
                        Forms\Components\TextInput::make('code')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->numeric(),
                        Forms\Components\Select::make('method')
                            ->options(PaymentMethod::class),
                        Forms\Components\DateTimePicker::make('paid_at'),
                        Forms\Components\Select::make('payer_id')
                            ->relationship('payer', 'name')
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('receiver_id')
                            ->required()
                            ->relationship('receiver', 'name')
                            ->preload()
                            ->default(auth()->id()),
                    ]),
                    Forms\Components\Section::make([
                        Forms\Components\Textarea::make('content'),
                        Forms\Components\Hidden::make('issuer_id')
                            ->default(auth()->id()),
                    ])->relationship('note')
                ])->columnSpan(['lg' => 2]),
                Forms\Components\Group::make([
                    AppComponents\Forms\TimestampPlaceholder::make()
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                AppComponents\Columns\IDColumn::make(),
                Tables\Columns\TextColumn::make('registrant.id')
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
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('paid_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payer_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('receiver_id')
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
