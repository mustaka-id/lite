@php
$total_bills_amount = $this->registrant->bills->pluck('items.*.amount')->flatten()->sum();
$total_payments_amount = $this->registrant->payments->sum('amount');
$remain = $total_bills_amount - $total_payments_amount;

$completeness = $this->getRegistrationFormSection();
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
            <x-filament::section icon="heroicon-o-pencil-square">
                <x-slot name="heading">
                    Proses Pendaftaran
                </x-slot>
                <div class="space-y-4">
                    <x-filament::section compact class="transition-colors dark:group-hover:bg-gray-800 group-hover:bg-gray-50" collapsible collapsed>
                        <x-slot name="heading">
                            <div class="flex items-center justify-between gap-4">
                                <div @class([ 'grid rounded-full place-items-center size-8 text-sm font-semibold' , $this->registrant->registered_at ? 'bg-success-500/20 text-success-700 dark:text-success-400' : 'bg-gray-100 dark:bg-gray-800' , ])>1</div>
                                <div class="grow">Formulir pendaftaran</div>
                            </div>
                        </x-slot>
                        <x-slot name="headerEnd">
                            <x-filament::badge :color="$completeness_precentage == 100 ? 'success' : 'warning'">
                                @if (isset($this->registrant->registered_at))
                                Terkirim
                                @else
                                {{ $completeness_precentage }}%
                                @endif
                            </x-filament::badge>
                        </x-slot>
                        <div class="space-y-2">
                            @foreach($this->getRegistrationFormSection() as $section)
                            <a href="{{ $section['url'] ?? 'javascript:;' }}" class="block group">
                                <div class="px-2 py-2 transition-colors rounded-lg dark:group-hover:bg-gray-800 group-hover:bg-gray-50">
                                    <div class="flex items-center justify-between gap-4">
                                        <div @class([ 'grid rounded-full place-items-center size-8 text-sm font-semibold' , $section['value'] ? 'bg-success-500/20 text-success-700 dark:text-success-400' : 'bg-gray-100 dark:bg-gray-800' , ])>
                                            @if($section['value'])
                                            <x-heroicon-o-check-circle class="size-6 text-success-500" />
                                            @else
                                            <x-heroicon-o-x-circle class="size-6 text-danger-500" />
                                            @endif
                                        </div>
                                        <div class="grow">
                                            <div>{{ $section['label'] }}</div>
                                            @if (isset($section['description']))
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $section['description'] }}</div>
                                            @endif
                                        </div>
                                        @isset ($section['url'])
                                        <x-heroicon-o-arrow-right class="size-5" />
                                        @endisset
                                    </div>
                                    @if(isset($section['children']))
                                    <ul class="flex flex-col space-y-2 list-disc ms-12">
                                        @foreach($section['children'] as $child)
                                        <li class="ms-4">
                                            <a class="flex items-center justify-between gap-4" href="{{ $child['url'] ?? 'javascript:;' }}">
                                                <div>{{ $child['label'] }}</div>
                                                @isset ($child['url'])
                                                <x-heroicon-o-arrow-right class="size-5" />
                                                @endisset
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                    @endif
                                </div>
                            </a>
                            @endforeach
                            @if(!$this->registrant->registered_at)
                            <div class="p-2 mt-2 space-y-2">
                                <hr>
                                <p>Apabila Anda sudah yakin dengan formulir pendaftaran Anda, silakan klik tombol "Kirim Formulir" di bawah ini</p>
                                <x-filament::button wire:click="finalizeRegistration" icon="heroicon-o-paper-airplane" :disabled="$completeness_precentage < 100 || $this->registrant->registered_at">
                                    Kirim Formulir
                                </x-filament::button>
                            </div>
                            @endif
                        </div>
                    </x-filament::section>
                    <x-filament::section compact class="transition-colors dark:group-hover:bg-gray-800 group-hover:bg-gray-50" collapsible :collapsed="!$appointed">
                        <x-slot name="heading">
                            <div class="flex items-center justify-between gap-4">
                                <div @class([ 'grid rounded-full place-items-center size-8 text-sm font-semibold' , $appointed ? 'bg-success-500/20 text-success-700 dark:text-success-400' : 'bg-gray-100 dark:bg-gray-800' , ])>2</div>
                                <div class="grow">Jadwal Wawancara</div>
                            </div>
                        </x-slot>
                        @if (!$appointed)
                        <x-slot name="headerEnd">
                            <x-filament::badge color="danger" icon="heroicon-o-x-circle">
                                Belum diatur
                            </x-filament::badge>
                        </x-slot>
                        @endif
                        @if($this->registrant->registered_at)
                        <x-filament-panels::form id="form" :wire:key="$this->getId() . '.forms.' . $this->getFormStatePath()" wire:submit="saveAppointment">
                            @if (!$appointed)
                            <div class="flex gap-6">
                                <div class="w-full">
                                    {{ $this->form }}
                                </div>
                                <x-filament::button type="submit" icon="heroicon-o-check-circle">
                                    {{ __('Save') }}
                                </x-filament::button>
                            </div>
                            @else
                            <p>Anda telah menjadwalkan wawancara pada
                                <strong>{{ \Carbon\Carbon::parse($registrant->meta['appointment_at'])->isoFormat('LLLL') }}</strong>,
                                jika ada perubahan silakan hubungi Petugas PSB melalui WhatsApp
                            </p>
                            @endif
                        </x-filament-panels::form>
                        @else
                        <p class="text-red-600">Mohon untuk selesaikan dahulu tahap pengisian formulir pendaftaran</p>
                        @endif
                    </x-filament::section>
                    <x-filament::section compact class="transition-colors dark:group-hover:bg-gray-800 group-hover:bg-gray-50" collapsible collapsed>
                        @php($accepted = isset($this->registrant->accepted_at))
                        <x-slot name="heading">
                            <div class="flex items-center justify-between gap-4">
                                <div @class([ 'grid rounded-full place-items-center size-8 text-sm font-semibold' , $accepted ? 'bg-success-500/20 text-success-700 dark:text-success-400' : 'bg-gray-100 dark:bg-gray-800' , ])>3</div>
                                <div class="grow">Hasil Wawancara</div>
                            </div>
                        </x-slot>
                        @if ($accepted)
                        <x-slot name="headerEnd">
                            <x-filament::badge color="danger" icon="heroicon-o-x-circle">
                                Diterima
                            </x-filament::badge>
                        </x-slot>
                        @endif
                        @if(!$accepted)
                        <div class="flex items-center gap-4">
                            <x-heroicon-o-check-badge class="size-6 text-success-500" />
                            <div>
                                <h5 class="text-lg font-bold">Selamat, {{ $this->user->name }}!</h5>
                                <p>Anda telah diterima sebagai santri Madrasah Aliyah Ihsaniyya Yogyakarta per {{ $this->registrant->accepted_at?->isoFormat('LLLL') }}</p>
                            </div>
                        </div>
                        @else
                        <p>Belum ada pengumuman, pastikan Anda telah melakukan sesi wawancara dengan petugas PSB</p>
                        @endif
                    </x-filament::section>
                    <x-filament::section compact class="transition-colors dark:group-hover:bg-gray-800 group-hover:bg-gray-50" collapsible collapsed>
                        @php($paid = $this->registrant->payments->count())
                        <x-slot name="heading">
                            <div class="flex items-center justify-between gap-4">
                                <div @class([ 'grid rounded-full place-items-center size-8 text-sm font-semibold' , $this->registrant->paid_off_at ? 'bg-success-500/20 text-success-700 dark:text-success-400' : ($paid ? 'bg-warning-500/20 text-warning-700 dark:text-warning-400' : 'bg-gray-100 dark:bg-gray-800' ) , ])>4</div>
                                <div class="grow">Pembayaran</div>
                            </div>
                        </x-slot>
                        @if($remain > 0)
                        <div class="space-y-2">
                            <div>Anda memiliki tagihan yang belum dibayar sebesar {{ Number::currency($remain, in: 'IDR', locale: 'id') }}</div>
                            <x-filament::section compact class="ring-danger-200 dark:ring-danger-800/60 bg-danger-50 dark:bg-danger-900/40 text-danger-700 dark:text-danger-400">
                                Silakan lakukan pembayaran ke Nomor Rekening: BRI <strong>2200 0100 0314 308</strong>
                                a.n. Nurul Ihsan YK.
                            </x-filament::section>
                        </div>
                        @else
                        <div>Terima kasih telah menyelesaikan tahap pembayaran</div>
                        @endif
                    </x-filament::section>
                </div>
            </x-filament::section>
            <x-filament::section icon="heroicon-o-exclamation-triangle">
                <x-slot name="heading">
                    Pengumuman
                </x-slot>
                <ul class="space-y-4">
                    @forelse ($this->announcements as $announcement)
                    <li class="space-y-2 border-gray-200 border-s-2 dark:border-gray-800 ps-4">
                        <div class="prose dark:prose-invert">{!! $announcement->content !!}</div>
                    </li>
                    @empty
                    <li>Tidak ada informasi yang ditampilkan</li>
                    @endforelse
                </ul>
            </x-filament::section>
        </div>
        <div class="space-y-6">
            <x-filament::section class="ring-primary-200 dark:ring-primary-800/60 bg-primary-50 dark:bg-primary-900/40 text-primary-700 dark:text-primary-400" icon="heroicon-o-information-circle">
                <x-slot name="heading">
                    Informasi
                </x-slot>
                Untuk konfirmasi pembayaran atau informasi lainnya, silakan chat melalui <a href="https://wa.me/6282329528670?text=Assalamu%27alaikum%20admin%20MASAYA%2C%20saya%20{{ $this->registrant->user->name }}%0A%0ASaya%20ingin%20konfirmasi%20pembayaran%2Fmenanyakan%20informasi%20tentang%20%20pendaftaran%20santri%20baru%20Madrasah%20Aliyah%20Ihsaniyya.%20%0A%0ATerimakasih" target="_blank" class="font-semibold underline decoration-dotted text-primary-500">WhatsApp ini</a>
            </x-filament::section>
            <x-filament::section icon="heroicon-o-clock" class="overflow-y-auto">
                <x-slot name="heading">
                    Status pendaftaran
                </x-slot>
                <ol class="space-y-4 text-sm font-medium sm:items-center sm:flex-row xl:justify-between sm:text-base">
                    @foreach ($steps as $step)
                    <li>
                        <span class="flex items-start space-x-3">
                            @if ($step['value'])
                            <x-heroicon-s-check-circle class="block text-blue-500 size-6" />
                            @else
                            <x-heroicon-o-check-circle class="block size-6" />
                            @endif
                            <div>
                                <div class="text-nowrap">{{ $step['label'] }}</div>
                                @isset($step['value'])
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $step['value']?->isoFormat('LLL') }}</div>
                                @endisset
                            </div>
                        </span>
                    </li>
                    @endforeach
                </ol>
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>