<nav class="bg-white dark:bg-gray-950 border-b border-slate-200 dark:border-gray-800 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard.home') }}"  class="text-2xl font-black text-[#004aad] dark:text-white uppercase tracking-tighter">
                        <img src="{{ asset('img/1.png') }}" alt="StoreX" class="w-32 md:w-40 block dark:hidden">
                        <img src="{{ asset('img/2.png') }}" alt="StoreX" class="w-32 md:w-40 hidden dark:flex">
                    </a>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <a href="{{ route('dashboard.categories.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-semibold text-slate-600 dark:text-gray-400 hover:text-[#0158cd] dark:hover:text-white hover:border-[#0158cd] transition-all">
                        Categorias
                    </a>
                    <a href="{{ route('dashboard.products.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-semibold text-slate-600 dark:text-gray-400 hover:text-[#0158cd] dark:hover:text-white hover:border-[#0158cd] transition-all">
                        Produtos
                    </a>
                </div>
            </div>
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center px-4 py-2 text-sm font-medium text-slate-700 dark:text-gray-300 bg-slate-100 dark:bg-gray-900 rounded-full hover:bg-slate-200 dark:hover:bg-gray-800 focus:outline-none transition-all">
                        <span class="mr-2">{{ Auth::user()->name ?? 'Usuário' }}</span>
                        <svg class="fill-current h-4 w-4 opacity-60" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div x-show="open"  @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-800 rounded-2xl shadow-xl py-2 z-50">
                        <a href="{{ route('dashboard.profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-gray-300 hover:bg-slate-100 dark:hover:bg-gray-800 transition">Perfil</a>
                        <div class="border-t border-slate-100 dark:border-gray-800 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                                Sair do Sistema
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="openMobile = !openMobile" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-500 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-gray-900 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div x-show="openMobile" class="sm:hidden border-t border-slate-100 dark:border-gray-800 bg-white dark:bg-gray-950 px-4 pt-2 pb-4 space-y-1">
        <a href="{{ route('dashboard.categories.index') }}" class="block pl-3 pr-4 py-2 rounded-lg text-base font-medium text-slate-700 dark:text-gray-300 hover:bg-slate-100 dark:hover:bg-gray-900">Categorias</a>
        <a href="{{ route('dashboard.products.index') }}" class="block pl-3 pr-4 py-2 rounded-lg text-base font-medium text-slate-700 dark:text-gray-300 hover:bg-slate-100 dark:hover:bg-gray-900">Produtos</a>
        <a href="{{ route('dashboard.profile.edit') }}" class="block pl-3 pr-4 py-2 rounded-lg text-base font-medium text-slate-700 dark:text-gray-300 hover:bg-slate-100 dark:hover:bg-gray-900">Perfil</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left block pl-3 pr-4 py-2 rounded-lg text-base font-medium text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20">Sair</button>
        </form>
    </div>
</nav>
