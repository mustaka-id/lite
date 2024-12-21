<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex flex-col justify-between min-h-screen bg-white dark:bg-gray-950">
    @include('components.navbar')
    <main class="grow">
        {{ $slot }}
    </main>
    @include('components.footer')
    <script>

    </script>
</body>

</html>