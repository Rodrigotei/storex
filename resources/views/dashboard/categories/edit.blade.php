<x-dashboard_layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard.categories.index') }}" class="p-2 text-slate-400 hover:text-[#004aad] dark:hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-2xl text-slate-800 dark:text-white tracking-tight">Editar Categoria</h2>
        </div>
    </x-slot>

    <div class="mt-8 max-w-3xl mx-auto">
        <form action="{{ route('dashboard.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="bg-white dark:bg-gray-900 shadow-sm border border-slate-200 dark:border-gray-800 rounded-[30px] p-8 transition-all">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2 ml-1">Nome da Categoria</label>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}" required 
                            class="w-full px-5 py-3 bg-slate-100 dark:bg-gray-950 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-gray-200 rounded-full focus:ring-2 focus:ring-[#0158cd] focus:border-transparent outline-none transition-all">
                        @error('name')
                            <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2 ml-1">Imagem da Categoria</label>
                        
                        <div class="flex flex-col sm:flex-row gap-4 items-start">
                            <div class="shrink-0">
                                <p class="text-[10px] uppercase font-bold text-slate-400 mb-1 ml-1 tracking-wider">Atual</p>
                                <img src="{{ asset($category->img ? 'storage/'.$category->img : 'storage/images/default.png') }}" class="w-24 h-24 object-cover rounded-2xl border-2 border-slate-200 dark:border-gray-800 shadow-sm">
                            </div>

                            <div class="flex-1 w-full relative group">
                                <p class="text-[10px] uppercase font-bold text-slate-400 mb-1 ml-1 tracking-wider">Alterar para:</p>
                                <input type="file" name="img" accept="image/*" id="img-input"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <div class="w-full px-5 py-6 bg-slate-100 dark:bg-gray-950 border-2 border-dashed border-slate-200 dark:border-gray-700 rounded-[20px] text-center group-hover:border-[#0158cd] transition-all">
                                    <div id="preview-container" class="hidden mb-2 flex justify-center">
                                        <img id="img-preview" src="#" alt="Preview" class="w-16 h-16 object-cover rounded-lg shadow-md border-2 border-white">
                                    </div>
                                    <div id="upload-placeholder">
                                        <svg class="w-6 h-6 mx-auto text-slate-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <p class="text-xs text-slate-500 dark:text-gray-400">Clique para substituir</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('img')
                            <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2 ml-1">Status</label>
                        <select name="status" class="w-full px-5 py-3 bg-slate-100 dark:bg-gray-950 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-gray-200 rounded-full focus:ring-2 focus:ring-[#0158cd] focus:border-transparent outline-none transition-all appearance-none cursor-pointer">
                            <option value="1" {{ old('status', $category->status) == 1 ? 'selected' : '' }}>Ativo</option>
                            <option value="0" {{ old('status', $category->status) == 0 ? 'selected' : '' }}>Inativo</option>
                        </select>
                        <span class="text-xs text-slate-500 dark:text-gray-400 mt-1 ml-2">Categorias inativas ocultam os produtos relacionados.</span>
                    </div>

                </div>
            </div>

            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('dashboard.categories.index') }}" class="px-8 py-3 text-sm font-bold text-slate-500 hover:text-slate-700 dark:text-gray-400 dark:hover:text-white transition-all uppercase tracking-wider">
                    Descartar Alterações
                </a>
                <button type="submit" class="px-10 py-3 bg-[#004aad] hover:bg-[#0158cd] dark:bg-white dark:text-[#004aad] dark:hover:bg-gray-200 text-white font-bold rounded-full shadow-lg transform active:scale-[0.98] transition-all uppercase text-sm tracking-wider">Salvar Categoria</button>
            </div>
        </form>
    </div>
    @error('error')
    <div class="fixed bottom-5 right-5 z-[60] flex flex-col gap-3">
        <div class="message bg-white dark:bg-gray-900 border-l-4 border-red-500 shadow-2xl rounded-xl p-4 flex items-center min-w-[300px]">
            <div class="p-2 bg-red-100 dark:bg-red-500/20 rounded-full mr-3 text-red-600">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </div>
            <p class="text-sm font-bold text-slate-700 dark:text-gray-200">{{ $message }}</p>
        </div>
    </div>
    @enderror
    <script>
        const imgInput = document.getElementById('img-input');
        const imgPreview = document.getElementById('img-preview');
        const previewContainer = document.getElementById('preview-container');
        const placeholder = document.getElementById('upload-placeholder');

        imgInput.onchange = evt => {
            const [file] = imgInput.files;
            if (file) {
                imgPreview.src = URL.createObjectURL(file);
                previewContainer.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
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
</x-dashboard_layout>