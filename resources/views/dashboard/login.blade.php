<!DOCTYPE html>
<html lang="pt-BR" x-data="{ openMobile: false }"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME', 'StoreX') }}</title>
    @vite('resources/css/app.css')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 dark:bg-gray-950 text-slate-900 dark:text-gray-200 antialiased transition-colors duration-300 min-h-screen">
    <div class="min-h-screen flex items-center justify-center px-4 bg-gradient-to-br from-[#003378] to-[#0158cd] dark:from-gray-950 dark:to-slate-900">
        <div class="w-full max-w-md">
            <div class="bg-white dark:bg-gray-900 border border-transparent dark:border-gray-800 shadow-2xl rounded-[30px] p-8 transition-all">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-[#004aad] dark:text-white uppercase tracking-tight">StoreX Dashboard</h1>
                </div>
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2 ml-1">E-mail</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="seu@email.com" class="w-full px-5 py-3 bg-slate-100 dark:bg-gray-950 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-gray-200 rounded-full focus:ring-2 focus:ring-[#0158cd] focus:border-transparent outline-none transition-all placeholder:text-slate-400">
                        @error('email')
                            <p class="text-red-500 dark:text-red-400 text-xs mt-2 ml-4">{{ $message }}</p>
                        @enderror
                    </div>  
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2 ml-1">Senha</label>
                        <input type="password" name="password" required placeholder="••••••••" class="w-full px-5 py-3 bg-slate-100 dark:bg-gray-950 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-gray-200 rounded-full focus:ring-2 focus:ring-[#0158cd] focus:border-transparent outline-none transition-all">
                        @error('password')
                            <p class="text-red-500 dark:text-red-400 text-xs mt-2 ml-4">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full bg-[#004aad] hover:bg-[#0158cd] dark:bg-white dark:text-[#004aad] dark:hover:bg-gray-200 text-white font-bold py-3 rounded-full shadow-lg transform active:scale-[0.98] transition-all duration-200 uppercase text-sm tracking-wider">Entrar</button>
                </form>
                <div class="mt-6 text-center">
                    <a href="{{ route('password.request') }}" class="text-xs text-slate-400 hover:text-[#004aad] dark:hover:text-white transition-colors">Esqueceu sua senha?</a>
                </div>
            </div>
        </div>
    </div>
    @if ($errors->has('account'))
        <div 
            x-data="{ open: true }"
            x-show="open"
            x-transition
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4"
        >
            <div @click.away="open = false" class="w-full max-w-md bg-white dark:bg-gray-900 rounded-3xl shadow-2xl p-8 border border-slate-200 dark:border-gray-800">
                <div class="text-center">
                    <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-500/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Conta pendente</h2>
                    <p class="mt-3 text-sm text-slate-600 dark:text-gray-400 leading-relaxed">Sua conta foi criada com sucesso, mas ainda precisa da confirmação do pagamento para ser ativada.</p>
                    <div class="mt-8 flex flex-col gap-3">
                        <a href="{{ route('payment') }}" class="w-full bg-[#004aad] hover:bg-[#0158cd] text-white font-semibold py-3 rounded-full transition-all">Finalizar pagamento</a>
                        <button  @click="open = false" class="w-full border border-slate-300 dark:border-gray-700 text-slate-700 dark:text-gray-300 font-semibold py-3 rounded-full hover:bg-slate-100 dark:hover:bg-gray-800 transition-all">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</body>
</html>