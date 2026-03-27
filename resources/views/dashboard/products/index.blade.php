<x-dashboard_layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-bold text-2xl text-slate-800 dark:text-white tracking-tight">Produtos</h2>
            <a href="{{ route('dashboard.products.create') }}" 
               class="inline-flex items-center px-6 py-2.5 bg-[#004aad] hover:bg-[#0158cd] dark:bg-white dark:text-[#004aad] dark:hover:bg-gray-200 text-white text-sm font-bold rounded-full transition-all shadow-lg shadow-blue-500/20 active:scale-95 uppercase tracking-wider">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Novo Produto
            </a>
        </div>
    </x-slot>
    <div class="mt-8 bg-white dark:bg-gray-900 shadow-sm border border-slate-200 dark:border-gray-800 rounded-[30px] overflow-hidden transition-all">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-gray-800/50 border-b border-slate-200 dark:border-gray-800">
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-widest">Produto</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-widest">Imagem</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-widest">Categoria</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-widest">Preço</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-widest">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-gray-800">
                    @forelse ($products as $product)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-gray-800/30 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-slate-700 dark:text-gray-200">{{ $product->name }}</span>
                                    <span class="text-[10px] text-slate-400 uppercase">ID: #{{ $product->id }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <img src="{{ asset($product->productImages->first() ? 'storage/'.$product->productImages->first()->img : 'storage/images/default.png')}}" class="w-15 h-15 object-cover rounded" alt="Imagem do produto" srcset="">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs font-medium bg-slate-100 dark:bg-gray-800 text-slate-600 dark:text-gray-400 rounded-lg">{{ $product->category->name ?? 'Sem categoria' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($product->promotional_price != null && $product->promotional_price > 0)
                                    <span class="text-sm font-bold text-red-500">R$ {{ number_format($product->promotional_price, 2, ',', '.') }}</span>
                                @else
                                    <span class="text-sm font-bold text-[#004aad] dark:text-blue-400">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($product->status ?? true)
                                    <span class="inline-flex items-center px-3 py-1 bg-green-100 dark:bg-green-500/10 text-green-600 dark:text-green-400 text-xs font-bold rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-2"></span>
                                        Ativo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-400 text-xs font-bold rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-2"></span>
                                        Inativo
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('dashboard.products.edit', $product->id) }}" class="p-2 text-slate-400 hover:text-amber-500 transition-colors" title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>
                                    <form action="{{ route('dashboard.products.destroy', $product->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Deseja excluir o produto: {{ $product->name }}?')" class="p-2 text-slate-400 hover:text-red-500 transition-colors" title="Excluir">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-1 12a2 2 0 01-2 2H8a2 2 0 01-2-2L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 dark:text-gray-500 italic">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    Nenhum produto cadastrado.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
            <div class="px-6 py-4 bg-slate-50 dark:bg-gray-800/30 border-t border-slate-100 dark:border-gray-800">
                {{ $products->links() }}
            </div>
        @endif
    </div>
    <div class="fixed bottom-5 right-5 z-[60] flex flex-col gap-3">
        @if (session('success'))
            <div class="message bg-white dark:bg-gray-900 border-l-4 border-green-500 shadow-2xl rounded-xl p-4 flex items-center min-w-[300px] animate-bounce-subtle">
                <div class="p-2 bg-green-100 dark:bg-green-500/20 rounded-full mr-3 text-green-600">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                </div>
                <p class="text-sm font-bold text-slate-700 dark:text-gray-200">{{ session('success') }}</p>
            </div>
        @endif
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
        function editCategory(id) { window.location.href = `/dashboard/products/${id}/edit`; }
        setTimeout(() => {
            document.querySelectorAll('.message').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateX(20px)';
                el.style.transition = 'all 0.5s ease';
                setTimeout(() => el.remove(), 500);
            });
        }, 5000);
    </script>
</x-dashboard_layout>