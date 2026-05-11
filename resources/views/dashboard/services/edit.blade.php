<x-dashboard_layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard.services.index') }}" class="p-2 text-slate-400 hover:text-[#004aad] dark:hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-2xl text-slate-800 dark:text-white tracking-tight">Editar Serviço</h2>
        </div>
    </x-slot>
    <div class="mt-8 max-w-4xl mx-auto">
        <form action="{{ route('dashboard.services.update', $service->id) }}" enctype="multipart/form-data" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="bg-white dark:bg-gray-900 shadow-sm border border-slate-200 dark:border-gray-800 rounded-[30px] p-8 transition-all">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2 ml-1">Nome do Serviço</label>
                        <input type="text" name="name" value="{{ old('name', $service->name) }}" required class="w-full px-5 py-3 bg-slate-100 dark:bg-gray-950 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-gray-200 rounded-full focus:ring-2 focus:ring-[#0158cd] focus:border-transparent outline-none transition-all">
                        @error('name') 
                            <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p> 
                        @enderror
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2 ml-1">Preço (R$)</label>
                        <input type="number" step="0.01" min="0.00" name="price" value="{{ old('price', $service->price) }}" required 
                            class="w-full px-5 py-3 bg-slate-100 dark:bg-gray-950 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-gray-200 rounded-full focus:ring-2 focus:ring-[#0158cd] focus:border-transparent outline-none transition-all">
                        @error('price') 
                            <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p> 
                        @enderror
                    </div> 
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2 ml-1">Preço Promocional (R$)</label>
                        <input type="number" step="0.01" min="0.00" name="promotional_price" value="{{ old('promotional_price', $service->promotional_price) }}" 
                            class="w-full px-5 py-3 bg-slate-100 dark:bg-gray-950 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-gray-200 rounded-full focus:ring-2 focus:ring-[#0158cd] focus:border-transparent outline-none transition-all">
                        @error('promotional_price') 
                            <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p> 
                        @enderror
                    </div>
                    @if ($service->serviceImages->isNotEmpty())
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2 ml-1">Imagens salvas</label>
                        <div class="flex gap-4 items-start">
                            @for ($i = 0; $i < count($service->serviceImages); $i++)
                                <div class="flex flex-col gap-1 items-center">
                                    <img src="{{ asset($service->serviceImages[$i]->img ? 'storage/'.$service->serviceImages[$i]->img : 'storage/images/default.png') }}" class="w-24 h-24 object-cover rounded-2xl border-2 border-slate-200 dark:border-gray-800 shadow-sm">
                                    <button type="button" onclick="deleteImage({{ $service->serviceImages[$i]->id }})" class="py-1 px-2 bg-red-600 hover:bg-red-400 active:bg-red-800 text-white rounded transition-all text-sm">Remover</button>
                                </div>
                            @endfor
                        </div>
                    </div>
                    @endif
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2 ml-1">Adicionar Imagem</label>
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
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2 ml-1">Status</label>
                        <select name="status" class="w-full px-5 py-3 bg-slate-100 dark:bg-gray-950 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-gray-200 rounded-full focus:ring-2 focus:ring-[#0158cd] focus:border-transparent outline-none transition-all appearance-none">
                            <option value="1" @selected($service->status == '1')>Ativo</option>
                            <option value="0" @selected($service->status == '0')>Inativo</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2 ml-1">Descrição</label>
                        <textarea name="description" rows="4" class="w-full px-5 py-4 bg-slate-100 dark:bg-gray-950 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-gray-200 rounded-[20px] focus:ring-2 focus:ring-[#0158cd] focus:border-transparent outline-none transition-all resize-none">{{ old('description', $service->description) }}</textarea>
                        @error('description') 
                            <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p> 
                        @enderror
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-4 mt-4">
                <a href="{{ route('dashboard.services.index') }}" class="px-8 py-3 text-sm font-bold text-slate-500 hover:text-slate-700 dark:text-gray-400 dark:hover:text-white transition-all uppercase tracking-wider">Voltar</a>
                <button type="submit" class="px-10 py-3 bg-[#004aad] hover:bg-[#0158cd] dark:bg-white dark:text-[#004aad] dark:hover:bg-gray-200 text-white font-bold rounded-full shadow-lg transform active:scale-[0.98] transition-all uppercase text-sm tracking-wider">Atualizar Serviço</button>
            </div>
        </form>
    </div>
 <script>
   function deleteImage(id) {
        if (!confirm('Deseja excluir a imagem?')) return;

        fetch(`/dashboard/services/image/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(() => location.reload());
    }

    const imgInput = document.getElementById('img-input');
    const previewContainer = document.getElementById('preview-container');
    const placeholder = document.getElementById('upload-placeholder');

    imgInput.onchange = evt => {
        const files = imgInput.files;
        let currentImages = previewContainer.querySelectorAll('.service-img');
        currentImages.forEach(img => {
            URL.revokeObjectURL(img.src); 
            img.remove();
        });
        if (files.length > 0) {
            for (const file of files) {
                let img = document.createElement('img');
                img.className = "w-24 h-24 object-cover rounded-xl shadow-md service-img";
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
            img.className = "w-24 h-24 object-cover rounded-xl shadow-md service-img";
            img.src = URL.createObjectURL(files[i]); 
            previewContainer.appendChild(img);
        }
        previewContainer.classList.remove('hidden');
        placeholder.classList.add('hidden');
    }
</script>
</x-dashboard_layout>