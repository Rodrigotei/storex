<x-client_layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <h2 class="font-bold text-2xl text-slate-800 dark:text-white tracking-tight">{{ $categoryName->name }}</h2>
        </div>
    </x-slot>
    <section id="produtos">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8">
            @foreach($products as $product)
            <div class="bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-800 rounded-[35px] p-4 transition-all hover:shadow-2xl hover:shadow-blue-500/10 group">
                <div class="relative aspect-square bg-slate-100 dark:bg-gray-950 rounded-[25px] overflow-hidden mb-4">
                    <img src="{{ asset($product->productImages->first() ? 'storage/'.$product->productImages->first()->img : 'storage/images/product-default.png') }}" class="w-full h-full object-cover">
                </div>
                <div class="px-2">
                    <p class="text-[10px] font-bold text-blue-500 uppercase tracking-widest mb-1">{{ $product->category->name }}</p>
                    <h3 class="text-sm md:text-base font-bold text-slate-800 dark:text-white mb-2 line-clamp-1">{{ $product->name }}</h3>
                    <div class="flex items-center justify-between mt-4">
                        @if ($product->promotional_price && $product->promotional_price < $product->price)
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400 line-through">
                                    R$ {{ number_format($product->price, 2, ',', '.') }}
                                </span>
                                <span class="text-lg font-black text-red-500">
                                    R$ {{ number_format($product->promotional_price, 2, ',', '.') }}
                                </span>
                            </div>
                        @else
                            <span class="text-lg font-black text-slate-900 dark:text-white">
                                R$ {{ number_format($product->price, 2, ',', '.') }}
                            </span>
                        @endif
                        <button onclick="showProduct({{ $product->id }})" class="p-3 bg-[#004aad] text-white rounded-2xl hover:bg-[#0158cd] transition-all shadow-lg shadow-blue-500/20 active:scale-90">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2.5" stroke-linecap="round"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    <script>
        function showProduct(id){
            window.location.href = `/loja/product/${id}`;
        }
    </script>
</x-client_layout>
