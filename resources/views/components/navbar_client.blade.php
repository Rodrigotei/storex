<nav class="sticky top-0 z-50 bg-white/80 dark:bg-gray-950/80 backdrop-blur-md border-b border-slate-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
            <a href="{{ route('client.home', ['tenant' => app('store')->slug]) }}" class="text-2xl font-black text-[#004aad]  dark:text-white uppercase tracking-tighter">StoreX</a>
            <!-- Desktop (input visível) -->
            <div class="hidden md:flex flex-1 justify-center px-6">
                <form action="{{ route('client.search', ['tenant' => app('store')->slug]) }}" method="POST" class="relative w-full max-w-md mt-5">
                    @csrf
                    <input type="text" name="search" placeholder="O que você procura hoje?" class="w-full pl-12 pr-4 py-3 bg-slate-100 dark:bg-gray-900 rounded-full focus:ring-2 focus:ring-[#0158cd] focus:bg-white dark:focus:bg-gray-800 outline-none text-sm">
                    <button type="submit"><svg class="w-5 h-5 absolute left-4 top-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2"/></svg></button>
                </form>
            </div>
            <!-- Mobile (botão que abre modal) -->
            <div class="flex-1 flex justify-center md:hidden">
                <button onclick="openSearchModal()" class="p-3 bg-slate-100 dark:bg-gray-900 rounded-full hover:bg-[#004aad] hover:text-white transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2"/></svg></button>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('client.cart', ['tenant' => app('store')->slug]) }}" class="relative p-3 bg-slate-100 dark:bg-gray-900 rounded-full hover:bg-[#004aad] hover:text-white transition-all group">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    @if(session('cart_count'))
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full border-2 border-white dark:border-gray-950">
                            {{ session('cart_count') }}
                        </span>
                    @endif
                </a>
            </div>
        </div>
    </div>
</nav>

<div id="searchModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-[999] hidden items-center justify-center md:hidden">
    <div class="w-full max-w-xl mx-4">
        <!-- fechar -->
        <div class="flex justify-end mb-3">
            <button onclick="closeSearchModal()" class="text-white text-2xl font-bold">✕</button>
        </div>
        <!-- input -->
        <form action="{{ route('client.search', ['tenant' => app('store')->slug]) }}" method="POST" class="relative">
            @csrf

            <input type="text" name="search" autofocus placeholder="O que você procura?" class="w-full pl-5 pr-12 py-4 rounded-full text-lg bg-white dark:bg-gray-900 text-black dark:text-white focus:ring-2 focus:ring-[#004aad] outline-none">
            <button type="submit"><svg class="w-6 h-6 absolute right-4 top-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2"/></svg></button>
        </form>
    </div>
</div>

<script>
function openSearchModal() {
    const modal = document.getElementById('searchModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeSearchModal() {
    const modal = document.getElementById('searchModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
document.addEventListener('click', function(e) {
    const modal = document.getElementById('searchModal');
    if (modal && e.target === modal) {
        closeSearchModal();
    }
});
</script>