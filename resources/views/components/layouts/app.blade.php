<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $metaDescription ?? 'Form Builder App' }}">
    <meta name="theme-color" content="#0f172a">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <title>{{ config('app.name', $title ?? 'Page Title') }}</title>
    @filamentStyles
    @vite(['resources/css/app.css'])
</head>

<body class="bg-neutral-50 dark:bg-neutral-900 dark:text-neutral-100">
    <main>
        {{ $slot }}
    </main>
    @vite(['resources/js/app.js'])
    @filamentScripts
</body>

</html>