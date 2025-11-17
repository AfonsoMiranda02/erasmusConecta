<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'ErasmusConecta' }} - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white min-h-screen">
    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-blue-50 to-sky-100">
        <!-- Header -->
        <div class="w-full max-w-md mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">ErasmusConecta</h1>
            <p class="text-sm text-gray-600">Plataforma de integração IPVC</p>
        </div>

        <!-- Card Principal -->
        <div class="w-full max-w-md">
            <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-200">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>

            <!-- Footer -->
            <div class="mt-6 text-center text-sm text-gray-600">
                <p>&copy; {{ date('Y') }} ErasmusConecta - IPVC</p>
            </div>
        </div>
    </div>
</body>
</html>

