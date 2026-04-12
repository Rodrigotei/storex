<x-client_layout>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{ activeImg: '{{ asset('storage/' . ($service->serviceImages->first()->img ?? 'images/default.png')) }}' }">
        <div class="mb-8">
            <a href="{{ route('client.home') }}" class="inline-flex items-center text-sm font-bold text-slate-400 hover:text-[#004aad] transition-colors group">
                <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Voltar aos serviços
            </a>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div class="space-y-6">
                <div class="aspect-video bg-white dark:bg-gray-900 rounded-[40px] overflow-hidden border border-slate-200 dark:border-gray-800 shadow-sm group">
                    <img :src="activeImg" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $service->name }}">
                </div>
                @if(isset($service->serviceImages) && $service->serviceImages->count() > 0)
                    <div class="flex gap-4 overflow-x-auto pb-2 no-scrollbar">
                        @foreach($service->serviceImages as $item)
                            <button @click="activeImg = '{{ asset('storage/' . $item->img) }}'"  class="relative flex-none w-32 h-20 rounded-[20px] overflow-hidden border-2 transition-all duration-300" :class="activeImg === '{{ asset('storage/' . $item->img) }}' ? 'border-[#004aad] ring-4 ring-blue-500/10' : 'border-transparent opacity-60 hover:opacity-100'"><img src="{{ asset('storage/' . $item->img) }}" class="w-full h-full object-cover"></button>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="flex flex-col">
                <div class="mb-6">
                    <span class="text-xs font-bold text-[#004aad] uppercase tracking-widest px-4 py-1.5 bg-blue-50 dark:bg-blue-500/10 rounded-full italic"><i class="fas fa-tools mr-1"></i> Serviço Especializado</span>
                    <h1 class="text-4xl font-black text-slate-800 dark:text-white mt-4 tracking-tight leading-tight">{{ $service->name }}</h1>
                </div>

                <div class="mb-8 flex items-baseline gap-3">
                    <span class="text-4xl font-black text-[#004aad] dark:text-blue-400">
                       @if ($service->promotional_price && $service->promotional_price < $service->price)
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400 line-through">
                                    R$ {{ number_format($service->price, 2, ',', '.') }}
                                </span>
                                <span class="text-4xl font-black text-red-500">
                                    R$ {{ number_format($service->promotional_price, 2, ',', '.') }}
                                </span>
                            </div>
                        @else
                            <span class="text-4xl font-black text-[#004aad] dark:text-blue-400">
                                R$ {{ number_format($service->price, 2, ',', '.') }}
                            </span>
                        @endif
                    </span>
                </div>
                <div class="bg-slate-50 dark:bg-gray-800/30 rounded-[30px] p-8 mb-8 border border-slate-100 dark:border-gray-800">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-3 uppercase tracking-wider flex items-center"><span class="w-8 h-[2px] bg-[#004aad] mr-3"></span> Detalhes do Serviço</h3>
                    <pre class="text-slate-600 font-sans dark:text-gray-400 leading-relaxed text-lg">{{ $service->description ?? 'Entre em contato para saber mais detalhes sobre este serviço.' }}</pre>
                </div>
                <form action="{{ route('client.service.finish') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="service_id" value="{{ $service->id }}">
                    <label class="block text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider mb-3 ml-1">Preferência de Data/Hora</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider mb-3 ml-1">Data</label>
                            <input type="date" name="date" class="w-full px-5 py-4 bg-white dark:bg-gray-950 border border-slate-200 dark:border-gray-800 text-slate-900 dark:text-gray-200 rounded-[20px] focus:ring-2 focus:ring-[#0158cd] outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider mb-3 ml-1">Horário</label>
                            <input type="time" name="time" class="w-full px-5 py-4 bg-white dark:bg-gray-950 border border-slate-200 dark:border-gray-800 text-slate-900 dark:text-gray-200 rounded-[20px] focus:ring-2 focus:ring-[#0158cd] outline-none transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider mb-3 ml-1">Informações Adicionais</label>
                        <textarea name="message" rows="4" placeholder="Descreva brevemente sua necessidade..." class="w-full px-5 py-4 bg-white dark:bg-gray-950 border border-slate-200 dark:border-gray-800 text-slate-900 dark:text-gray-200 rounded-[25px] focus:ring-2 focus:ring-[#0158cd] outline-none transition-all resize-none"></textarea>
                    </div>
                    <div class="hidden" id="fieldName">
                        <label class="block text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider mb-3 ml-1">Nome</label>
                        <input class="w-full px-5 py-4 bg-white dark:bg-gray-950 border border-slate-200 dark:border-gray-800 text-slate-900 dark:text-gray-200 rounded-[20px] focus:ring-2 focus:ring-[#0158cd] outline-none transition-all" type="text" name="name" placeholder="Informe seu nome">
                    </div>
                    <div class="pt-4">
                        <button type="submit" id="btn-submit" class="hidden w-full bg-[#004aad] hover:bg-[#0158cd] text-white font-bold py-6 px-10 rounded-full shadow-2xl shadow-blue-500/30 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-4 group text-lg"><i class="fab fa-whatsapp text-2xl transition-transform group-hover:rotate-12"></i>Solicitar Serviço</button>
                        <button type="button" id="btn-finish" onclick="openFieldName()" class="w-full bg-[#004aad] hover:bg-[#0158cd] text-white font-bold py-6 px-10 rounded-full shadow-2xl shadow-blue-500/30 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-4 group text-lg"><i class="fab fa-whatsapp text-2xl transition-transform group-hover:rotate-12"></i>Finalizar</button>
                        <p class="text-center text-xs text-slate-400 mt-4 italic">Ao solicitar, iniciaremos um atendimento via WhatsApp.</p>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <div class="fixed bottom-5 right-5 z-[60] flex flex-col gap-3">
        @if (session('success'))
            <div class="message bg-white dark:bg-gray-900 border-l-4 border-green-500 shadow-2xl rounded-2xl p-5 flex items-center min-w-[320px] animate-bounce-subtle">
                <div class="p-2 bg-green-100 dark:bg-green-500/20 rounded-full mr-3 text-green-600 italic font-black">!</div>
                <p class="text-sm font-bold text-slate-700 dark:text-gray-200">{{ session('success') }}</p>
            </div>
        @endif
    </div>
    <script>
        function openFieldName(){
            const field = document.querySelector('#fieldName');
            field.classList.remove('hidden');
            const btnFinish = document.querySelector('#btn-finish');
            const btnSubmit = document.querySelector('#btn-submit');
            btnFinish.classList.add('hidden');
            btnSubmit.classList.remove('hidden');
        }
        setTimeout(() => {
            document.querySelectorAll('.message').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                setTimeout(() => el.remove(), 600);
            });
        }, 4000);
    </script>
</x-client_layout>