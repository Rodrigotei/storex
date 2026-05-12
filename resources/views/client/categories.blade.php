<x-client_layout>
     <x-slot name="header">
        <div class="flex items-center gap-4">
            <h2 class="font-bold text-2xl text-slate-800 dark:text-white tracking-tight">Categorias</h2>
        </div>
    </x-slot>
    <div class="mb-8">
        <a href="{{ route('client.home', ['tenant' => app('store')->slug]) }}" class="inline-flex items-center text-sm font-bold text-slate-400 hover:text-[#004aad] transition-colors group">
            <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Voltar ao início
        </a>
    </div>
    <section id="produtos" class="py-6">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6 place-items-center">
                @foreach($categories as $category)
                    <a href="{{ route('client.category', ['tenant' => app('store')->slug, 'id' => $category->id]) }}"  class="w-full max-w-[160px] group text-center">
                        <div class="w-full aspect-square bg-white dark:bg-gray-900 rounded-[30px] border border-slate-200 dark:border-gray-800 flex items-center justify-center mb-3 group-hover:border-[#0158cd] group-hover:shadow-lg transition-all overflow-hidden">
                            <img src="{{ asset($category->img ? 'storage/'.$category->img : 'img/default.png') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        </div>
                        <span class="text-sm font-bold text-slate-700 dark:text-gray-300 group-hover:text-[#004aad] transition-colors">{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</x-client_layout>