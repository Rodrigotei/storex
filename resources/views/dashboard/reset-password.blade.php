<!DOCTYPE html>
<html lang="pt-BR" x-data="{ openMobile: false }"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'StoreX') }}</title>
    <x-theme-script />
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-50 dark:bg-gray-950 text-slate-900 dark:text-gray-200 antialiased transition-colors duration-300 min-h-screen">
    <div class="min-h-screen flex items-center justify-center px-4 bg-gradient-to-br from-[#003378] to-[#0158cd] dark:from-gray-950 dark:to-slate-900">
        <div class="w-full max-w-md">
            <div class="bg-white dark:bg-gray-900 border border-transparent dark:border-gray-800 shadow-2xl rounded-[30px] p-8 transition-all">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-[#004aad] dark:text-white uppercase tracking-tight">Redefinir Senha</h1>
                </div>
                <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="email" value="{{ request()->query('email') }}">
                    <input type="hidden" name="token" value="{{ request('token') }}">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2 ml-1">Nova Senha</label>
                        <input type="password" name="password" required autofocus placeholder="Digite a nova senha" class="w-full px-5 py-3 bg-slate-100 dark:bg-gray-950 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-gray-200 rounded-full focus:ring-2 focus:ring-[#0158cd] focus:border-transparent outline-none transition-all placeholder:text-slate-400">
                        @error('password')
                            <p class="text-red-500 dark:text-red-400 text-xs mt-2 ml-4">{{ $message }}</p>
                        @enderror
                    </div>  
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2 ml-1">Confirmar Nova Senha</label>
                        <input type="password" name="password_confirmation" required autofocus placeholder="Digite a nova senha" class="w-full px-5 py-3 bg-slate-100 dark:bg-gray-950 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-gray-200 rounded-full focus:ring-2 focus:ring-[#0158cd] focus:border-transparent outline-none transition-all placeholder:text-slate-400">
                        @error('password_confirmation')
                            <p class="text-red-500 dark:text-red-400 text-xs mt-2 ml-4">{{ $message }}</p>
                        @enderror
                    </div> 
                    @error('email')
                        <p class="text-red-500 dark:text-red-400 text-xs mt-2 ml-4">{{ $message }}</p>
                    @enderror
                    @error('token')
                        <p class="text-red-500 dark:text-red-400 text-xs mt-2 ml-4">{{ $message }}</p>
                    @enderror
                    <button type="submit" class="w-full bg-[#004aad] hover:bg-[#0158cd] dark:bg-white dark:text-[#004aad] dark:hover:bg-gray-200 text-white font-bold py-3 rounded-full shadow-lg transform active:scale-[0.98] transition-all duration-200 uppercase text-sm tracking-wider">Redefinir Senha</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
