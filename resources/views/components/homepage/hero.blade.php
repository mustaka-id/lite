<section>
    <div class="max-w-screen-xl px-4 py-16 mx-auto text-center sm:py-28 lg:px-6">
        <a href="{{ route('filament.admission.auth.register') }}" class="inline-flex items-center justify-between px-1 py-1 pr-4 text-sm text-gray-700 bg-gray-100 rounded-full mb-7 dark:bg-gray-800 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700" role="alert">
            <span class="text-xs bg-primary-600 rounded-full text-white px-4 py-1.5 mr-3">Dibuka</span> <span class="text-sm font-medium">Pendaftaran Santri Baru</span>
            <x-heroicon-o-chevron-right class="w-5 h-5 ml-2" />
        </a>
        <h1 class="mb-4 text-5xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-white">Facing the Future without <br />Breaking Tradition</h1>
        <p class="mb-8 font-normal text-gray-500 lg:text-xl xl:px-16 dark:text-gray-400">Menjadi lembaga pendidikan menengah keagamaan terdepan dalam menanamkan nilai-nilai kebaikan (ihsan) sesuai dengan perkembangan zaman berlandaskan paham Ahlussunnah wal Jama’ah an Nahdliyyah</p>
        <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">
            <a href="{{ route('filament.admission.auth.register') }}" class="inline-flex items-center justify-center px-5 py-3 text-base font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900">
                Daftar Sekarang
                <x-heroicon-o-arrow-right class="w-5 h-5 ml-2 -mr-1" />
            </a>
            <a href="{{ asset('brosur-psb-25-26.pdf') }}" target="_blank" class="inline-flex items-center justify-center px-5 py-3 text-base font-medium text-center text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                <x-heroicon-o-arrow-down-tray class="w-5 h-5 mr-2 -ml-1" />
                Unduh Brosur
            </a>
            {{-- <a href="#" class="inline-flex items-center justify-center px-5 py-3 text-base font-medium text-center text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                <x-heroicon-o-video-camera class="w-5 h-5 mr-2 -ml-1" />
                Lihat Video
            </a> --}}
        </div>
    </div>
</section>