<footer class="p-4 md:p-8 lg:p-10 dark:bg-gray-900">
    <div class="max-w-screen-xl mx-auto text-center">
        <a href="/" class="flex justify-center mx-auto text-start">
            @include('components.logo.full')
        </a>
        <p class="my-6 text-gray-500 dark:text-gray-400">Jl. Bulus Tempel, Bulus I, Candibinangun, Pakem, Sleman, Daerah Istimewa Yogyakarta 55582</p>
        <ul class="flex flex-wrap items-center justify-center mb-6 space-x-4 text-gray-900 dark:text-white lg:space-x-8">
            <li>
                <a href="mailto:ma.ihsaniyya@gmail.com" target="_blank" class="hover:underline ">Email</a>
            </li>
            <li>
                <a href="https://wa.me/62823329528670" target="_blank" class="hover:underline">Telepon</a>
            </li>
            <li class="text-gray-400 dark:text-gray-600"> | </li>
            <li>
                <a href="{{ route('filament.admission.auth.login') }}" class="hover:underline">Login</a>
            </li>
            <li>
                <a href="{{ route('filament.admission.auth.register') }}" class="hover:underline">Daftar</a>
            </li>
        </ul>
        <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2025 <a href="#" class="hover:underline">{{ config('app.name') }}</a>. All Rights Reserved.</span>
    </div>
</footer>