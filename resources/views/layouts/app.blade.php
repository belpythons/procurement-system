<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Permit to Work System')</title>

    <!-- Tailwind CSS (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Flowbite CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Phosphor Icons (Modern Alternative to FontAwesome) -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        .sidebar-transition { transition: transform 0.3s ease-in-out; }
    </style>
</head>
<body class="antialiased text-gray-900 bg-gray-50">

    <!-- Header / Topbar Component -->
    <x-header />

    <!-- Sidebar Component -->
    <x-sidebar />

    <!-- Main Content Area -->
    <main class="p-4 sm:ml-64 pt-20 min-h-screen">
        
        <!-- Flash Messages -->
        @if (session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                <span class="font-medium">Berhasil!</span> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <span class="font-medium">Gagal!</span> {{ session('error') }}
            </div>
        @endif

        <!-- Page Content -->
        @yield('content')

    </main>

    <!-- Flowbite JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    
    <!-- Custom Page Scripts -->
    @stack('scripts')

</body>
</html>
