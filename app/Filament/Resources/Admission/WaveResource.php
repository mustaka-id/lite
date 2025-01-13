<?php

namespace App\Filament\Resources\Admission;

use App\Filament\Components as AppComponents;
use App\Filament\Resources\Admission\WaveResource\Pages;
use App\Filament\Resources\Admission\WaveResource\RelationManagers;
use App\Models\Admission\Wave;
use Awcodes\TableRepeater;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WaveResource extends Resource
{
    protected static ?string $model = Wave::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return __('Admission');
    }

    public static function getModelLabel(): string
    {
        return __('Wave');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make([
                        Forms\Components\Select::make('year_id')
                            ->relationship('year', 'name')
                            ->required()
                            ->preload()
                            ->searchable(),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                    ]),
                    Forms\Components\Section::make([
                        TableRepeater\Components\TableRepeater::make('meta.payment_components')
                            ->headers([
                                TableRepeater\Header::make('category')
                                    ->label(__('Category')),
                                TableRepeater\Header::make('name')
                                    ->label(__('Name')),
                                TableRepeater\Header::make('amount')
                                    ->label(__('Amount')),
                            ])
                            ->schema([
                                Forms\Components\TextInput::make('category')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('amount')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->minItems(1)
                            ->columns(3),
                    ]),
                    Forms\Components\Section::make([
                        TableRepeater\Components\TableRepeater::make('meta.files')
                            ->headers([
                                TableRepeater\Header::make('category')
                                    ->label(__('Category')),
                                TableRepeater\Header::make('name')
                                    ->label(__('Name')),
                                TableRepeater\Header::make('required')
                                    ->label(__('Wajib?')),
                            ])
                            ->schema([
                                Forms\Components\Select::make('category')
                                    ->required()
                                    ->options(['user', 'registrant']),
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Toggle::make('required')
                            ])
                            ->minItems(0)
                            ->columns(3),
                    ]),
                ])->columnSpan(['lg' => 2]),
                Forms\Components\Group::make([
                    Forms\Components\Section::make([
                        Forms\Components\DateTimePicker::make('opened_at'),
                        Forms\Components\DateTimePicker::make('closed_at'),
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
                Tables\Columns\TextColumn::make('year.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('opened_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('closed_at')
                    ->dateTime()
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
            'index' => Pages\ListWaves::route('/'),
            'create' => Pages\CreateWave::route('/create'),
            'edit' => Pages\EditWave::route('/{record}/edit'),
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
