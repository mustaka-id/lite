<?php

namespace App\Filament\Admission\Resources;

use App\Enums\Support\Grade;
use App\Filament\Components as AppComponents;
use App\Filament\Admission\Resources\UserEducationResource\Pages;
use App\Filament\Admission\Resources\UserEducationResource\RelationManagers;
use App\Models\UserEducation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserEducationResource extends Resource
{
    protected static ?string $model = UserEducation::class;

    protected static ?string $slug = 'educations';

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?int $navigationSort = 10;

    public static function getNavigationGroup(): ?string
    {
        return __('Data');
    }

    public static function getModelLabel(): string
    {
        return __('Education History');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(Auth::id())
                    ->dehydrated(),
                Forms\Components\Select::make('grade')
                    ->options([
                        Grade::SMP->value => Grade::SMP->getLabel(),
                        Grade::SD->value => Grade::SD->getLabel(),
                        Grade::TK->value => Grade::TK->getLabel()
                    ])
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label(__('School Name'))
                    ->required(),
                Forms\Components\TextInput::make('npsn')
                    ->numeric()
                    ->label('NPSN')
                    ->required()
                    ->live()
                    ->hint(fn($state) => strlen($state) . '/8')
                    ->length(8)
                    ->maxLength(8),
                Forms\Components\TextInput::make('certificate_number')
                    ->label(__('School certificate number')),
                Forms\Components\FileUpload::make('certificate')
                    ->label(__('School certificate file'))
                    ->directory('educations')
                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                    ->maxSize(1024),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                AppComponents\Columns\IDColumn::make(),
                Tables\Columns\TextColumn::make('grade'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('certificate_number')
                    ->label(__('School certificate number')),
                Tables\Columns\TextColumn::make('certificate')
                    ->alignCenter()
                    ->label(__('School certificate file'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->getStateUsing(fn($record) => $record->certificate ? __('Download') : null)
                    ->url(fn($record) => $record->certificate ? Storage::url($record->certificate) : null)
                    ->openUrlInNewTab(),
                AppComponents\Columns\LastModifiedColumn::make(),
                AppComponents\Columns\CreatedAtColumn::make()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListUserEducation::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return static::getModel()::query()->whereBelongsTo(Auth::user());
    }
}
