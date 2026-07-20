<!DOCTYPE html>
<html lang="pt-BR" x-data="{ openMobile: false }"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'StoreX') }}</title>
    <x-theme-script />
    @vite('resources/css/app.css')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 dark:bg-gray-950 text-slate-900 dark:text-gray-200 antialiased transition-colors duration-300 min-h-screen">
    <div class="min-h-screen flex items-center justify-center px-4 bg-gradient-to-br from-[#003378] to-[#0158cd] dark:from-gray-950 dark:to-slate-900">
        <div class="w-full max-w-md">
            <div class="bg-white dark:bg-gray-900 border border-transparent dark:border-gray-800 shadow-2xl rounded-[30px] p-8 transition-all">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-[#004aad] dark:text-white uppercase tracking-tight">Recuperar Senha</h1>
                </div>
                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2 ml-1">E-mail</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="seu@email.com" class="w-full px-5 py-3 bg-slate-100 dark:bg-gray-950 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-gray-200 rounded-full focus:ring-2 focus:ring-[#0158cd] focus:border-transparent outline-none transition-all placeholder:text-slate-400">
                    </div>  
                    <button type="submit" class="w-full bg-[#004aad] hover:bg-[#0158cd] dark:bg-white dark:text-[#004aad] dark:hover:bg-gray-200 text-white font-bold py-3 rounded-full shadow-lg transform active:scale-[0.98] transition-all duration-200 uppercase text-sm tracking-wider">Recuperar Senha</button>
                </form>
                <div class="mt-6 text-center">
                    <a href="{{ route('dashboard.home') }}" class="text-xs text-slate-400 hover:text-[#004aad] dark:hover:text-white transition-colors">Lembrou sua senha?</a>
                </div>
            </div>
        </div>
    </div>
    @if (session('status') || $errors->has('email'))
        <div class="fixed bottom-5 right-5 z-[60] flex flex-col gap-3">
            <div class="message bg-white dark:bg-gray-900 border-l-4 border-green-500 shadow-2xl rounded-xl p-4 flex items-center min-w-[300px] animate-bounce-subtle">
                <div class="p-2 bg-green-100 dark:bg-green-500/20 rounded-full mr-3 text-green-600">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                </div>
                <p class="text-sm font-bold text-slate-700 dark:text-gray-200">E-mail de recuperação enviado com sucesso!</p>
            </div>
        </div>
    @endif
    <script>
        setTimeout(() => {
            document.querySelectorAll('.message').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateX(20px)';
                el.style.transition = 'all 0.5s ease';
                setTimeout(() => el.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>
