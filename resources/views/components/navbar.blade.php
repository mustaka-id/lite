<nav class="border-b border-gray-200 dark:border-gray-800">
    <div class="flex flex-wrap items-center justify-between max-w-screen-xl p-4 mx-auto space-x-1">
        <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
            @include('components.logo.full')
        </a>
        <div class="grow"></div>
        <div class="md:pr-4">
            <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
                <span id="theme-toggle-dark-icon" class="hidden">
                    <x-heroicon-o-sun class="w-5 h-5" />
                </span>
                <span id="theme-toggle-light-icon" class="hidden">
                    <x-heroicon-o-moon class="w-5 h-5" />
                </span>
            </button>
        </div>
        <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center justify-center w-10 h-10 p-2 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-default" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <x-heroicon-o-bars-2 class="w-5 h-5" />
        </button>
        <div class="hidden w-full md:block md:w-auto" id="navbar-default">
            <ul class="flex flex-col p-4 mt-4 font-medium border border-gray-100 rounded-lg md:p-0 bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-950 dark:border-gray-700">
                @auth
                <li>
                    <a href="{{ route('filament.admission.pages.profile') }}" class="block px-3 py-2 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Akun Saya</a>
                </li>
                <li>
                    <form action="{{ route('filament.admission.auth.logout') }}" method="post"> @csrf
                        <button type="submit" class="block px-3 py-2 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Logout</button>
                    </form>
                </li>
                @else
                <li>
                    <a href="{{ route('filament.admission.auth.login') }}" class="block px-3 py-2 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Login</a>
                </li>
                <li>
                    <a href="{{ route('filament.admission.auth.register') }}" class="block px-3 py-2 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Daftar</a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>