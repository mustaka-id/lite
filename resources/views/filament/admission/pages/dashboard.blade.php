@php
$total_bills_amount = $this->registrant->bills->pluck('items.*.amount')->flatten()->sum();
$total_payments_amount = $this->registrant->payments->sum('amount');
$remain = $total_bills_amount - $total_payments_amount;

$completeness = $this->getCompleteness();
$completeness_precentage = round(($completeness->where('value', true)->count() / $completeness->count()) * 100);

$steps = $this->getSteps();

$appointed = isset($registrant->meta['appointment_at']);
@endphp

<x-filament-panels::page class="fi-dashboard-page">
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <x-filament-widgets::widgets :columns="1" :data="[
                ...property_exists($this, 'filters') ? ['filters' => $this->filters] : [],
                ...$this->getWidgetData(),
            ]" :widgets="$this->getVisibleWidgets()" />
            <x-filament::section icon="heroicon-o-video-camera" heading="Jadwal wawancara">
                @if (!$appointed)
                <x-slot name="headerEnd">
                    <x-filament::badge color="danger" icon="heroicon-o-x-circle">
                        Belum diatur
                    </x-filament::badge>
                </x-slot>
                @endif
                <x-filament-panels::form id="form" :wire:key="$this->getId() . '.forms.' . $this->getFormStatePath()" wire:submit="saveAppointment">
                    @if (!$appointed)
                    <div class="flex gap-6">
                        <div class="w-full">
                            {{ $this->form }}
                        </div>
                        {{-- <x-filament::input.wrapper class="w-full"> --}}
                            {{--
                            <x-filament::input type="datetime-local" wire:model="appointment_at" min="{{ now()->format('Y-m-d') . 'T09:00' }}" max="{{ now()->addWeek()->format('Y-m-d') . 'T17:00' }}" required /> --}}
                            {{--
                        </x-filament::input.wrapper> --}}
                        <x-filament::button type="submit" icon="heroicon-o-check-circle">
                            {{ __('Save') }}
                        </x-filament::button>
                    </div>

                    {{-- <div class="flex gap-6">
                        <x-filament::input.wrapper class="w-full">
                            <x-filament::input type="date" wire:model="appointment_date" min="{{ now()->format('Y-m-d') }}" max="{{ now()->addWeek()->format('Y-m-d') }}" required />
                        </x-filament::input.wrapper>

                        <x-filament::input.wrapper class="w-full">
                            <x-filament::input type="time" wire:model="appointment_time" min="09:00" max="17:00" required />
                        </x-filament::input.wrapper>

                        <x-filament::button type="submit" icon="heroicon-o-check-circle">
                            {{ __('Save') }}
                        </x-filament::button>
                    </div> --}}
                    @else
                    <p>Anda telah menjadwalkan wawancara pada
                        <strong>{{ \Carbon\Carbon::parse($registrant->meta['appointment_at'])->isoFormat('LLLL') }}</strong>,
                        jika ada perubahan silakan hubungi Petugas PSB melalui WhatsApp
                    </p>
                    @endif
                </x-filament-panels::form>
            </x-filament::section>
            <x-filament::section compact class="ring-primary-200 dark:ring-primary-800/60 bg-primary-50 dark:bg-primary-900/40 text-primary-700 dark:text-primary-400">
                Untuk konfirmasi pembayaran atau informasi lainnya, silakan chat melalui <a href="https://wa.me/6282329528670?text=Assalamu%27alaikum%20admin%20MASAYA%2C%20saya%20{{ $this->registrant->user->name }}%0A%0ASaya%20ingin%20konfirmasi%20pembayaran%2Fmenanyakan%20informasi%20tentang%20%20pendaftaran%20santri%20baru%20Madrasah%20Aliyah%20Ihsaniyya.%20%0A%0ATerimakasih" target="_blank" class="font-semibold underline decoration-dotted text-primary-500">WhatsApp ini</a>
            </x-filament::section>
            <x-filament::section icon="heroicon-o-exclamation-triangle">
                <x-slot name="heading">
                    Informasi & Pengumuman
                </x-slot>
                <ul class="space-y-4">
                    @if ($this->registrant->accepted_at || $this->announcements->count() > 0)
                    @if($this->registrant->accepted_at)
                    <li class="space-y-2 border-gray-200 border-s-2 dark:border-gray-800 ps-4">
                        <x-filament::section compact class="ring-success-300 dark:ring-success-800/60 bg-success-50 dark:bg-success-900/40 text-success-700 dark:text-success-400">
                            <strong>Selamat!</strong> Anda telah diterima sebagai santri di MA Ihsaniyya
                        </x-filament::section>
                    </li>
                    @if($remain > 0)
                    <li class="space-y-2 border-gray-200 border-s-2 dark:border-gray-800 ps-4">
                        <div>Anda memiliki tagihan yang belum dibayar sebesar
                            {{ Number::currency($remain, in: 'IDR', locale: 'id') }}</div>
                        <x-filament::section compact class="ring-danger-200 dark:ring-danger-800/60 bg-danger-50 dark:bg-danger-900/40 text-danger-700 dark:text-danger-400">
                            Silakan lakukan pembayaran ke Nomor Rekening: BRI <strong>2200 0100 0311 300</strong>
                            a.n. Madrasah Aliyah Ihsaniyya.
                        </x-filament::section>
                    </li>
                    @endif
                    @endif
                    @foreach ($this->announcements as $announcement)
                    <li class="space-y-2 border-gray-200 border-s-2 dark:border-gray-800 ps-4">
                        <div class="prose dark:prose-invert">{!! $announcement->content !!}</div>
                    </li>
                    @endforeach
                    @else
                    <li>Tidak ada informasi yang ditampilkan</li>
                    @endif
                </ul>
            </x-filament::section>
            <x-filament::section icon="heroicon-o-clock" class="overflow-y-auto">
                <x-slot name="heading">
                    Progress pendaftaran
                </x-slot>
                <x-slot name="description">
                    Ini merupakan progress pendaftaran Anda, diperbarui oleh Petugas PSB
                </x-slot>
                <ol class="flex flex-col flex-wrap gap-4 text-sm font-medium text-center text-gray-500 sm:items-center sm:flex-row xl:justify-between dark:text-gray-400 sm:text-base">
                    @foreach ($steps as $step)
                    <li @class(['text-blue-600 dark:text-blue-500'=> $step['value']])>
                        <span class="flex items-center">
                            @if ($step['value'])
                            <x-heroicon-s-check-circle class="block size-6" />
                            @else
                            <x-heroicon-o-check-circle class="block size-6" />
                            @endif
                            <div class="text-nowrap ms-2">{{ $step['label'] }}</div>
                        </span>
                    </li>
                    @if (!$loop->last)
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
                    @foreach ($completeness as $completion)
                    <a href="{{ $completion['url'] ?? 'javascript:;' }}" class="block group">
                        <x-filament::section compact class="transition-colors dark:group-hover:bg-gray-800 group-hover:bg-gray-50">
                            <div class="flex items-center justify-between gap-4">
                                <div @class([ 'grid rounded-full place-items-center size-8 text-sm font-semibold' , $completion['value'] ? 'bg-success-500/20 text-success-700 dark:text-success-400' : 'bg-gray-100 dark:bg-gray-800' , ])>{{ $loop->iteration }}</div>
                                <div class="grow">
                                    <div>{{ $completion['label'] }}</div>
                                    @if (isset($completion['description']))
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $completion['description'] }}</div>
                                    @endif
                                </div>
                                @if ($completion['value'])
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