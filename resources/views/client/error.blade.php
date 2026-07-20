<!DOCTYPE html>
<html lang="pt-BR" x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ops! Algo deu errado</title>
    <x-theme-script />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-slate-50 dark:bg-gray-950 text-slate-900 dark:text-gray-200 antialiased min-h-screen flex items-center justify-center p-4">
    <div 
        x-show="show" 
        x-transition:enter="transition ease-out duration-700"
        x-transition:enter-start="opacity-0 transform scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
        class="max-w-md w-full bg-white dark:bg-gray-900 rounded-[2.5rem] p-8 shadow-2xl border border-slate-200 dark:border-gray-800 text-center"
    >
        <div class="mb-6">
            <div class="w-20 h-20 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto"><i class="fas fa-store-slash text-3xl text-red-500"></i></div>
        </div>
        <h1 class="text-2xl font-black text-slate-900 dark:text-white mb-3">Ops! Não conseguimos carregar 😕
        </h1>
        <p class="text-slate-600 dark:text-gray-400 mb-6 leading-relaxed">{{ $message ?? 'Tivemos um problema ao exibir este conteúdo do catálogo. Pode ser algo temporário ou o item não está mais disponível.' }}</p>
        <div class="bg-slate-50 dark:bg-gray-800/50 rounded-2xl p-4 mb-6 text-sm text-slate-600 dark:text-gray-300">💡 Tente voltar e explorar outros produtos ou serviços disponíveis.</div>
        <div class="space-y-3">
            <a href="{{ route('client.home', ['tenant' => request()->route('tenant')]) }}" class="block w-full py-4 rounded-2xl bg-[#004aad] hover:bg-[#0158cd] text-white font-bold text-lg transition-all shadow-lg active:scale-95">Voltar ao Início <i class="fas fa-store ml-2 text-sm"></i></a>
        </div>
        <p class="text-xs text-slate-400 mt-6">Se o problema continuar, tente novamente em instantes.</p>
    </div>
    <div class="fixed top-0 left-0 w-full h-full -z-10 overflow-hidden opacity-10 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-red-500 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-[#004aad] rounded-full blur-[120px]"></div>
    </div>
</body>
</html>
