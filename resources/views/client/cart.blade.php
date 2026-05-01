<x-client_layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h1 class="font-bold text-2xl text-slate-800 dark:text-white tracking-tight">Seu Carrinho</h1>
            <a href="{{ route('client.home', ['tenant' => app('store')->slug]) }}" class="inline-flex items-center text-sm font-bold text-slate-400 hover:text-[#004aad] transition-colors group">
                <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Voltar ao início
            </a>
        </div>
    </x-slot>
    @if(session('cart') && count(session('cart')) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-4">
                @php $total = 0; @endphp
                @foreach(session('cart') as $index => $item)
                    @php $total += $item['price'] * $item['qty']; @endphp
                    <div class="bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-800 rounded-[30px] p-4 flex items-center gap-4 sm:gap-6 shadow-sm">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none rounded-[20px] overflow-hidden bg-slate-100 dark:bg-gray-950"><img src="{{ asset('storage/' . ($item['image'] ?? 'products/default.png')) }}" class="w-full h-full object-cover"></div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm sm:text-base font-bold text-slate-800 dark:text-white truncate">{{ $item['name'] }}</h3>
                            @if(isset($item['variation']))
                                <p class="text-xs text-slate-400 font-medium uppercase mt-1">Opção: <span class="text-[#004aad] dark:text-blue-400">{{ $item['variation'] }}</span></p>
                            @endif
                            <p class="text-sm font-black text-slate-900 dark:text-white mt-2">R$ {{ number_format($item['price'], 2, ',', '.') }}</p>
                            <div><span class="text-gray-500 text-sm">Observação:</span> {{ $item['observation'] }}</div>
                        </div>
                        <div class="flex flex-col items-end gap-3">
                            <form action="{{ route('client.cart.delete', ['tenant' => app('store')->slug]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="index" value="{{ $index }}">
                                <button type="submit" onclick="return confirm('Deseja remover do carrinho o produto: {{ $item['name'] }}?')" class="p-2 text-slate-300 hover:text-red-500 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-1 12a2 2 0 01-2 2H8a2 2 0 01-2-2L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                            </form>
                            <div class="text-xs font-bold text-slate-500 bg-slate-100 dark:bg-gray-800 px-3 py-1 rounded-full">{{ $item['qty'] }}x</div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-800 rounded-[40px] p-8 sticky top-24 shadow-xl shadow-blue-500/5">
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-6">Resumo</h2>
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between text-slate-500 dark:text-gray-400">
                            <span>Subtotal</span>
                            <span>R$ {{ number_format($total, 2, ',', '.') }}</span>
                        </div>
                        @if ($delivery_fee > 0.00)
                            <div class="flex justify-between text-slate-500 dark:text-gray-400">
                                <span>Taxa de Entrega</span>
                                @php $total += $delivery_fee; @endphp
                                <span class="text-green-500 font-bold">R$ {{ number_format($delivery_fee, 2, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="border-t border-slate-100 dark:border-gray-800 pt-4 flex justify-between items-end">
                            <span class="text-base font-bold text-slate-800 dark:text-white">Total</span>
                            <span class="text-2xl font-black text-[#004aad] dark:text-blue-400 leading-none">R$ {{ number_format($total, 2, ',', '.') }}</span>
                        </div>
                        
                    </div>
                    <div x-data="{open : false}" >
                        <button type="button" @click="open = !open" :class="{ 'hidden': open }" class="w-full bg-[#004aad] hover:bg-[#0158cd] text-white font-bold py-4 rounded-full flex items-center justify-center gap-3 transition-all shadow-lg shadow-blue-500/20 active:scale-95">
                            Finalizar
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                        <form action="{{ route('client.order.finish', ['tenant' => app('store')->slug]) }}" method="POST" :class="{ 'hidden': !open }" class="space-y-5 mt-5">
                            @csrf
                            <input type="hidden" name="delivery_fee" value="{{ $delivery_fee ?? 0 }}">
                            <div>
                                <h3 class="text-sm font-bold text-slate-600 dark:text-gray-300 mb-2">Dados do cliente</h3>
                                <input type="text" name="name" required placeholder="Seu nome" class="w-full mb-2 px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                            </div>
                            <div x-data="{ type: '' }">
                                <h3 class="text-sm font-bold text-slate-600 dark:text-gray-300 mb-2">Recebimento</h3>
                                <div class="flex gap-2 mb-3">
                                    <button type="button" @click="type = 'pickup'" :class="type === 'pickup' ? 'bg-[#004aad] text-white' : 'bg-gray-100 dark:bg-gray-800'" class="flex-1 py-2 rounded-xl text-sm font-bold">
                                        Retirada
                                    </button>
                                    <button type="button" @click="type = 'delivery'" :class="type === 'delivery' ? 'bg-[#004aad] text-white' : 'bg-gray-100 dark:bg-gray-800'" class="flex-1 py-2 rounded-xl text-sm font-bold">
                                        Entrega
                                    </button>
                                </div>
                                <input type="hidden" name="type" :value="type">
                                <div x-show="type === 'delivery'" x-transition>
                                    <div id="container-zip-code">
                                        <div class="flex justify-start gap-4">
                                            <input type="text" id="zip-code" oninput="formatZipCode(this)" name="address[zip_code]" class="w-full mb-2 px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm" placeholder="CEP">
                                            <button type="button" onclick="searchZipCode()"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"  width="24" height="24"><circle cx="11" cy="11" r="7"></circle><line x1="16.65" y1="16.65" x2="21" y2="21"></line></svg></button>
                                        </div>
                                    </div>
                                    <input type="text" id="street" name="address" placeholder="Endereço" class="w-full mb-2 px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                    <input type="text" id="number" name="number" placeholder="Número" class="w-full mb-2 px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                    <input type="text" id="neighborhood" name="neighborhood" placeholder="Bairro" class="w-full mb-2 px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                    <input type="text" name="complement" placeholder="Complemento (opcional)" class="w-full mb-2 px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                </div>
                            </div>
                            <div>
                                <div x-data="{ payment_method: '' }">
                                <select x-model="payment_method" name="payment_method" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                    <option value="">Selecione</option>
                                    <option value="pix">PIX</option>
                                    <option value="cash">Dinheiro</option>
                                    <option value="card">Cartão</option>
                                </select>
                                <input x-show="payment_method === 'cash'"x-transition type="number" name="change_for"  step="0.01" min="0" placeholder="Troco para" class="w-full mt-2 px-4 py-3 rounded-xl border text-sm">
                            </div>
                            <button type="submit" class="w-full bg-[#004aad] hover:bg-[#0158cd] text-white font-bold py-4 rounded-full flex items-center justify-center gap-3 mt-5 transition-all shadow-lg shadow-blue-500/20 active:scale-95">
                                Fazer Pedido
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </form>
                   </div>
                    <p class="text-[10px] text-center text-slate-400 mt-6 uppercase tracking-widest font-bold">🔒 Pagamento 100% Seguro</p>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-800 rounded-[40px] py-20 px-10 text-center">
            <div class="w-24 h-24 bg-slate-50 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6"><svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-2">Ops! Seu carrinho está vazio.</h2>
            <a href="{{ route('client.home', ['tenant' => app('store')->slug]) }}" class="inline-flex bg-[#004aad] text-white font-bold px-10 py-4 rounded-full hover:bg-[#0158cd] transition-all shadow-lg mt-3">Voltar ao início</a>
        </div>
    @endif
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
         function formatZipCode(input){
            let value = input.value.replace(/\D/g, '');
            if(value.length > 8) value = value.slice(0,8);
            input.value = value
        }
        async function searchZipCode(){
            const zipCode = document.querySelector('#zip-code').value;
            const street = document.querySelector('#street');
            const neighborhood = document.querySelector('#neighborhood');
            const containerZipCode = document.querySelector('#container-zip-code');

            if(!zipCode || zipCode.length != 8){
                return;
            }
            const response = await fetch(`https://viacep.com.br/ws/${zipCode}/json/`)
            const result = await response.json();
            
            if(!result.erro){
                street.value = result.logradouro;
                neighborhood.value = result.bairro;
            }else{
                street.value = '';
                neighborhood.value = '';

                const p = document.createElement('p');
                p.className = 'text-red-500 text-xs mb-2 ml-4';
                p.textContent = 'Cep inválido.'
                containerZipCode.appendChild(p); 
                setTimeout(() => {
                    p.remove();
                }, 2000);   
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
</x-client_layout>