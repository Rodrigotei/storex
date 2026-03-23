<x-client_layout>
     <x-slot name="header">
        <div class="flex items-center gap-4">
            <h2 class="font-bold text-2xl text-slate-800 dark:text-white tracking-tight">Categorias</h2>
        </div>
    </x-slot>
    <section id="produtos" class="py-6">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6 place-items-center">
                @foreach($categories as $category)
                    <a href="{{ route('client.category', $category->id) }}"  class="w-full max-w-[160px] group text-center">
                        <div class="w-full aspect-square bg-white dark:bg-gray-900 rounded-[30px] border border-slate-200 dark:border-gray-800 flex items-center justify-center mb-3 group-hover:border-[#0158cd] group-hover:shadow-lg transition-all overflow-hidden">
                            <img src="{{ asset($category->img ? 'storage/'.$category->img : 'storage/images/default.png') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        </div>
                        <span class="text-sm font-bold text-slate-700 dark:text-gray-300 group-hover:text-[#004aad] transition-colors">{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</x-client_layout>