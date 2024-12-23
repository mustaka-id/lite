<section>
    <div class="max-w-screen-xl px-4 py-8 mx-auto sm:py-16 lg:px-6">
        <x-filament::section class="xl:p-6">
            <h2 class="mb-4 text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">Ayo bergabung bersama kami..</h2>
            <p class="mb-8 text-gray-500 sm:text-xl dark:text-gray-400">Nikmati pembelajaran yang menyenangkan, lingkungan yang kondusif, dan bimbingan dari guru-guru yang berpengalaman. Daftar sekarang di MA Ihsaniyya dan temukan pengalaman belajar yang menyenangkan!</p>
            <div class="flex flex-col space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4">
                <a href="{{ route('filament.admission.auth.register') }}" class="inline-flex items-center justify-center px-5 py-3 text-base font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900">
                    Daftar Sekarang
                    <x-heroicon-o-arrow-right class="w-5 h-5 ml-2 -mr-1" />
                </a>
                <a href="{{ asset('brosur-psb-25-26.pdf') }}" target="_blank" class="inline-flex items-center justify-center px-5 py-3 text-base font-medium text-center text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                    <x-heroicon-o-arrow-down-tray class="w-5 h-5 mr-2 -ml-1" />
                    Unduh Brosur
                </a>
            </div>
        </x-filament::section>
    </div>
</section>