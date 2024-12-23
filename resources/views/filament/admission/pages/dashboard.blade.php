@php
$total_bills_amount = $this->registrant->bills->pluck('items.*.amount')->flatten()->sum();
$total_payments_amount = $this->registrant->payments->sum('amount');

$completeness = $this->getCompleteness();
$completeness_precentage = $completeness->where('value', true)->count() / $completeness->count() * 100;

$steps = $this->getSteps();
@endphp

<x-filament-panels::page class="fi-dashboard-page">
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <x-filament-widgets::widgets :columns="1" :data="
                                    [
                                        ...(property_exists($this, 'filters') ? ['filters' => $this->filters] : []),
                                        ...$this->getWidgetData(),
                                    ]
                                " :widgets="$this->getVisibleWidgets()" />
            <x-filament::section icon="heroicon-o-exclamation-triangle" icon-color="danger">
                <x-slot name="heading">
                    Informasi
                </x-slot>
                <ul>
                    <li class="space-y-2 border-gray-200 border-s-2 dark:border-gray-800 ps-4">
                        <div>Anda memiliki tagihan yang belum dibayar sebesar {{ Number::currency($total_bills_amount, in: 'IDR', locale: 'id') }}</div>
                        <x-filament::section compact class="ring-danger-200 dark:ring-danger-800/60 bg-danger-50 dark:bg-danger-900/40 text-danger-700 dark:text-danger-400">
                            Silakan lakukan pembayaran ke Nomor Rekening: BRI <strong>2200 0100 0311 300</strong> a.n. Madrasah Aliyah Ihsaniyya.
                        </x-filament::section>
                    </li>
                </ul>
            </x-filament::section>
            <x-filament::section icon="heroicon-o-clock" class="overflow-y-auto">
                <x-slot name="heading">
                    Progress pendaftaran
                </x-slot>
                <ol class="flex flex-col flex-wrap gap-4 text-sm font-medium text-center text-gray-500 sm:items-center sm:flex-row xl:justify-between dark:text-gray-400 sm:text-base">
                    @foreach($steps as $step)
                    <li @class(['text-blue-600 dark:text-blue-500'=> $step['value']])>
                        <span class="flex items-center">
                            @if($step['value'])
                            <x-heroicon-s-check-circle class="block size-6" />
                            @else
                            <x-heroicon-o-check-circle class="block size-6" />
                            @endif
                            <div class="text-nowrap ms-2">{{ $step['label'] }}</div>
                        </span>
                    </li>
                    @if(!$loop->last)
                    <li class="hidden sm:block">
                        <x-heroicon-o-chevron-double-right class="size-3" />
                    </li>
                    @endif
                    @endforeach
                </ol>
            </x-filament::section>
        </div>
        <div class="space-y-6">
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
    </div>
</x-filament-panels::page>