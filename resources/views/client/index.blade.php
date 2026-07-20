<x-client_layout>
    @php($whatsappUrl = $store->whatsappUrl('Olá! Gostaria de saber mais sobre os produtos da '.$store->name.'.'))
    <div class="mb-10">
        <div class="relative overflow-hidden rounded-[32px] border border-blue-100 bg-gradient-to-br from-white via-white to-blue-50/80 p-6 shadow-xl shadow-blue-950/5 dark:border-gray-800 dark:from-gray-900 dark:via-gray-900 dark:to-blue-950/20 md:p-8">
            <div class="pointer-events-none absolute -right-24 -top-24 size-72 rounded-full bg-blue-500/10 blur-3xl dark:bg-blue-500/5"></div>
            <div class="relative flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div class="flex items-start gap-4">
                    @if ($store->img)
                        <img src="{{ Storage::url($store->img) }}" class="size-20 rounded-3xl border-4 border-white object-cover shadow-lg dark:border-gray-800 md:size-24" alt="Logo da {{ $store->name }}">
                    @else
                        <div class="flex size-20 shrink-0 items-center justify-center overflow-hidden rounded-3xl border-4 border-white bg-gradient-to-br from-blue-100 to-blue-50 shadow-lg dark:border-gray-800 dark:from-blue-950 dark:to-gray-900 md:size-24">
                            <span class="text-2xl font-black text-[#004aad] dark:text-blue-300">{{ strtoupper(substr($store->name, 0, 2)) }}</span>
                        </div>
                    @endif
                    <div>
                        <span class="mb-1 inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-[0.18em] text-blue-600 dark:text-blue-400"><span class="size-1.5 rounded-full bg-emerald-500"></span> Catálogo online</span>
                        <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white md:text-3xl">{{ $store->name }}</h1>
                       @if($store->description)
                        <div x-data="{ expanded: false }" class="max-w-md mt-1">
                            <p :class="expanded ? '' : 'line-clamp-2'" class="text-sm text-slate-500 dark:text-gray-400 transition-all">{{ $store->description }}</p>
                            @if(mb_strlen($store->description) > 90)
                                <button type="button" @click="expanded = !expanded" class="mt-2 text-xs font-bold text-[#004aad] hover:text-[#0158cd] transition">
                                    <span x-show="!expanded">Ver mais</span>
                                    <span x-show="expanded">Ver menos</span>
                                </button>
                            @endif
                        </div>
                    @endif
                        <div class="flex flex-wrap items-center gap-3 mt-3 text-xs">
                            @if($store->phone)
                                <span class="rounded-full bg-white/80 px-3 py-1.5 font-semibold text-slate-600 shadow-sm ring-1 ring-slate-200/80 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-700">📞 {{ $store->phone }}</span>
                            @endif
                            @if ($store->delivery_fee > 0)
                                <span class="rounded-full bg-blue-100/80 px-3 py-1.5 font-semibold text-[#004aad] dark:bg-blue-900/30 dark:text-blue-300">🚚 Entrega R$ {{ number_format($store->delivery_fee, 2, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @if ($whatsappUrl)
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-500/20 transition-all hover:-translate-y-0.5 hover:bg-emerald-600 hover:shadow-xl">
                        <svg class="size-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2a9.84 9.84 0 00-8.5 14.78L2 22l5.36-1.5A9.96 9.96 0 1012.04 2zm0 17.98a8.1 8.1 0 01-4.13-1.13l-.3-.18-3.18.89.85-3.1-.2-.32a8.04 8.04 0 116.96 3.84zm4.43-6.02c-.24-.12-1.44-.71-1.66-.79-.22-.08-.38-.12-.55.12-.16.24-.62.79-.76.95-.14.16-.28.18-.52.06-.24-.12-1.02-.38-1.94-1.2a7.27 7.27 0 01-1.34-1.67c-.14-.24-.01-.37.11-.49.11-.11.24-.28.36-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.55-1.31-.75-1.8-.2-.47-.4-.4-.55-.41h-.47c-.16 0-.42.06-.64.3-.22.24-.84.82-.84 2s.86 2.32.98 2.48c.12.16 1.69 2.58 4.1 3.62.57.25 1.02.4 1.37.51.58.18 1.1.16 1.51.1.46-.07 1.44-.59 1.64-1.16.2-.57.2-1.06.14-1.16-.06-.1-.22-.16-.46-.28z"/></svg>
                        Falar com a loja
                    </a>
                @endif
            </div>
            <div x-data="{ tab: null }" class="mt-8">
                <div class="flex gap-6 border-b border-slate-200 dark:border-gray-800">
                    <button  @click="tab = tab === 'address' ? null : 'address'" :class="tab === 'address'  ? 'text-[#004aad] border-[#004aad]'  : 'text-slate-500 border-transparent'" class="pb-3 border-b-2 font-bold text-sm transition-all flex items-center gap-2">
                        Endereço
                        <svg  class="w-4 h-4 transition-transform duration-300" :class="tab === 'address' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>
                <div class="pt-6 text-sm">
                    <div x-show="tab === 'address'" x-transition x-cloak>
                        @if($store->address)
                            <div class="bg-slate-50 dark:bg-gray-800 rounded-2xl p-4 text-slate-600 dark:text-gray-300 space-y-1">
                                <p class="font-semibold">{{ $store->address->street }}, {{ $store->address->number }}</p>
                                @if($store->address->complement)
                                    <p>{{ $store->address->complement }}</p>
                                @endif
                                <p>{{ $store->address->neighborhood }}</p>
                                <p>{{ $store->address->city }} - {{ $store->address->state }}</p>
                                <p class="text-sm text-slate-400">CEP: {{ $store->address->zip_code }}</p>
                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($store->address->street . ', ' . $store->address->number . ', ' . $store->address->city) }}" target="_blank" class="inline-flex items-center gap-2 mt-2 text-[#004aad] hover:text-[#0158cd] font-semibold transition">Abrir no mapa</a>
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
                            <img src="{{ $category->img ? Storage::url($category->img) : asset('img/default.png') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
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
                    <x-product-card :product="$promotionalProduct" compact />
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
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>
    @endif

    @if ($whatsappUrl)
        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Falar com a loja pelo WhatsApp" class="fixed bottom-5 right-5 z-40 inline-flex items-center gap-2 rounded-full bg-emerald-500 p-4 font-bold text-white shadow-2xl shadow-emerald-950/30 transition-all hover:-translate-y-1 hover:bg-emerald-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-950 sm:px-5">
            <svg class="size-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2a9.84 9.84 0 00-8.5 14.78L2 22l5.36-1.5A9.96 9.96 0 1012.04 2zm0 17.98a8.1 8.1 0 01-4.13-1.13l-.3-.18-3.18.89.85-3.1-.2-.32a8.04 8.04 0 116.96 3.84zm4.43-6.02c-.24-.12-1.44-.71-1.66-.79-.22-.08-.38-.12-.55.12-.16.24-.62.79-.76.95-.14.16-.28.18-.52.06-.24-.12-1.02-.38-1.94-1.2a7.27 7.27 0 01-1.34-1.67c-.14-.24-.01-.37.11-.49.11-.11.24-.28.36-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.55-1.31-.75-1.8-.2-.47-.4-.4-.55-.41h-.47c-.16 0-.42.06-.64.3-.22.24-.84.82-.84 2s.86 2.32.98 2.48c.12.16 1.69 2.58 4.1 3.62.57.25 1.02.4 1.37.51.58.18 1.1.16 1.51.1.46-.07 1.44-.59 1.64-1.16.2-.57.2-1.06.14-1.16-.06-.1-.22-.16-.46-.28z"/></svg>
            <span class="hidden sm:inline">Falar com a loja</span>
        </a>
    @endif
</x-client_layout>
