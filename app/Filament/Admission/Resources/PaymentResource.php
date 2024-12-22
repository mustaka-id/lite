<?php

namespace App\Filament\Admission\Resources;

use App\Filament\Components as AppComponents;
use App\Filament\Admission\Resources\PaymentResource\Pages;
use App\Filament\Admission\Resources\PaymentResource\RelationManagers;
use App\Models\Admission\RegistrantPayment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class PaymentResource extends Resource
{
    protected static ?string $model = RegistrantPayment::class;

    protected static ?string $slug = 'payments';

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return __('Admission');
    }

    public static function getModelLabel(): string
    {
        return __('Payment');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                AppComponents\Columns\IDColumn::make(),
                //
                AppComponents\Columns\LastModifiedColumn::make(),
                AppComponents\Columns\CreatedAtColumn::make()
            ])
            ->filters([
                //
            ])
            ->actions([]);
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
            'index' => Pages\ListPayments::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return static::getModel()::query()->whereRelation('registrant', 'user_id', '=', Auth::id());
    }
}
