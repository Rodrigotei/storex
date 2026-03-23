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
                    <span class="text-4xl font-black text-[#004aad] dark:text-blue-400">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                </div>
                <div class="bg-slate-50 dark:bg-gray-800/30 rounded-[30px] p-6 mb-8 border border-slate-100 dark:border-gray-800">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-2 uppercase tracking-wider">Descrição</h3>
                    <p class="text-slate-600 dark:text-gray-400 leading-relaxed">{{ $product->description ?? 'Sem descrição disponível.' }}</p>
                </div>
                <form action="" method="POST" class="space-y-10">
                    @csrf
                    @if($product->productVariations->count() > 0)
                        <div>
                            <label class="block text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider mb-4">{{ $product->productVariations->first()->variation->name }}</label>
                            <div class="flex flex-wrap gap-3">
                                @foreach($product->productVariations as $variation)
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="variation_id" value="{{ $variation->id }}" class="peer sr-only" required>
                                        <div class="px-6 py-3 bg-white dark:bg-gray-950 border-2 border-slate-200 dark:border-gray-800 rounded-2xl text-sm font-bold text-slate-600 dark:text-gray-400 peer-checked:border-[#004aad] peer-checked:bg-blue-50/50 dark:peer-checked:bg-blue-500/10 peer-checked:text-[#004aad] transition-all duration-300">{{ $variation->value }}</div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
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
</x-client_layout>
