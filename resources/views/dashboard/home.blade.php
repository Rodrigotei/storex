<x-dashboard_layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 dark:text-white tracking-tight">Painel de Controle</h2>
                <p class="text-sm text-slate-500 dark:text-gray-400">Bem-vindo de volta, {{ auth()->user()->name }}!</p>
            </div>
            <div class="text-sm font-medium text-slate-400 bg-white dark:bg-gray-900 px-4 py-2 rounded-full border border-slate-200 dark:border-gray-800">{{ now()->format('d de M, Y') }}</div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
        
        <div class="bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-800 p-6 rounded-[30px] shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-100 dark:bg-blue-500/10 rounded-2xl text-[#004aad] dark:text-blue-400 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
            </div>
            <h3 class="text-slate-500 dark:text-gray-400 text-sm font-semibold uppercase tracking-wider">Produtos</h3>
            <p class="text-3xl font-bold text-slate-800 dark:text-white mt-1">{{ $totalProducts ?? 0 }}</p>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-800 p-6 rounded-[30px] shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-purple-100 dark:bg-purple-500/10 rounded-2xl text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                </div>
            </div>
            <h3 class="text-slate-500 dark:text-gray-400 text-sm font-semibold uppercase tracking-wider">Categorias</h3>
            <p class="text-3xl font-bold text-slate-800 dark:text-white mt-1">{{ $totalCategories ?? 0 }}</p>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-800 p-6 rounded-[30px] shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-100 dark:bg-blue-500/10 rounded-2xl text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2" stroke="#004aad" stroke-width="2"/><path d="M5 17L10 11L14 15L16 13L19 17" stroke="#0158cd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="8" cy="9" r="1.5" fill="#004aad"/></svg>
                </div>
            </div>
            <h3 class="text-slate-500 dark:text-gray-400 text-sm font-semibold uppercase tracking-wider">Fotos dos produtos</h3>
            <p class="text-3xl font-bold text-slate-800 dark:text-white mt-1">{{ $totalImagesProducts ?? 0 }}</p>
        </div>
        
        <div class="bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-800 p-6 rounded-[30px] shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-100 dark:bg-blue-500/10 rounded-2xl text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 8a4 4 0 100 8 4 4 0 000-8z" stroke="#004aad" stroke-width="2"/><path d="M4 12h2M18 12h2M12 4v2M12 18v2M6.2 6.2l1.4 1.4M16.4 16.4l1.4 1.4M6.2 17.8l1.4-1.4M16.4 7.6l1.4-1.4" stroke="#0158cd" stroke-width="2" stroke-linecap="round"/></svg>
                </div>
            </div>
            <h3 class="text-slate-500 dark:text-gray-400 text-sm font-semibold uppercase tracking-wider">Serviços</h3>
            <p class="text-3xl font-bold text-slate-800 dark:text-white mt-1">{{ $totalServices ?? 0 }}</p>
        </div>

    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
        <div class="lg:col-span-2 bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-800 rounded-[30px] p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-lg text-slate-800 dark:text-white">Produtos Adicionados Recentemente</h3>
                <a href="{{ route('dashboard.products.index') }}" class="text-xs font-bold text-[#004aad] dark:text-blue-400 uppercase hover:underline">Ver todos</a>
            </div>
            <div class="space-y-4">
                @forelse($recentProducts ?? [] as $product)
                    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-gray-800/50 rounded-2xl border border-transparent hover:border-slate-200 dark:hover:border-gray-700 transition-all">
                        <div class="flex items-center gap-4">
                            <div>
                                <img src="{{ asset($product->productImages->first() ? 'storage/'.$product->productImages->first()->img : 'storage/images/default.png') }}" class="w-12 h-12 object-cover rounded" alt="Imagem do produto" srcset="">
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-700 dark:text-gray-200">{{ $product->name }}</p>
                                <p class="text-xs text-slate-400">{{ $product->category->name ?? 'Sem categoria' }}</p>
                            </div>
                        </div>
                        <span class="font-bold text-sm text-slate-700 dark:text-gray-200">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                    </div>
                @empty
                    <p class="text-center text-slate-400 py-10 italic">Nenhuma atividade recente.</p>
                @endforelse
            </div>
        </div>
        <div class="bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-800 rounded-[30px] p-8">
            <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-6">Ações Rápidas</h3>
            <div class="grid grid-cols-1 gap-3">
                <a href="{{ route('dashboard.products.create') }}" class="flex items-center p-4 bg-slate-100 dark:bg-gray-950 rounded-2xl text-slate-600 dark:text-gray-300 hover:bg-[#004aad] hover:text-white transition-all group">
                    <span class="p-2 bg-white dark:bg-gray-800 rounded-lg mr-3 group-hover:bg-white/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </span>
                    <span class="font-bold text-sm">Novo Produto</span>
                </a>
                <a href="{{ route('dashboard.categories.create') }}" class="flex items-center p-4 bg-slate-100 dark:bg-gray-950 rounded-2xl text-slate-600 dark:text-gray-300 hover:bg-[#004aad] hover:text-white transition-all group">
                    <span class="p-2 bg-white dark:bg-gray-800 rounded-lg mr-3 group-hover:bg-white/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </span>
                    <span class="font-bold text-sm">Nova Categoria</span>
                </a>
                <a href="{{ route('dashboard.services.create') }}" class="flex items-center p-4 bg-slate-100 dark:bg-gray-950 rounded-2xl text-slate-600 dark:text-gray-300 hover:bg-[#004aad] hover:text-white transition-all group">
                    <span class="p-2 bg-white dark:bg-gray-800 rounded-lg mr-3 group-hover:bg-white/20">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 8a4 4 0 100 8 4 4 0 000-8z" stroke-width="2"/><path d="M4 12h2M18 12h2M12 4v2M12 18v2M6.2 6.2l1.4 1.4M16.4 16.4l1.4 1.4M6.2 17.8l1.4-1.4M16.4 7.6l1.4-1.4"  stroke-width="2" stroke-linecap="round"/></svg>
                    </span>
                    <span class="font-bold text-sm">Novo Serviço</span>
                </a>
                 <a href="{{ route('dashboard.profile.edit', auth()->user()->id) }}" class="flex items-center p-4 bg-slate-100 dark:bg-gray-950 rounded-2xl text-slate-600 dark:text-gray-300 hover:bg-[#004aad] hover:text-white transition-all group">
                    <span class="p-2 bg-white dark:bg-gray-800 rounded-lg mr-3 group-hover:bg-white/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2"/><path d="M4 20C4 16.6863 7.58172 14 12 14C16.4183 14 20 16.6863 20 20"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"/></svg>
                    </span>
                    <span class="font-bold text-sm">Meu Perfil</span>
                </a>
            </div>
        </div>

    </div>
</x-dashboard_layout>