<?php

namespace App\Filament\Resources\Admission\RegistrantResource\Pages;

use App\Filament\Components as AppComponents;
use App\Filament\Resources\Recruitment\PoolResource;
use App\Filament\Resources\Admission\RegistrantResource;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Contracts\HasHeaderActions;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class ManageUserFiles extends ManageRelatedRecords implements HasHeaderActions
{
    protected static string $resource = RegistrantResource::class;

    protected static string $relationship = 'files';

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    public static function getNavigationLabel(): string
    {
        return 'Files';
    }

    public function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('Add new file'))
                ->modalWidth(MaxWidth::Large)
                ->form(fn(Form $form) => $this->form($form)),
        ];
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
                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                    ->maxSize(1024),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('path')
            ->columns([
                AppComponents\Columns\IDColumn::make(),
                Tables\Columns\TextColumn::make('required')
                    ->label('')
                    ->badge()
                    ->color(fn($state) => $state ? 'primary' : 'gray')
                    ->width(1)
                    ->formatStateUsing(fn($state) => $state ? __('Required') : __('Optional')),
                Tables\Columns\TextColumn::make('category')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('File type')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('path')
                    ->label(__('File'))
                    ->alignCenter()
                    ->icon('heroicon-o-arrow-down-tray')
                    ->getStateUsing(fn($record) => $record->path ? __('Download') : null)
                    ->url(fn($record) => $record->path ? Storage::url($record->path) : null)
                    ->openUrlInNewTab(),
                Tables\Columns\IconColumn::make('uploaded')
                    ->boolean()
                    ->alignCenter()
                    ->width(1)
                    ->getStateUsing(fn(?Model $record) => filled($record?->path_url)),
                AppComponents\Columns\LastModifiedColumn::make()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }
}
