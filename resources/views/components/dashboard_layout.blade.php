<!DOCTYPE html>
<html lang="pt-BR"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-50 dark:bg-gray-950 text-slate-900 dark:text-gray-200 antialiased transition-colors duration-300">
    <main>
        {{ $slot }}
    </main>
    @vite('resources/js/app.js')
</body>
</html>