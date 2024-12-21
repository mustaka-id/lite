<?php

namespace App\Filament\Resources\Admission\RegistrantResource\Pages;

use App\Filament\Components as AppComponents;
use App\Filament\Resources\Recruitment\PoolResource;
use App\Filament\Resources\Admission\RegistrantResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManageUserFiles extends ManageRelatedRecords
{
    protected static string $resource = RegistrantResource::class;

    protected static string $relationship = 'files';

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    public static function getNavigationLabel(): string
    {
        return 'Files';
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('category'),
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\FileUpload::make('path')
                    ->nullable()
                    ->directory('users')
                    ->columnSpanFull()
                    ->acceptedFileTypes(['application/pdf', 'image/jpg', 'image/jpeg', 'image/png'])
                    ->maxSize(1024),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('path')
            ->columns([
                AppComponents\Columns\IDColumn::make(),
                Tables\Columns\IconColumn::make('uploaded')
                    ->label(__('Uploaded'))
                    ->boolean()
                    ->alignCenter()
                    ->width(1)
                    ->getStateUsing(fn(?Model $record) => filled($record?->path_url)),
                Tables\Columns\TextColumn::make('category')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('path')
                    ->label(__('Attachment'))
                    ->icon('heroicon-o-document-text')
                    ->formatStateUsing(fn(?Model $record): string => class_basename($record?->path_url))
                    ->url(fn(?Model $record) => $record?->path_url)
                    ->openUrlInNewTab(),
                AppComponents\Columns\LastModifiedColumn::make()
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('Add new file')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }
}
