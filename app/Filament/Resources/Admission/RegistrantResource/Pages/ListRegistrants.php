<?php

namespace App\Filament\Resources\Admission\RegistrantResource\Pages;

use App\Filament\Resources\Admission\RegistrantResource;
use App\Models\Admission\Registrant;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class ListRegistrants extends ListRecords
{
    protected static string $resource = RegistrantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $filters = [
            [
                'column' => 'registered_at',
                'label' => __('Registered'),
                'icon' => 'heroicon-o-calendar-days',
            ],
            [
                'column' => 'verified_at',
                'label' => __('Verified'),
                'icon' => 'heroicon-o-check',
            ],
            [
                'column' => 'validated_at',
                'label' => __('Validated'),
                'icon' => 'heroicon-o-clipboard-document-check',
            ],
            [
                'column' => 'paid_off_at',
                'label' => __('Paid Off'),
                'icon' => 'heroicon-o-banknotes',
            ],
            [
                'column' => 'accepted_at',
                'label' => __('Accepted'),
                'icon' => 'heroicon-o-check-badge',
            ],
        ];

        return [
            'all' => Tab::make()->icon('heroicon-o-wallet'),
            ...Arr::map(
                $filters,
                fn($filter) => Tab::make($filter['label'])
                    ->icon($filter['icon'])
                    ->modifyQueryUsing(fn(Builder $query) => $query->whereNotNull($filter['column']))
                    ->badge(Registrant::query()->whereNotNull($filter['column'])->count())
            ),
        ];
    }
}
