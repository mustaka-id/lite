<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Components as AppComponents;
use App\Filament\Resources\Recruitment\PoolResource;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class ManageUserFiles extends ManageRelatedRecords
{
    protected static string $resource = UserResource::class;

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
                    ->alignCenter()
                    ->icon('heroicon-o-arrow-down-tray')
                    ->getStateUsing(fn($record) => $record->path ? __('Download') : null)
                    ->url(fn($record) => $record->path ? Storage::url($record->path) : null)
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
