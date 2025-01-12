<?php

namespace App\Filament\Resources\Admission;

use App\Filament\Components as AppComponents;
use App\Filament\Resources\Admission\RegistrantBillResource\Pages;
use App\Filament\Resources\Admission\RegistrantBillResource\RelationManagers;
use App\Models\Admission\RegistrantBill;
use Awcodes\TableRepeater;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class RegistrantBillResource extends Resource
{
    protected static ?string $model = RegistrantBill::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return __('Admission');
    }

    public static function getModelLabel(): string
    {
        return __('Bill');
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
                            ->required(),
                        Forms\Components\TextInput::make('name')
                            ->required(),
                    ]),
                    Forms\Components\Section::make()
                        ->relationship('note', fn(callable $get) => strlen($get('note.content')) > 0)
                        ->schema([
                            Forms\Components\Textarea::make('content')
                                ->label(__('Note')),
                            Forms\Components\Hidden::make('issuer_id')
                                ->default(Auth::id()),
                        ]),
                ])->columnSpan(['lg' => 2]),
                Forms\Components\Group::make([
                    AppComponents\Forms\TimestampPlaceholder::make()
                ]),
                Forms\Components\Section::make([
                    TableRepeater\Components\TableRepeater::make('meta.payment_components')
                        ->relationship('items')
                        ->minItems(1)
                        ->reorderable()
                        ->headers([
                            TableRepeater\Header::make('category'),
                            TableRepeater\Header::make('name'),
                            TableRepeater\Header::make('amount'),
                        ])
                        ->schema([
                            Forms\Components\TextInput::make('category')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('amount')
                                ->required()
                                ->numeric()
                                ->default(0),
                        ])
                        ->minItems(1)
                        ->columns(3),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                AppComponents\Columns\IDColumn::make(),
                Tables\Columns\TextColumn::make('registrant.user.name')
                    ->label(__('Registrant'))
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('items_sum_amount')
                    ->label(__('Amount'))
                    ->sum('items', 'amount')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount_paid')
                    ->label(__('Amount paid'))
                    ->getStateUsing(function (RegistrantBill $record) {
                        return $record->registrant->payments->sum('amount');
                    })
                    ->money('IDR'),
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
            'index' => Pages\ListRegistrantBills::route('/'),
            'create' => Pages\CreateRegistrantBill::route('/create'),
            'edit' => Pages\EditRegistrantBill::route('/{record}/edit'),
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
