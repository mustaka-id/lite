<?php

namespace App\Filament\Resources\Admission\RegistrantResource\Pages;

use App\Enums\Support\Grade;
use App\Filament\Components as AppComponents;
use App\Filament\Resources\Admission\RegistrantResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class ManageUserEducations extends ManageRelatedRecords
{
    protected static string $resource = RegistrantResource::class;

    protected static string $relationship = 'educations';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Educations';
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
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
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
