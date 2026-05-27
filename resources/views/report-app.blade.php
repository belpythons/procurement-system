<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Report — {{ config('app.name', 'Procurement System') }}</title>

    <!-- Fonts: Figtree (Design System primary font) -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Bootstrap Icons for Sidebar Alignment -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    @inertiaHead
    @vite(['resources/css/app.css', 'resources/js/app-inertia.js'])
</head>
<body class="font-sans antialiased">
    @inertia
</body>
</html>
