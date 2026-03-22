<x-dashboard_layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard.products.index') }}" class="p-2 text-slate-400 hover:text-[#004aad] dark:hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-2xl text-slate-800 dark:text-white tracking-tight">Novo Produto</h2>
        </div>
    </x-slot>
    <div class="mt-8 max-w-4xl mx-auto">
        <form action="{{ route('dashboard.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" novalidate>
            @csrf
            <div class="bg-white dark:bg-gray-900 shadow-sm border border-slate-200 dark:border-gray-800 rounded-[30px] p-8 transition-all">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2 ml-1">Nome do Produto</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Ex: Smartphone Galaxy S24" 
                            class="w-full px-5 py-3 bg-slate-100 dark:bg-gray-950 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-gray-200 rounded-full focus:ring-2 focus:ring-[#0158cd] focus:border-transparent outline-none transition-all">
                        @error('name') 
                            <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p> 
                        @enderror
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2 ml-1">Categoria</label>
                        <div class="relative">
                            <select name="category_id" required class="w-full px-5 py-3 bg-slate-100 dark:bg-gray-950 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-gray-200 rounded-full focus:ring-2 focus:ring-[#0158cd] focus:border-transparent outline-none transition-all appearance-none cursor-pointer">
                                <option value="">Selecione uma categoria</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                            </div>
                        </div>
                        @error('category_id') 
                            <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p> 
                        @enderror
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2 ml-1 ">Preço (R$)</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price') }}" required placeholder="0,00" class="w-full px-5 py-3 bg-slate-100 dark:bg-gray-950 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-gray-200 rounded-full focus:ring-2 focus:ring-[#0158cd] focus:border-transparent outline-none transition-all">
                        @error('price') 
                            <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p> 
                        @enderror
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2 ml-1">Imagem de Capa</label>
                        <div class="relative group">
                            <input type="file" name="img[]" multiple accept="image/*" id="img-input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="w-full px-5 py-8 bg-slate-100 dark:bg-gray-950 border-2 border-dashed border-slate-200 dark:border-gray-700 rounded-[20px] text-center group-hover:border-[#0158cd] transition-all">
                                <div id="preview-container" class="hidden mb-4 flex justify-center gap-2">
                                </div>
                                <div id="upload-placeholder">
                                    <svg class="w-8 h-8 mx-auto text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <p class="text-xs text-slate-500 dark:text-gray-400">Clique para selecionar ou arraste uma imagem</p>
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
                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Ativo</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2 ml-1">Descrição</label>
                        <textarea name="description" rows="4" placeholder="Detalhes sobre o produto..." class="w-full px-5 py-4 bg-slate-100 dark:bg-gray-950 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-gray-200 rounded-[20px] focus:ring-2 focus:ring-[#0158cd] focus:border-transparent outline-none transition-all resize-none">{{ old('description') }}</textarea>
                        @error('description') 
                            <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p> 
                        @enderror
                    </div>
                </div>
                <div class="col-span-1 md:col-span-2 mt-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" id="has-variation" class="w-5 h-5 text-[#004aad] rounded">
                        <span class="text-sm font-semibold text-slate-700 dark:text-gray-300">Este produto possui variações?</span>
                    </label>
                </div>
                <div id="variation-container" class="hidden col-span-1 md:col-span-2 space-y-4 mt-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2 ml-1">Tipo de Variação</label>
                        <select name="variation_id" class="w-full px-5 py-3 bg-slate-100 dark:bg-gray-950 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-gray-200 rounded-full focus:ring-2 focus:ring-[#0158cd] outline-none appearance-none">
                            <option value="">Selecione</option>
                            @foreach($variations as $variation)
                                <option value="{{ $variation->id }}">{{ $variation->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="variation-values" class="space-y-3">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2 ml-1">Valores da Variação</label>
                        <div class="flex gap-2">
                            <input type="text" name="variation_values[]" placeholder="Ex: P, M, G ou Azul, Vermelho" class="flex-1 px-5 py-3 bg-slate-100 dark:bg-gray-950 border border-slate-200 dark:border-gray-700 rounded-full focus:ring-2 focus:ring-[#0158cd] outline-none">
                            <button type="button" id="add-variation-value"class="px-5 py-2 bg-[#004aad] text-white rounded-full text-sm">+</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('dashboard.products.index') }}" class="px-8 py-3 text-sm font-bold text-slate-500 hover:text-slate-700 dark:text-gray-400 dark:hover:text-white transition-all uppercase tracking-wider">Cancelar</a>
                <button type="submit" class="px-10 py-3 bg-[#004aad] hover:bg-[#0158cd] dark:bg-white dark:text-[#004aad] dark:hover:bg-gray-200 text-white font-bold rounded-full shadow-lg transform active:scale-[0.98] transition-all uppercase text-sm tracking-wider">Cadastrar Produto</button>
            </div>
        </form>
    </div>
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
        const imgInput = document.getElementById('img-input');
        const previewContainer = document.getElementById('preview-container');
        const placeholder = document.getElementById('upload-placeholder');
        imgInput.onchange = evt => {
            const files = imgInput.files;
            let currentImages = previewContainer.querySelectorAll('.product-img');
            currentImages.forEach(img => {
                URL.revokeObjectURL(img.src); 
                img.remove();
            });
            if (files.length > 0) {
                for (const file of files) {
                    let img = document.createElement('img');
                    img.className = "w-24 h-24 object-cover rounded-xl shadow-md product-img";
                    img.src = URL.createObjectURL(file); 
                    previewContainer.appendChild(img);
                }
                previewContainer.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                previewContainer.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
        }
        const files = imgInput.files;
        if(files.length > 0){
            for(var i = 0; i < files.length; i++){
                let img = document.createElement('img');
                img.className = "w-24 h-24 object-cover rounded-xl shadow-md product-img";
                img.src = URL.createObjectURL(files[i]); 
                previewContainer.appendChild(img);
            }
            previewContainer.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
        const hasVariation = document.getElementById('has-variation');
        const variationContainer = document.getElementById('variation-container');
        const addBtn = document.getElementById('add-variation-value');
        const valuesContainer = document.getElementById('variation-values');
        hasVariation.addEventListener('change', () => {
            if (hasVariation.checked) {
                variationContainer.classList.remove('hidden');
            } else {
                variationContainer.classList.add('hidden');
            }
        });
        addBtn.addEventListener('click', () => {
            const div = document.createElement('div');
            div.className = "flex gap-2";
            div.innerHTML = `
            <input type="text" name="variation_values[]" placeholder="Outro valor" class="flex-1 px-5 py-3 bg-slate-100 dark:bg-gray-950 border border-slate-200 dark:border-gray-700 rounded-full focus:ring-2 focus:ring-[#0158cd] outline-none">
            <button type="button" class="remove-variation px-5 py-2 bg-red-500 text-white rounded-full text-sm">x</button>
            `;
            valuesContainer.appendChild(div);
        });
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-variation')) {
                e.target.parentElement.remove();
            }
        });
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