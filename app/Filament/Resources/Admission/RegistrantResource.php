<?php

namespace App\Filament\Resources\Admission;

use App\Filament\Components as AppComponents;
use App\Filament\Resources\Admission\RegistrantResource\Pages;
use App\Filament\Resources\Admission\RegistrantResource\RelationManagers;
use App\Filament\Resources\UserResource\Components\UserForm;
use App\Models\Admission\Registrant;
use App\Models\Admission\Wave;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class RegistrantResource extends Resource
{
    protected static ?string $model = Registrant::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return __('Admission');
    }

    public static function getModelLabel(): string
    {
        return __('Registrant');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make([
                        Forms\Components\Select::make('wave_id')
                            ->relationship('wave', 'name')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->default(Wave::latest()->first()->id),
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->createOptionForm([
                                Forms\Components\Group::make(UserForm::createUserSimpleForm())->columns(2)
                            ])
                            ->createOptionAction(fn(Forms\Components\Actions\Action $action) => $action->modalWidth(MaxWidth::ExtraLarge))
                            ->createOptionUsing(fn(array $data): int => User::create($data)->getKey()),
                        Forms\Components\Select::make('registered_by')
                            ->relationship('registeredBy', 'name')
                            ->default(Auth::id())
                            ->preload()
                            ->searchable(),
                        Forms\Components\DateTimePicker::make('meta.appointment_at'),
                    ]),
                    Forms\Components\Section::make()
                        ->relationship('note', fn(callable $get) => strlen($get('note.content')) > 0)
                        ->schema([
                            Forms\Components\Textarea::make('content')
                                ->label(__('Note')),
                            Forms\Components\Hidden::make('issuer_id')
                                ->default(Auth::id()),
                        ])
                ])->columnSpan(['lg' => 2]),
                Forms\Components\Group::make([
                    Forms\Components\Section::make([
                        Forms\Components\DateTimePicker::make('registered_at')
                            ->default(now()),
                        Forms\Components\DateTimePicker::make('verified_at'),
                        Forms\Components\DateTimePicker::make('validated_at'),
                        Forms\Components\DateTimePicker::make('paid_off_at'),
                        Forms\Components\DateTimePicker::make('accepted_at'),
                    ]),
                    AppComponents\Forms\TimestampPlaceholder::make()
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                AppComponents\Columns\IDColumn::make(),
                Tables\Columns\TextColumn::make('wave.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('meta.appointment_at')
                    ->label(__('Interview'))
                    ->dateTime()
                    ->sortable()
                    ->badge()
                    ->color(fn($record) => match (true) {
                        Carbon::parse($record->meta['appointment_at'])->isToday() => 'danger',
                        Carbon::parse($record->meta['appointment_at'])->gte(now()) => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('registered_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('accepted_at')
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
            'index' => Pages\ListRegistrants::route('/'),
            'create' => Pages\CreateRegistrant::route('/create'),
            'edit' => Pages\EditRegistrant::route('/{record}/edit'),
            'edit-father' => Pages\EditFather::route('/{record}/edit-father'),
            'edit-mother' => Pages\EditMother::route('/{record}/edit-mother'),
            'edit-trustee' => Pages\EditTrustee::route('/{record}/edit-trustee'),
            'edit-profile' => Pages\EditProfile::route('/{record}/edit-profile'),
            'manage-files' => Pages\ManageUserFiles::route('/{record}/manage-files'),
            'manage-educations' => Pages\ManageUserEducations::route('/{record}/manage-educations'),
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\EditRegistrant::class,
            Pages\EditProfile::class,
            Pages\ManageUserEducations::class,
            Pages\EditFather::class,
            Pages\EditMother::class,
            Pages\EditTrustee::class,
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
