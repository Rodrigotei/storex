<x-client_layout>
    <div class="mb-10">
        <div class="bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-800 rounded-[30px] p-6 md:p-8 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-start gap-4">
                    @if ($store->img)
                        <img src="{{ asset('storage/'.$store->img) }}" class="w-20 h-20 object-cover rounded" alt="logo {{ $store->name }}" srcset="">
                    @else
                        <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl bg-slate-100 dark:bg-gray-800 flex items-center justify-center overflow-hidden border border-slate-200 dark:border-gray-700">
                            <span class="text-xl font-bold text-[#004aad]">{{ strtoupper(substr($store->name, 0, 2)) }}</span>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-xl md:text-2xl font-bold text-slate-800 dark:text-white">{{ $store->name }}</h1>
                        @if($store->description)
                            <p class="text-sm text-slate-500 dark:text-gray-400 mt-1 max-w-md">{{ $store->description }}</p>
                        @endif
                        <div class="flex flex-wrap items-center gap-3 mt-3 text-xs">
                            @if($store->phone)
                                <span class="px-3 py-1 rounded-full bg-slate-100 dark:bg-gray-800 text-slate-600 dark:text-gray-300">📞 {{ $store->phone }}</span>
                            @endif
                            @if ($store->delivery_fee > 0)
                                <span class="px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/30 text-[#004aad] dark:text-blue-300 font-semibold">🚚 R$ {{ number_format($store->delivery_fee, 2, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="https://wa.me/55{{ preg_replace('/\D/', '', $store->phone) }}" target="_blank" class="px-5 py-3 rounded-2xl bg-[#004aad] hover:bg-[#0158cd] text-white font-semibold text-sm transition-all shadow-sm hover:shadow-md">Entrar em Contato</a>
                </div>
            </div>
            <div x-data="{ tab: '' }" class="mt-8">
                <div class="flex gap-6 border-b border-slate-200 dark:border-gray-800">
                    <button @click="tab = 'address'" :class="tab === 'address' ? 'text-[#004aad] border-[#004aad]' : 'text-slate-500 border-transparent'"class="pb-3 border-b-2 font-bold text-sm transition-all">Endereço</button>
                </div>
                <div class="pt-6 text-sm">
                    <div x-show="tab === 'address'" x-transition>
                        @if($store->address)
                            <div class="bg-slate-50 dark:bg-gray-800 rounded-2xl p-4 text-slate-600 dark:text-gray-300 space-y-1">
                                <p class="font-semibold">{{ $store->address->street }}, {{ $store->address->number }}</p>
                                @if($store->address->complement)
                                    <p>{{ $store->address->complement }}</p>
                                @endif
                                <p>{{ $store->address->neighborhood }}</p>
                                <p>{{ $store->address->city }} - {{ $store->address->state }}</p>
                                <p class="text-sm text-slate-400">CEP: {{ $store->address->zip_code }}</p>
                            </div>
                        @else
                            <p class="text-slate-500">Endereço não informado.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($categories->isNotEmpty())
         <section class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Categorias</h2>
                <a href="{{ route('client.categories', ['tenant' => app('store')->slug]) }}" class="text-sm text-gray-500 hover:text-gray-700 font-bold">Ver todas</a>
            </div>
            <div class="flex gap-4 overflow-x-auto pb-4 no-scrollbar">
                @foreach($categories as $category)
                    <a href="{{ route('client.category', ['tenant' => app('store')->slug, 'id' => $category->id]) }}" class="flex-none w-20 md:w-30 group text-center">
                        <div class="w-full aspect-square bg-white dark:bg-gray-900 rounded-[30px] border border-slate-200 dark:border-gray-800 flex items-center justify-center mb-3 group-hover:border-[#0158cd] group-hover:shadow-lg transition-all overflow-hidden">
                            <img src="{{ asset($category->img ? 'storage/'.$category->img : 'storage/images/default.png') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        </div>
                        <span class="text-sm font-bold text-slate-700 dark:text-gray-300 group-hover:text-[#004aad] transition-colors">{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
    @if ($promotionalProducts->isNotEmpty())
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Promoções</h2>
            </div>
            <div class="flex gap-4 overflow-x-auto pb-4 no-scrollbar">
                @foreach ($promotionalProducts as $promotionalProduct)
                    <div class="min-w-[180px] max-w-[180px] flex-shrink-0 bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-800 rounded-[35px] p-4 transition-all hover:shadow-2xl hover:shadow-blue-500/10 group">
                        <div class="relative aspect-square bg-slate-100 dark:bg-gray-950 rounded-[25px] overflow-hidden mb-4">
                            <img src="{{ asset($promotionalProduct->productImages->first() ? 'storage/'.$promotionalProduct->productImages->first()->img : 'storage/images/default.png') }}" class="w-full h-full object-cover">
                        </div>
                        <div class="px-2">
                            <p class="text-[10px] font-bold text-blue-500 uppercase tracking-widest mb-1">{{ $promotionalProduct->category->name }}</p>
                            <h3 class="text-sm md:text-base font-bold text-slate-800 dark:text-white mb-2 line-clamp-1">{{ $promotionalProduct->name }}</h3>
                            <div class="flex items-center justify-between mt-4">
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-400 line-through">
                                        R$ {{ number_format($promotionalProduct->price, 2, ',', '.') }}
                                    </span>
                                    <span class="text-lg font-black text-red-500">
                                        R$ {{ number_format($promotionalProduct->promotional_price, 2, ',', '.') }}
                                    </span>
                                </div>
                                <a href="{{ route('client.product', ['tenant' => app('store')->slug, 'id' => $promotionalProduct->id]) }}">
                                    <button class="p-3 bg-[#004aad] text-white rounded-2xl hover:bg-[#0158cd] transition-all shadow-lg shadow-blue-500/20 active:scale-90"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2.5" stroke-linecap="round"/></svg></button>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
    @if ($lastProducts->isNotEmpty())
        <section id="produtos" class="mb-12">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Novidades</h2>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 md:gap-8">
                @foreach ($lastProducts as $product)
                    <div class="bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-800 rounded-[35px] p-4 transition-all hover:shadow-2xl hover:shadow-blue-500/10 group">
                        <div class="relative aspect-square bg-slate-100 dark:bg-gray-950 rounded-[25px] overflow-hidden mb-4">
                            <img src="{{ asset($product->productImages->first() ? 'storage/'.$product->productImages->first()->img : 'storage/images/default.png') }}" class="w-full h-full object-cover">
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
                                <a href="{{ route('client.product', ['tenant' => app('store')->slug, 'id' => $product->id]) }}">
                                    <button class="p-3 bg-[#004aad] text-white rounded-2xl hover:bg-[#0158cd] transition-all shadow-lg shadow-blue-500/20 active:scale-90"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2.5" stroke-linecap="round"/></svg></button>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
    @if ($services->isNotEmpty())
        <section id="produtos">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Serviços</h2>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 md:gap-8">
                @foreach ($services as $service)
                    <div class="bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-800 rounded-[35px] p-3 transition-all hover:shadow-2xl hover:shadow-blue-500/10 group">
                        <div class="relative aspect-square bg-slate-100 dark:bg-gray-950 rounded-[25px] overflow-hidden mb-4">
                            <img src="{{ asset($service->serviceImages->first() ? 'storage/'.$service->serviceImages->first()->img : 'storage/images/default.png') }}" class="w-full h-full object-cover">
                        </div>
                        <div class="px-2">
                            <h3 class="text-sm md:text-base font-bold text-slate-800 dark:text-white mb-2 line-clamp-1">{{ $service->name }}</h3>
                            <div class="flex items-center justify-between mt-4">
                               @if ($service->promotional_price && $service->promotional_price < $service->price)
                                    <div class="flex flex-col">
                                       <span class="text-xs text-gray-400 line-through">
                                            R$ {{ number_format($service->price, 2, ',', '.') }}
                                        </span>
                                        <span class="text-lg font-black text-red-500">
                                            R$ {{ number_format($service->promotional_price, 2, ',', '.') }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-lg font-black text-slate-900 dark:text-white">
                                        R$ {{ number_format($service->price, 2, ',', '.') }}
                                    </span>
                                @endif
                                 <a href="{{ route('client.service', ['tenant' => app('store')->slug, 'id' => $service->id]) }}">
                                    <button class="p-3 bg-[#004aad] text-white rounded-2xl hover:bg-[#0158cd] transition-all shadow-lg shadow-blue-500/20 active:scale-90"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2.5" stroke-linecap="round"/></svg></button>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</x-client_layout>