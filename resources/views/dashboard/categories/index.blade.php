<x-dashboard_layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-bold text-2xl text-slate-800 dark:text-white tracking-tight">Categorias</h2>
            <a href="{{ route('dashboard.categories.create') }}" class="inline-flex items-center px-6 py-2.5 bg-[#004aad] hover:bg-[#0158cd] dark:bg-white dark:text-[#004aad] dark:hover:bg-gray-200 text-white text-sm font-bold rounded-full transition-all shadow-lg shadow-blue-500/20 active:scale-95 uppercase tracking-wider">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nova Categoria
            </a>
        </div>
    </x-slot>
    <div class="mt-8 bg-white dark:bg-gray-900 shadow-sm border border-slate-200 dark:border-gray-800 rounded-[30px] overflow-hidden transition-all">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-gray-800/50 border-b border-slate-200 dark:border-gray-800">
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-widest">Nome</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-widest">Image</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-widest">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-gray-800">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-gray-800/30 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap"><span class="text-sm font-semibold text-slate-700 dark:text-gray-200">{{ $category->name }}</span></td>
                            <td class="px-6 py-2 whitespace-nowrap">
                                 <img src="{{ asset($category->img ? 'storage/'.$category->img : 'img/default.png') }}" alt="Imagem da categoria" class="w-15 h-15 object-cover rounded">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($category->status ?? true)
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
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-3">
                                    <button onclick='editCategory({{ $category->id }})'
                                        class="p-2 text-slate-400 hover:text-amber-500 dark:hover:text-amber-400 transition-colors" title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <form action="{{ route('dashboard.categories.destroy', $category->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Deseja excluir a categoria: {{ $category->name }}')" class="p-2 text-slate-400 hover:text-red-400 transition-colors" title="Visualizar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-1 12a2 2 0 01-2 2H8a2 2 0 01-2-2L5 7" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"d="M10 11v6M14 11v6" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"d="M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 dark:text-gray-500 italic">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    Nenhuma categoria encontrada.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="px-6 py-4 bg-slate-50 dark:bg-gray-800/30 border-t border-slate-100 dark:border-gray-800">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
    <script>
        function editCategory(id) { window.location.href = `/dashboard/categories/${id}/edit`; }
    </script>
</x-dashboard_layout>