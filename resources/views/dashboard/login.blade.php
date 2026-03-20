    <x-dashboard_layout>
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
                    <a href="/" class="text-xs text-slate-400 hover:text-[#004aad] dark:hover:text-white transition-colors">Esqueceu sua senha?</a>
                </div>
            </div>
        </div>
    </div>
    </x-dashboard_layout>