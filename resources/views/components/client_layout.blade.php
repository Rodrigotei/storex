<!DOCTYPE html>
<html lang="pt-BR" x-data="{ openMobile: false }"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('img/icon-white.png') }}">
    <title>{{ env('APP_NAME', 'StoreX') }}</title>
    @vite('resources/css/app.css')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 dark:bg-gray-950 text-slate-900 dark:text-gray-200 antialiased transition-colors duration-300 min-h-screen">
        <x-navbar_client />
        @isset($header)
            <header class="bg-white dark:bg-gray-900 border-b border-slate-200 dark:border-gray-800 shadow-sm">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h2 class="text-xl font-bold text-[#004aad] dark:text-white leading-tight">
                        {{ $header }}
                    </h2>
                </div>
            </header>
        @endisset    
    <main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        {{ $slot }}

        <x-alert />
        
    </main>
    @vite('resources/js/app.js')
</body>
</html>