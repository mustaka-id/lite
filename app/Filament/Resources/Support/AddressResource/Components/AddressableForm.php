<?php

namespace App\Filament\Resources\Support\AddressResource\Components;

use App\Models\Support\Address;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Illuminate\Contracts\Support\Htmlable;

class AddressableForm extends Section
{
    public static function make(string | array | Htmlable | Closure | null $heading = 'Address'): static
    {
        $static = app(static::class, ['heading' => $heading]);
        $static->configure();

        $static
            ->relationship('address')
            ->columns(6)
            ->schema([
                Forms\Components\TextInput::make('primary')
                    ->required()
                    ->columnSpan(2),
                Forms\Components\TextInput::make('secondary')
                    ->columnSpan(2),
                Forms\Components\TextInput::make('rt')
                    ->label('RT')
                    ->numeric()
                    ->maxLength(3)
                    ->required(),
                Forms\Components\TextInput::make('rw')
                    ->label('RW')
                    ->numeric()
                    ->maxLength(3),
                Forms\Components\TextInput::make('village')
                    ->columnSpan(2),
                Forms\Components\TextInput::make('district')
                    ->required()
                    ->columnSpan(2),
                Forms\Components\TextInput::make('regency')
                    ->required()
                    ->columnSpan(2),
                Forms\Components\TextInput::make('province')
                    ->required()
                    ->columnSpan(3),
                Forms\Components\TextInput::make('country')
                    ->required()
                    ->columnSpan(3),
                Forms\Components\TextInput::make('zipcode')
                    ->numeric()
                    ->required()
                    ->columnSpan(2)
                    ->maxLength(5),
            ]);

        return $static;
    }
}
