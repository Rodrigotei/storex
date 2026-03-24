<x-dashboard_layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard.home') }}" class="p-2 text-slate-400 hover:text-[#004aad] dark:hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-2xl text-slate-800 dark:text-white tracking-tight">Meu Perfil</h2>
        </div>
    </x-slot>

    <div class="mt-8 max-w-4xl mx-auto">
        <form action="{{ route('dashboard.profile.update', $user->id) }}" method="POST" class="space-y-6" novalidate>
            @csrf
            @method('PATCH')
            {{-- ================= USER ================= --}}
            <div class="bg-white dark:bg-gray-900 shadow-sm border border-slate-200 dark:border-gray-800 rounded-[30px] p-8">
                <div class="flex items-center gap-3 mb-6 border-b border-slate-100 dark:border-gray-800 pb-4">
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white">Dados da Conta</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Nome</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-5 py-3 bg-slate-100 dark:bg-gray-950 rounded-full">
                        @error('name')
                            <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Documento <span class="text-gray-400 text-xs">(CPF)</span></label>
                        <input type="text" name="document" oninput="formatDocument(this)" value="{{ old('document', ($user->document ?? '-')) }}" required class="w-full px-5 py-3 bg-slate-100 dark:bg-gray-950 rounded-full">
                        @error('document')
                            <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mt-3">
                    <label class="block text-sm font-semibold mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-5 py-3 bg-slate-100 dark:bg-gray-950 rounded-full">
                    @error('email')
                        <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            {{-- ================= STORE ================= --}}
            <div class="bg-white dark:bg-gray-900 shadow-sm border border-slate-200 dark:border-gray-800 rounded-[30px] p-8">
                <div class="mb-6 border-b pb-4">
                    <h3 class="font-bold text-lg">Dados da Loja</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Nome da Loja</label>
                        <input type="text" name="store[name]" value="{{ old('store.name', ($user->store?->name ?? '-')) }}" class="w-full px-5 py-3 bg-slate-100 dark:bg-gray-950 rounded-full">
                        @error('store.name')
                            <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Telefone da Loja <span class="text-gray-400 text-xs">(whatsapp)</span></label>
                        <input type="text" name="store[phone]" oninput="formatPhone(this)" value="{{ old('store.phone', ($user->store?->phone ?? '-')) }}" class="w-full px-5 py-3 bg-slate-100 dark:bg-gray-950 rounded-full">
                        @error('store.phone')
                            <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mt-3">
                    <label class="block text-sm font-semibold mb-2">Descrição</label>
                    <textarea name="store[description]" cols="30" rows="5" class="w-full resize-none outline-none p-3 rounded bg-slate-100 dark:bg-gray-950">{{ $user->store->description }}</textarea>
                </div>
                <div class="col-span-1">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2 ml-1 ">Taxa de entrega (R$)</label>
                    <input type="number" step="0.01" name="store[delivery_fee]" value="{{ old('store.delivery_fee', $user->store->delivery_fee) }}" placeholder="0.00" class="w-full px-5 py-3 bg-slate-100 dark:bg-gray-950 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-gray-200 rounded-full focus:ring-2 focus:ring-[#0158cd] focus:border-transparent outline-none transition-all">
                    @error('store.delivery_fee') 
                        <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p> 
                    @enderror
                </div>
            </div>
            {{-- ================= ADDRESS ================= --}}
            <div class="bg-white dark:bg-gray-900 shadow-sm border border-slate-200 dark:border-gray-800 rounded-[30px] p-8">
                <div class="mb-6 border-b pb-4">
                    <h3 class="font-bold text-lg">Endereço da Loja</h3>
                </div>
                <div id="container-zip-code">
                    <label class="block text-sm font-semibold mb-2">CEP</label>
                    <div class="flex justify-start gap-4">
                        <input type="text" id="zip-code" oninput="formatZipCode(this)" name="address[zip_code]" value="{{ old('address.zip_code', str_replace('-', '', $user->store?->address?->zip_code)) }}" class="px-5 py-3 bg-slate-100 dark:bg-gray-950 rounded-full">
                        <button type="button" onclick="searchZipCode()"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"  width="24" height="24"><circle cx="11" cy="11" r="7"></circle><line x1="16.65" y1="16.65" x2="21" y2="21"></line></svg></button>
                    </div>
                    @error('address.zip_code')
                        <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p>
                    @enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-3">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Rua</label>
                        <input type="text" id="street" name="address[street]" value="{{ old('address.street', $user->store?->address?->street) }}" class="w-full px-5 py-3 bg-slate-100 dark:bg-gray-950 rounded-full">
                        @error('address.street')
                            <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Número</label>
                        <input type="text" id="number" name="address[number]" value="{{ old('address.number', $user->store?->address?->number) }}" maxlength="8" class="w-full px-5 py-3 bg-slate-100 dark:bg-gray-950 rounded-full">
                        @error('address.number')
                            <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Bairro</label>
                        <input type="text" id="neighborhood" name="address[neighborhood]" value="{{ old('address.neighborhood', $user->store?->address?->neighborhood) }}" class="w-full px-5 py-3 bg-slate-100 dark:bg-gray-950 rounded-full">
                        @error('address.neighborhood')
                            <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Cidade</label>
                        <input type="text" id="city" name="address[city]" value="{{ old('address.city', $user->store?->address?->city) }}" class="w-full px-5 py-3 bg-slate-100 dark:bg-gray-950 rounded-full">
                        @error('address,city')
                            <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Estado</label>
                        <input type="text" id="state" name="address[state]" value="{{ old('address.state', $user->store?->address?->state) }}" class="w-full px-5 py-3 bg-slate-100 dark:bg-gray-950 rounded-full">
                        @error('address.state')
                            <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p>
                        @enderror
                        </div>
                </div>
            </div>
            {{-- ================= PASSWORD ================= --}}
            <div class="bg-white dark:bg-gray-900 shadow-sm border border-slate-200 dark:border-gray-800 rounded-[30px] p-8">
                <h3 class="font-bold text-lg mb-4">Alterar Senha</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <input type="password" name="password" placeholder="Nova senha" class="px-5 py-3 bg-slate-100 dark:bg-gray-950 rounded-full">
                    <input type="password" name="password_confirmation" placeholder="Confirmar senha" class="px-5 py-3 bg-slate-100 dark:bg-gray-950 rounded-full">
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p>
                @enderror
                @error('password_confirmation')
                    <p class="text-red-500 text-xs mt-2 ml-4">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end gap-3">
                <a href="{{ route('dashboard.home') }}" class="px-10 py-3 bg-red-600 text-white rounded-full">Voltar</a>
                <button type="submit" class="px-10 py-3 bg-[#004aad] text-white rounded-full">Salvar</button>
            </div>
        </form>
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
        function formatDocument(input){
            let value = input.value.replace(/\D/g, '');
            if(value.length > 11) value = value.slice(0,11);
            input.value = value
        }
        function formatPhone(input){
            let value = input.value.replace(/\D/g, '');
            if(value.length > 11) value = value.slice(0,11);
            input.value = value
        }
        function formatZipCode(input){
            let value = input.value.replace(/\D/g, '');
            if(value.length > 8) value = value.slice(0,8);
            input.value = value
        }
        async function searchZipCode(){
            const zipCode = document.querySelector('#zip-code').value;
            const street = document.querySelector('#street');
            const number = document.querySelector('#number');
            const neighborhood = document.querySelector('#neighborhood');
            const city = document.querySelector('#city');
            const state = document.querySelector('#state');
            const containerZipCode = document.querySelector('#container-zip-code');

            const response = await fetch(`https://viacep.com.br/ws/${zipCode}/json/`)
            const result = await response.json();
            
            if(!result.erro){
                street.value = result.logradouro;
                neighborhood.value = result.bairro;
                city.value = result.localidade;
                state.value = result.estado;
            }else{
                street.value = '';
                neighborhood.value = '';
                city.value = '';
                state.value = '';

                const p = document.createElement('p');
                p.className = 'text-red-500 text-xs mt-2 ml-4';
                p.textContent = 'Cep inválido.'
                containerZipCode.appendChild(p); 
                setTimeout(() => {
                    p.remove();
                }, 2000);   
            }

        }
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