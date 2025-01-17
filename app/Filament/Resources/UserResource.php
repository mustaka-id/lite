<?php

namespace App\Filament\Resources;

use App\Enums\UserRole;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Components as AppComponents;
use App\Filament\Resources\UserResource\Components\UserForm;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use DeepCopy\Filter\Filter;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'System';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make([
                        UserForm::getNameField(),
                        UserForm::getNikField(),
                        UserForm::getPhoneField(),
                        UserForm::getEmailField(),
                        Forms\Components\Select::make('roles')
                            ->options(UserRole::class)
                            ->multiple()
                            ->preload(),
                    ]),
                ]),
                Forms\Components\Group::make([
                    Forms\Components\Section::make(__('User avatar'))
                        ->schema([
                            Forms\Components\FileUpload::make('avatar')
                                ->label(__('Choose file'))
                                ->image()
                                ->directory('avatars')
                                ->maxSize(1024)
                                ->imageCropAspectRatio('3:4')
                                ->imageEditor()
                                ->helperText(fn() => view('components.user.avatar-instructions')),
                        ]),
                    AppComponents\Forms\TimestampPlaceholder::make()
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                AppComponents\Columns\IDColumn::make(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                AppComponents\Columns\WhatsappLinkColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                AppComponents\Columns\LastModifiedColumn::make(),
                AppComponents\Columns\CreatedAtColumn::make(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalWidth(MaxWidth::ExtraLarge),
                Tables\Actions\EditAction::make()
                    ->modalWidth(MaxWidth::ExtraLarge),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'edit.credential' => Pages\EditCredential::route('/{record}/credential'),
            'edit.profile' => Pages\EditProfile::route('/{record}/profile'),
            'manage.files' => Pages\ManageUserFiles::route('/{record}/files'),
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\EditUser::class,
            Pages\EditCredential::class,
            Pages\EditProfile::class,
            Pages\ManageUserFiles::class,
        ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
