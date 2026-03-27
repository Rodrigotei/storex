<x-client_layout>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" 
        x-data="{ activeImg: '{{ asset('storage/' . ($product->productImages->first()->img ?? 'products/default.png')) }}', qty: 1 }">
        <div class="mb-8">
            <a href="{{ route('client.home') }}" class="inline-flex items-center text-sm font-bold text-slate-400 hover:text-[#004aad] transition-colors group">
                <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Voltar ao início
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div class="space-y-6">
                <div class="aspect-square bg-white dark:bg-gray-900 rounded-[40px] overflow-hidden border border-slate-200 dark:border-gray-800 shadow-sm group">
                    <img :src="activeImg" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $product->name }}">
                </div>
                @if($product->productImages->count() > 0)
                    <div class="flex gap-4 overflow-x-auto pb-2 no-scrollbar">
                        @foreach($product->productImages as $image)
                            <button @click="activeImg = '{{ asset('storage/' . $image->img) }}'" class="relative flex-none w-24 h-24 rounded-[20px] overflow-hidden border-2 transition-all duration-300" :class="activeImg === '{{ asset('storage/' . $image->img) }}' ? 'border-[#004aad] ring-4 ring-blue-500/10' : 'border-transparent opacity-60 hover:opacity-100'">
                                <img src="{{ asset('storage/' . $image->img) }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="flex flex-col">
                <div class="mb-6">
                    <span class="text-xs font-bold text-blue-500 uppercase tracking-widest px-3 py-1 bg-blue-50 dark:bg-blue-500/10 rounded-full">{{ $product->category->name }}</span>
                    <h1 class="text-4xl font-black text-slate-800 dark:text-white mt-4 tracking-tight leading-tight">{{ $product->name }}</h1>
                </div>
                <div class="mb-8">
                    @if ($product->promotional_price && $product->promotional_price < $product->price)
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-400 line-through">
                                R$ {{ number_format($product->price, 2, ',', '.') }}
                            </span>
                            <span class="text-4xl font-black text-red-500">
                                R$ {{ number_format($product->promotional_price, 2, ',', '.') }}
                            </span>
                        </div>
                    @else
                        <span class="text-4xl font-black text-[#004aad] dark:text-blue-400">
                            R$ {{ number_format($product->price, 2, ',', '.') }}
                        </span>
                    @endif
                </div>
                <div class="bg-slate-50 dark:bg-gray-800/30 rounded-[30px] p-6 mb-8 border border-slate-100 dark:border-gray-800">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-2 uppercase tracking-wider">Descrição</h3>
                    <p class="text-slate-600 dark:text-gray-400 leading-relaxed">{{ $product->description ?? 'Sem descrição disponível.' }}</p>
                </div>
                <form action="{{ route('client.cart.add') }}" method="POST" class="space-y-10">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    @if($product->productVariations->count() > 0)
                        <div x-data="{ selectedVariationName: ''}">
                            <label class="block text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider mb-4">{{ $product->productVariations->first()->variation->name }}</label>
                            <input type="hidden" name="variation_name" :value="selectedVariationName">
                            <div class="flex flex-wrap gap-3">
                                @foreach($product->productVariations as $variation)
                                    <label class="relative cursor-pointer group">
                                        <input @click="selectedVariationName = '{{ $variation->value }}'" type="radio" name="variation_id" value="{{ $variation->id }}" class="peer sr-only" required>
                                        <div class="px-6 py-3 bg-white dark:bg-gray-950 border-2 border-slate-200 dark:border-gray-800 rounded-2xl text-sm font-bold text-slate-600 dark:text-gray-400 peer-checked:border-[#004aad] peer-checked:bg-blue-50/50 dark:peer-checked:bg-blue-500/10 peer-checked:text-[#004aad] transition-all duration-300">{{ $variation->value }}</div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2 ml-1">Observação</label>
                        <textarea name="observation" rows="3" placeholder="Observação sobre o produto..." class="w-full px-5 py-4 bg-slate-100 dark:bg-gray-950 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-gray-200 rounded-[20px] focus:ring-2 focus:ring-[#0158cd] focus:border-transparent outline-none transition-all resize-none">{{ old('description') }}</textarea>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4 pt-8 border-t border-slate-100 dark:border-gray-800">
                        <div class="flex items-center justify-between bg-slate-100 dark:bg-gray-950 rounded-full p-1.5 w-full sm:w-40 border border-slate-200 dark:border-gray-800">
                            <button type="button" @click="if(qty > 1) qty--" class="w-10 h-10 flex items-center justify-center text-slate-500 hover:text-[#004aad] bg-white dark:bg-gray-900 rounded-full shadow-sm transition-all focus:outline-none">-</button>
                            <input type="number" name="quantity" x-model="qty" readonly class="w-12 bg-transparent text-center font-bold border-none focus:ring-0 text-slate-800 dark:text-white cursor-default">
                            <button type="button" @click="qty++" class="w-10 h-10 flex items-center justify-center text-slate-500 hover:text-[#004aad] bg-white dark:bg-gray-900 rounded-full shadow-sm transition-all focus:outline-none">+</button>
                        </div>
                        <button type="submit" class="flex-1 bg-[#004aad] hover:bg-[#0158cd] text-white font-bold py-5 px-10 rounded-full shadow-2xl shadow-blue-500/30 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-4 group">
                            <svg class="w-6 h-6 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Adicionar ao Carrinho
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
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
        function editCategory(id) { window.location.href = `/dashboard/categories/${id}/edit`; }
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
