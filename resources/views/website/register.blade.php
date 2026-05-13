<!DOCTYPE html>
<html lang="pt-BR" x-data="{ openMobile: false }"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro | {{ env('APP_NAME', 'StoreX') }}</title>
    @vite('resources/css/app.css')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-slate-50 dark:bg-gray-950 text-slate-900 dark:text-gray-200 antialiased transition-colors duration-300 min-h-screen">
    <header class="w-full flex justify-between items-center px-6 md:px-12 py-8">
        <div>
            <a href="/" class="text-2xl font-black text-[#004aad] dark:text-white uppercase tracking-tighter">
                <img src="{{ asset('img/1.png') }}" alt="StoreX" class="w-32 md:w-50 block dark:hidden">
                <img src="{{ asset('img/2.png') }}" alt="StoreX" class="w-32 md:w-50 hidden dark:flex">
            </a>
        </div>
        <nav class="hidden md:flex items-center gap-6">
            <a href="/" class="text-sm font-semibold text-slate-600 dark:text-gray-400 hover:text-[#0158cd]">Home</a>
            <a href="{{ route('dashboard.home') }}" class="px-10 py-3 rounded-2xl bg-[#004aad] hover:bg-[#0158cd] text-white font-semibold text-sm transition-all shadow-sm">Login</a>
        </nav>
    </header>
    <main class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-10">
                <h1 class="text-3xl md:text-4xl font-bold text-[#004aad] dark:text-white">Crie sua conta</h1>
                <p class="text-slate-600 dark:text-gray-400 mt-2">Preencha os dados abaixo para configurar seu catálogo digital.</p>
            </div>
            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="space-y-8" novalidate>
                @csrf
                <div class="bg-white dark:bg-gray-900 p-8 rounded-3xl shadow-sm border border-slate-200 dark:border-gray-800">
                    <div class="flex items-center gap-3 mb-6 border-b border-slate-100 dark:border-gray-800 pb-4">
                        <i class="fas fa-user text-[#004aad] text-xl"></i>
                        <h2 class="text-xl font-bold">Informações Pessoais</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold">Nome Completo *</label>
                            <input type="text" name="name" required value="{{ old('name') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-slate-50 dark:bg-gray-800 focus:ring-2 focus:ring-[#004aad] outline-none transition">
                            @error('name') 
                                <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p> 
                            @enderror
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold">CPF ou CNPJ</label>
                            <input type="text" name="document" oninput="formatDocument(this)" placeholder="000.000.000-00" value="{{ old('document') }}"  class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-slate-50 dark:bg-gray-800 focus:ring-2 focus:ring-[#004aad] outline-none transition">
                            <p class="text-[11px] text-slate-500 dark:text-gray-400">Apenas números.</p>
                            @error('document') 
                                <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p> 
                            @enderror
                        </div>
                        <div class="flex flex-col gap-2 md:col-span-2">
                            <label class="text-sm font-semibold">E-mail de Acesso *</label>
                            <input type="email" name="email" required value="{{ old('email') }}"  class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-slate-50 dark:bg-gray-800 focus:ring-2 focus:ring-[#004aad] outline-none transition">
                            @error('email') 
                                <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p> 
                            @enderror
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold">Senha *</label>
                            <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-slate-50 dark:bg-gray-800 focus:ring-2 focus:ring-[#004aad] outline-none transition">
                            @error('password') 
                                <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p> 
                            @enderror
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold">Confirmar Senha *</label>
                            <input type="password" name="password_confirmation" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-slate-50 dark:bg-gray-800 focus:ring-2 focus:ring-[#004aad] outline-none transition">
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-900 p-8 rounded-3xl shadow-sm border border-slate-200 dark:border-gray-800">
                    <div class="flex items-center gap-3 mb-6 border-b border-slate-100 dark:border-gray-800 pb-4">
                        <i class="fas fa-store text-[#004aad] text-xl"></i>
                        <h2 class="text-xl font-bold">Configurações da Loja</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold">Nome da Loja *</label>
                            <input type="text" name="store_name" required value="{{ old('store_name') }}"  placeholder="Ex: Doce Sabor" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-slate-50 dark:bg-gray-800 focus:ring-2 focus:ring-[#004aad] outline-none transition">
                            @error('store_name') 
                                <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p> 
                            @enderror
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold">Endereço da sua Loja (Subdomínio) *</label>
                            <div class="flex items-center px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-slate-50 dark:bg-gray-800 focus-within:ring-2 focus-within:ring-[#004aad] transition group">
                                <input type="text"  name="slug"  id="slug" required value="{{ old('slug') }}"   placeholder="minhaloja"  class="bg-transparent outline-none flex-1 text-sm font-medium text-slate-700 dark:text-gray-200" oninput="this.value = this.value.toLowerCase().replace(/[^a-z0-9-]/g, '')">
                                <span class="text-slate-400 dark:text-gray-500 text-sm font-semibold border-l border-slate-300 dark:border-gray-600 pl-3 ml-2">.storex.com.br</span>
                            </div>
                            <p class="text-[11px] text-slate-500 dark:text-gray-400">Apenas letras minúsculas, números e hifens.</p>
                            @error('slug') 
                                <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p> 
                            @enderror
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold">WhatsApp / Telefone *</label>
                            <input type="text" name="phone" oninput="formatPhone(this)" placeholder="(00) 00000-0000" required value="{{ old('phone') }}"  class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-slate-50 dark:bg-gray-800 focus:ring-2 focus:ring-[#004aad] outline-none transition">
                            <p class="text-[11px] text-slate-500 dark:text-gray-400">Apenas números com DDD.</p>
                            @error('phone') 
                                <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p> 
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="flex flex-col items-center gap-4">
                    <button type="submit" onclick="this.disabled = true; this.form.submit();" class="w-full md:w-[300px] py-4 rounded-2xl bg-[#004aad] hover:bg-[#0158cd] text-white font-bold text-lg transition-all shadow-lg active:scale-95">Criar conta</button>
                </div>
            </form>
        </div>
    </main>
    <footer class="py-10 text-center text-slate-400 text-sm bg-gradient-to-b from-[#003378] to-[#0158cd] dark:from-gray-900 dark:to-gray-900">&copy; 2026 StoreX - Todos os direitos reservados.</footer>
    <div class="fixed bottom-5 right-5 z-[60] flex flex-col gap-3">
        @error('error')
            <div class="message bg-white dark:bg-gray-900 border-l-4 border-red-500 shadow-2xl rounded-xl p-4 flex items-center min-w-[300px]">
                <div class="p-2 bg-red-100 dark:bg-red-500/20 rounded-full mr-3 text-red-600">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </div>
                <p class="text-sm font-bold text-slate-700 dark:text-gray-200">{{ $message }}</p>
            </div>
        @enderror
    </div>
    <script>
        function formatDocument(input){
            let value = input.value.replace(/\D/g, '');
            if(value.length > 11){
                if(value.length > 14) value = value.slice(0,14);
            }else{
                if(value.length > 11) value = value.slice(0,11);
            }
            input.value = value;
        }
        function formatPhone(input){
            let value = input.value.replace(/\D/g, '');
            if(value.length > 11) value = value.slice(0,11);
            input.value = value;
        }   
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