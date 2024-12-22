<?php

namespace App\Filament\Admission\Resources;

use App\Filament\Components as AppComponents;
use App\Filament\Admission\Resources\UserFileResource\Pages;
use App\Filament\Admission\Resources\UserFileResource\RelationManagers;
use App\Filament\Resources\Admission\RegistrantResource\Pages\ManageUserFiles;
use App\Models\UserFile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class UserFileResource extends Resource
{
    protected static ?string $model = UserFile::class;

    protected static ?string $slug = 'files';

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?int $navigationSort = 10;

    public static function getNavigationGroup(): ?string
    {
        return __('Data');
    }

    public static function getModelLabel(): string
    {
        return __('Files');
    }

    public static function form(Form $form): Form
    {

        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->disabled()
                    ->required(),
                Forms\Components\FileUpload::make('path')
                    ->label('Choose file')
                    ->nullable()
                    ->directory('users')
                    ->columnSpanFull()
                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                    ->maxSize(1024),
            ]);
    }

    public static function table(Table $table): Table
    {
        $userFileTables = app(ManageUserFiles::class)->table($table);

        return $table
            ->columns(array_filter(
                $userFileTables->getColumns(),
                fn($column) => !in_array($column->getName(), [
                    'category'
                ])
            ))
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-arrow-up-tray')
                    ->hiddenLabel(false)
                    ->label('Upload'),
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
            'index' => Pages\ListUserFiles::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return static::getModel()::query()->whereBelongsTo(Auth::user());
    }
}
