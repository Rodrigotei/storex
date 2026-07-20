<x-dashboard_layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-[#004aad] dark:text-blue-400">Olá, {{ auth()->user()->name }}!</p>
                <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900 dark:text-white">Vamos colocar sua loja para vender?</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-gray-400">Siga os passos abaixo. Leva poucos minutos.</p>
            </div>
            <a href="{{ $storeUrl }}" target="_blank" rel="noopener" class="inline-flex min-h-12 items-center justify-center gap-2 rounded-2xl bg-[#004aad] px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-900/10 transition hover:bg-[#0158cd] focus:outline-none focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900">
                Ver minha loja
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 3h7m0 0v7m0-7L10 14M5 7v12h12v-5"/></svg>
            </a>
        </div>
    </x-slot>

    @php
        $completedSteps = collect($onboarding)->where('complete', true)->count();
        $progress = (int) round(($completedSteps / count($onboarding)) * 100);
    @endphp

    <section class="rounded-[28px] border border-blue-100 bg-gradient-to-br from-blue-50 to-white p-5 shadow-sm dark:border-blue-950 dark:from-blue-950/40 dark:to-gray-900 sm:p-7" aria-labelledby="first-steps-title">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.18em] text-[#004aad] dark:text-blue-400">Primeiros passos</p>
                <h2 id="first-steps-title" class="mt-2 text-xl font-black text-slate-900 dark:text-white">Prepare seu catálogo</h2>
                <p class="mt-1 text-sm text-slate-600 dark:text-gray-300">{{ $completedSteps }} de {{ count($onboarding) }} etapas concluídas</p>
            </div>
            <span class="text-2xl font-black text-[#004aad] dark:text-blue-400">{{ $progress }}%</span>
        </div>
        <div class="mt-4 h-2 overflow-hidden rounded-full bg-blue-100 dark:bg-gray-800" aria-label="Progresso da configuração: {{ $progress }}%">
            <div class="h-full rounded-full bg-[#004aad] transition-all" style="width: {{ $progress }}%"></div>
        </div>

        <ol class="mt-6 grid gap-3 lg:grid-cols-2">
            @foreach($onboarding as $index => $step)
                <li>
                    <a href="{{ $step['url'] }}" @if($step['external'] ?? false) target="_blank" rel="noopener" @endif class="group flex min-h-24 items-center gap-4 rounded-2xl border p-4 transition {{ $step['complete'] ? 'border-emerald-200 bg-emerald-50/80 dark:border-emerald-900 dark:bg-emerald-950/30' : 'border-slate-200 bg-white hover:border-blue-300 hover:shadow-md dark:border-gray-700 dark:bg-gray-900 dark:hover:border-blue-700' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full font-black {{ $step['complete'] ? 'bg-emerald-600 text-white' : 'bg-blue-100 text-[#004aad] dark:bg-blue-950 dark:text-blue-300' }}">
                            @if($step['complete'])
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="m5 12 4 4L19 6"/></svg>
                            @else
                                {{ $index + 1 }}
                            @endif
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block font-bold text-slate-900 dark:text-white">{{ $step['title'] }}</span>
                            <span class="mt-1 block text-xs leading-5 text-slate-500 dark:text-gray-400">{{ $step['description'] }}</span>
                        </span>
                        <svg class="h-5 w-5 shrink-0 text-slate-300 transition group-hover:translate-x-1 group-hover:text-[#004aad] dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6"/></svg>
                    </a>
                </li>
            @endforeach
        </ol>
    </section>

    <section class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3" aria-label="Resumo da loja">
        <a href="{{ route('dashboard.products.index') }}" class="rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-blue-300 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-gray-400">Produtos</span>
            <strong class="mt-2 block text-3xl font-black text-slate-900 dark:text-white">{{ $totalProducts }}</strong>
        </a>
        <a href="{{ route('dashboard.categories.index') }}" class="rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-blue-300 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-gray-400">Categorias</span>
            <strong class="mt-2 block text-3xl font-black text-slate-900 dark:text-white">{{ $totalCategories }}</strong>
        </a>
        <div class="col-span-2 rounded-2xl border border-slate-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900 sm:col-span-1">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-gray-400">Fotos</span>
            <strong class="mt-2 block text-3xl font-black text-slate-900 dark:text-white">{{ $totalImagesProducts }}</strong>
        </div>
    </section>

    <section class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="rounded-[28px] border border-slate-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900 sm:p-7 lg:col-span-2">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-black text-slate-900 dark:text-white">Produtos recentes</h2>
                    <p class="text-sm text-slate-500 dark:text-gray-400">Confira os últimos itens cadastrados.</p>
                </div>
                <a href="{{ route('dashboard.products.index') }}" class="text-sm font-bold text-[#004aad] hover:underline dark:text-blue-400">Ver todos</a>
            </div>
            <div class="mt-5 space-y-3">
                @forelse($recentProducts as $product)
                    <a href="{{ route('dashboard.products.edit', $product) }}" class="flex items-center gap-4 rounded-2xl bg-slate-50 p-3 transition hover:bg-blue-50 dark:bg-gray-950 dark:hover:bg-blue-950/30">
                        <img src="{{ $product->productImages->first() ? Storage::url($product->productImages->first()->img) : asset('img/default.png') }}" class="h-14 w-14 shrink-0 rounded-xl object-cover" alt="Foto de {{ $product->name }}">
                        <span class="min-w-0 flex-1">
                            <span class="block truncate font-bold text-slate-800 dark:text-gray-100">{{ $product->name }}</span>
                            <span class="block text-xs text-slate-500 dark:text-gray-400">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                        </span>
                        <span class="text-xs font-bold {{ $product->status ? 'text-emerald-600' : 'text-slate-400' }}">{{ $product->status ? 'Publicado' : 'Oculto' }}</span>
                    </a>
                @empty
                    <div class="rounded-2xl border-2 border-dashed border-slate-200 px-5 py-10 text-center dark:border-gray-700">
                        <p class="font-bold text-slate-700 dark:text-gray-200">Sua vitrine ainda está vazia</p>
                        <p class="mt-1 text-sm text-slate-500 dark:text-gray-400">Crie uma categoria e cadastre o primeiro produto.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <aside class="rounded-[28px] border border-slate-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900 sm:p-7">
            <h2 class="text-lg font-black text-slate-900 dark:text-white">Ações rápidas</h2>
            <div class="mt-5 grid gap-3">
                <a href="{{ route('dashboard.products.create') }}" class="inline-flex min-h-12 items-center justify-center rounded-2xl bg-[#004aad] px-4 py-3 text-sm font-bold text-white transition hover:bg-[#0158cd]">+ Cadastrar produto</a>
                <a href="{{ route('dashboard.categories.create') }}" class="inline-flex min-h-12 items-center justify-center rounded-2xl bg-slate-100 px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-200 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">+ Criar categoria</a>
                <button type="button" data-store-url="{{ $storeUrl }}" onclick="copyStoreUrl(this)" class="inline-flex min-h-12 items-center justify-center rounded-2xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-700 transition hover:border-blue-300 hover:text-[#004aad] dark:border-gray-700 dark:text-gray-200">
                    <span>Copiar link da loja</span>
                </button>
            </div>
        </aside>
    </section>

    <script>
        async function copyStoreUrl(button) {
            const label = button.querySelector('span');

            try {
                await navigator.clipboard.writeText(button.dataset.storeUrl);
                label.textContent = 'Link copiado!';
                button.classList.add('border-emerald-500', 'text-emerald-600');
            } catch (error) {
                label.textContent = 'Não foi possível copiar';
            }

            window.setTimeout(() => {
                label.textContent = 'Copiar link da loja';
                button.classList.remove('border-emerald-500', 'text-emerald-600');
            }, 1800);
        }
    </script>
</x-dashboard_layout>
