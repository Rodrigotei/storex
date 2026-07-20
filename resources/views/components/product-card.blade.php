@props(['product', 'compact' => false])

@php
    $hasPromotion = $product->promotional_price
        && (float) $product->promotional_price < (float) $product->price;
    $discount = $hasPromotion && (float) $product->price > 0
        ? round((1 - ((float) $product->promotional_price / (float) $product->price)) * 100)
        : null;
    $productUrl = route('client.product', [
        'tenant' => app('store')->slug,
        'id' => $product->id,
    ]);
    $imageUrl = $product->productImages->first()
        ? Storage::url($product->productImages->first()->img)
        : asset('img/default.png');
@endphp

<article @class([
    'group relative flex h-full flex-col overflow-hidden rounded-[28px] border border-slate-200/80 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-950/10 dark:border-gray-800 dark:bg-gray-900 dark:hover:border-blue-500/30',
    'w-[210px] shrink-0' => $compact,
])>
    <a href="{{ $productUrl }}" class="relative block aspect-square overflow-hidden bg-slate-100 dark:bg-gray-950" aria-label="Ver {{ $product->name }}">
        <img
            src="{{ $imageUrl }}"
            alt="{{ $product->name }}"
            loading="lazy"
            decoding="async"
            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
        >

        @if ($hasPromotion)
            <span class="absolute left-3 top-3 inline-flex items-center rounded-full bg-red-500 px-3 py-1 text-[10px] font-black uppercase tracking-wider text-white shadow-lg shadow-red-950/20">
                {{ $discount }}% off
            </span>
        @endif
    </a>

    <div class="flex flex-1 flex-col p-4 sm:p-5">
        @if ($product->category)
            <p class="mb-1 text-[10px] font-black uppercase tracking-[0.16em] text-blue-600 dark:text-blue-400">
                {{ $product->category->name }}
            </p>
        @endif

        <a href="{{ $productUrl }}" class="line-clamp-2 min-h-10 text-sm font-bold leading-5 text-slate-800 transition-colors hover:text-[#004aad] dark:text-white dark:hover:text-blue-400 sm:text-base">
            {{ $product->name }}
        </a>

        <div class="mt-auto flex items-end justify-between gap-3 pt-5">
            <div class="min-w-0">
                @if ($hasPromotion)
                    <span class="block text-xs text-slate-400 line-through">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                    <span class="block text-lg font-black text-red-500 sm:text-xl">R$ {{ number_format($product->promotional_price, 2, ',', '.') }}</span>
                @else
                    <span class="block text-lg font-black text-slate-900 dark:text-white sm:text-xl">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                @endif
            </div>

            <a
                href="{{ $productUrl }}"
                aria-label="Escolher {{ $product->name }}"
                class="inline-flex size-11 shrink-0 items-center justify-center rounded-2xl bg-[#004aad] text-white shadow-lg shadow-blue-500/20 transition-all hover:bg-[#0158cd] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#0158cd] focus-visible:ring-offset-2 active:scale-90 dark:focus-visible:ring-offset-gray-900"
            >
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 5v14m-7-7h14" stroke-width="2.2" stroke-linecap="round" />
                </svg>
            </a>
        </div>
    </div>
</article>
