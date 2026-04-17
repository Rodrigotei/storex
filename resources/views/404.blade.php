<!DOCTYPE html>
<html lang="pt-BR" x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página não encontrada | {{ env('APP_NAME', 'StoreX') }}</title>
    @vite('resources/css/app.css')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-slate-50 dark:bg-gray-950 text-slate-900 dark:text-gray-200 antialiased min-h-screen flex items-center justify-center p-4">
    <div 
        x-show="show" 
        x-transition:enter="transition ease-out duration-700"
        x-transition:enter-start="opacity-0 transform scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
        class="max-w-lg w-full bg-white dark:bg-gray-900 rounded-[2.5rem] p-8 md:p-12 shadow-2xl border border-slate-200 dark:border-gray-800 text-center"
    >
        <div class="mb-8 relative inline-block">
            <div class="w-24 h-24 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto"><i class="fas fa-search text-4xl text-red-500"></i></div>
            <div class="absolute -top-2 -right-2 w-6 h-6 bg-[#004aad] rounded-full"></div>
        </div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-4 tracking-tight">{{ $message ?? 'Opa! Página não encontrada.' }}</h1>
        <p class="text-slate-600 dark:text-gray-400 text-lg mb-6 leading-relaxed">Parece que você tentou acessar uma página que não existe ou foi removida. Mas relaxa, a gente te ajuda a voltar pro caminho certo 😉
        <div class="bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-500 p-4 mb-8 text-left">
            <div class="flex items-center gap-3">
                <i class="fas fa-lightbulb text-amber-500 text-xl"></i>
                <p class="text-amber-700 dark:text-amber-400 font-bold leading-tight text-sm">Dica: Verifique o link ou volte para a página inicial.</p>
            </div>
        </div>
        <div class="space-y-4">
            <a href="javascript:history.back()" class="block w-full py-4 rounded-2xl bg-[#004aad] hover:bg-[#0158cd] text-white font-bold text-lg transition-all shadow-lg hover:shadow-[#004aad]/30 active:scale-95">Voltar para página anterior</a>
            <p class="text-xs text-slate-400"><i class="fas fa-life-ring mr-1"></i> Se o problema persistir, entre em contato com o suporte.</p>
        </div>
    </div>
    <div class="fixed top-0 left-0 w-full h-full -z-10 overflow-hidden opacity-10 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-red-500 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-[#004aad] rounded-full blur-[120px]"></div>
    </div>
</body>
</html>