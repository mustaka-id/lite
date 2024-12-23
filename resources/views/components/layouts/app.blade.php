<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('/favicon.ico') }}">
    <meta name="description" content="">
    <meta property="og:url" content="{{ config('app.url') }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ config('app.name') }} - Facing the Future without Breaking Tradition">
    <meta property="og:description" content="{{ $description = 'Menjadi lembaga pendidikan menengah keagamaan terdepan dalam menanamkan nilai-nilai kebaikan (ihsan) sesuai dengan perkembangan zaman berlandaskan paham Ahlussunnah wal Jama’ah an Nahdliyyah' }}">
    <meta property="og:image" content="{{ asset('/og-image.jpg') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="ma.ihsaniyya.sch.id">
    <meta property="twitter:url" content="{{ config('app.url') }}">
    <meta name="twitter:title" content="{{ config('app.name') }} - Facing the Future without Breaking Tradition">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ asset('/og-image.jpg') }}">
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