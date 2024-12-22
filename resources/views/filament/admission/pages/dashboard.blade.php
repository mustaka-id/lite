@php
$total_bills_amount = $this->registrant->bills->pluck('items.*.amount')->flatten()->sum();
$total_payments_amount = $this->registrant->payments->sum('amount');

$completeness = $this->getCompleteness();
$completeness_precentage = $completeness->where('value', true)->count() / $completeness->count() * 100;
@endphp

<x-filament-panels::page class="fi-dashboard-page">
    <div class="grid grid-cols-3 gap-6">
        <div class="space-y-6 lg:col-span-2">
            <x-filament-widgets::widgets :columns="1" :data="
                        [
                            ...(property_exists($this, 'filters') ? ['filters' => $this->filters] : []),
                            ...$this->getWidgetData(),
                        ]
                    " :widgets="$this->getVisibleWidgets()" />
            <x-filament::section icon="heroicon-o-pencil-square">
                <x-slot name="heading">
                    Kelengkapan data pendaftaran
                </x-slot>
                <x-slot name="headerEnd">
                    <x-filament::badge :color="$completeness_precentage == 100 ? 'success' : 'warning'">
                        {{ $completeness_precentage }}%
                    </x-filament::badge>
                </x-slot>
                <div class="space-y-4">
                    @foreach($completeness as $completion)
                    <a href="{{ $completion['url'] }}" class="block group">
                        <x-filament::section compact class="transition-colors dark:group-hover:bg-gray-800 group-hover:bg-gray-50">
                            <div class="flex items-center justify-between gap-4">
                                <div @class(['grid rounded-full place-items-center size-8 text-sm font-semibold', $completion['value'] ? 'bg-success-500/20 text-success-700 dark:text-success-400' : 'bg-gray-100 dark:bg-gray-800' ])>{{ $loop->iteration }}</div>
                                <div class="grow">{{ $completion['label'] }}</div>
                                @if($completion['value'])
                                <x-heroicon-o-check-circle class="size-6 text-success-500" />
                                @else
                                <x-heroicon-o-arrow-right class="size-5" />
                                @endif
                            </div>
                        </x-filament::section>
                    </a>
                    @endforeach
                </div>
            </x-filament::section>
        </div>
        <div class="space-y-6">
            <x-filament::section icon="heroicon-o-exclamation-triangle" icon-color="warning">
                <x-slot name="heading">
                    Informasi
                </x-slot>
                <ul>
                    <li>Anda memiliki tagihan yang belum dibayar sebesar {{ Number::currency($total_bills_amount, in: 'IDR', locale: 'id') }}</li>
                </ul>
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>