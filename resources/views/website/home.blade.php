<!DOCTYPE html>
<html lang="pt-BR" x-data="{ openMobile: false }"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('img/icon-white.png') }}">
    <title>{{ env('APP_NAME', 'StoreX') }}</title>
    @vite('resources/css/app.css')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel=stylesheet href=https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css>
    <script src=https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js></script>
    <style>
        html{
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-gray-950 text-slate-900 dark:text-gray-200 antialiased transition-colors duration-300 min-h-screen">
{{-- ============================================================ --}}
    <header class="w-full flex justify-between items-center px-6 md:px-12 py-8 md:py-5">
        <div>
            <a href="/" class="text-2xl font-black text-[#004aad] dark:text-white uppercase tracking-tighter">
                <img src="{{ asset('img/1.png') }}" alt="StoreX" class="w-32 md:w-50 block dark:hidden">
                <img src="{{ asset('img/2.png') }}" alt="StoreX" class="w-32 md:w-50 hidden dark:flex">
            </a>
        </div>
        <nav class="hidden md:flex items-center gap-6">
            <a href="/" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-semibold text-slate-600 dark:text-gray-400 hover:text-[#0158cd] dark:hover:text-white hover:border-[#0158cd] transition-all">Home</a>
            <a href="/#funcionalidades" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-semibold text-slate-600 dark:text-gray-400 hover:text-[#0158cd] dark:hover:text-white hover:border-[#0158cd] transition-all">Funcionalidades</a>
            <a href="/#planos" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-semibold text-slate-600 dark:text-gray-400 hover:text-[#0158cd] dark:hover:text-white hover:border-[#0158cd] transition-all">Assinatura</a>
            <a href="{{ route('dashboard.home') }}" class="px-10 py-3 rounded-2xl bg-[#004aad] hover:bg-[#0158cd] text-white font-semibold text-sm transition-all shadow-sm hover:shadow-md">Login</a>
        </nav>
        <div class="md:hidden">
            <button id="openMenu" class="text-[30px] text-[#004aad]">☰</button>
            <button id="closeMenu" class="text-[30px] text-[#004aad] hidden">✕</button>
        </div>
    </header>
    <div id="mobileMenu" class="hidden absolute top-[100px] left-0 w-full bg-white dark:bg-gray-950 flex flex-col items-center gap-4 py-5">
         <a href="/" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-semibold text-slate-600 dark:text-gray-400 hover:text-[#0158cd] dark:hover:text-white hover:border-[#0158cd] transition-all">Home</a>
            <a href="/#funcionalidades" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-semibold text-slate-600 dark:text-gray-400 hover:text-[#0158cd] dark:hover:text-white hover:border-[#0158cd] transition-all">Funcionalidades</a>
            <a href="/#planos" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-semibold text-slate-600 dark:text-gray-400 hover:text-[#0158cd] dark:hover:text-white hover:border-[#0158cd] transition-all">Assinatura</a>
        <a href="{{ route('dashboard.home') }}" class="px-10 py-3 rounded-2xl bg-[#004aad] hover:bg-[#0158cd] text-white font-semibold text-sm transition-all shadow-sm hover:shadow-md">Login</a>
    </div>
    <script>
        const openBtn = document.getElementById("openMenu");
        const closeBtn = document.getElementById("closeMenu");
        const menu = document.getElementById("mobileMenu");

        openBtn.addEventListener("click", () => {
            menu.classList.remove("hidden");
            openBtn.classList.add("hidden");
            closeBtn.classList.remove("hidden");
        });

        closeBtn.addEventListener("click", () => {
            menu.classList.add("hidden");
            openBtn.classList.remove("hidden");
            closeBtn.classList.add("hidden");
        });
    </script>
{{-- ============================================================ --}}
    <div class="w-full min-h-[460px] bg-gradient-to-b from-[#003378] to-[#0158cd] dark:from-gray-900 dark:to-gray-900 border-b border-slate-200 dark:border-gray-800 shadow-sm flex flex-col md:flex-row justify-center items-center gap-8 px-3 py-12 md:py-0">
        <div class="w-full max-w-[600px] text-white text-center md:text-left">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">Catálogo online com sistema de pedidos completo.</h1>
            <h2 class="text-lg md:text-xl mt-1">StoreX: A plataforma que conecta seu catálogo de produtos ao seu cliente.</h2>
            <a href="/#planos" class="w-[250px] mx-auto md:mx-0 text-center mt-5 block px-10 py-3 rounded-2xl bg-white text-slate-700 hover:bg-slate-100 hover:text-slate-900 dark:bg-[#004aad] dark:text-white  dark:hover:bg-[#0158cd] dark:hover:text-white font-semibold text-sm transition-all shadow-sm hover:shadow-md">Crie seu catálogo agora</a>            
        </div>
        <div class="w-full max-w-[650px]"><img src="/img/bg-2.png" alt="" class="w-full max-w-[650px] md:max-w-[600px]"></div>
    </div>
{{-- ============================================================ --}}
    <section class="flex flex-col md:flex-row justify-center items-center gap-8 px-5 py-10">
        <div class="hidden md:block"><img src="/img/img-2.png" alt="" class="max-w-[400px]"></div>
        <div class="w-full max-w-[600px]">
            <h2 class="text-2xl md:text-3xl text-[#004aad] dark:text-white mb-6 font-bold">Venda mais rápido: compartilhe o link e deixe os pedidos chegarem!</h2>
            <div class="flex items-start gap-3 mb-3">
                <span class="text-[#004aad] drop-shadow">★</span>
                <p>Diga adeus aos catálogos físicos e desatualizados.</p>
            </div>
                <div class="flex items-start gap-3 mb-3">
                <span class="text-[#004aad] drop-shadow">★</span>
                <p>Não perca mais vendas por falta de um sistema eficiente.</p>
            </div>
            <div class="flex items-start gap-3 mb-3">
                <span class="text-[#004aad] drop-shadow">★</span>
                <p>Evite o trabalho de enviar fotos e preços repetidamente.</p>
            </div>
            <div class="flex items-start gap-3">
                <span class="text-[#004aad] drop-shadow">★</span>
                <p>Esqueça os pedidos perdidos em meio a tantas mensagens.</p>
            </div>
        </div>
    </section>
{{-- ============================================================ --}}
    <section id="funcionalidades" class="w-full min-h-[460px] bg-gradient-to-b from-[#003378] to-[#0158cd] dark:from-gray-900 dark:to-gray-900 border-b border-slate-200 dark:border-gray-800 shadow-sm flex flex-col md:flex-row justify-center items-center gap-8 px-3 py-12">
        <div class="w-full mx-auto text-center px-3">
            <h2 class="text-3xl md:text-4xl text-white mb-5 font-bold">Funcionalidades do StoreX</h2>
            <p class="text-lg md:text-xl text-white mb-10">Descubra como o nosso app pode ajudar seu negócio a crescer!</p>
            <div class="flex flex-wrap justify-center gap-8">
                <div class="max-w-[400px] bg-white p-6 rounded-lg shadow-md hover:-translate-y-2 transition">
                    <i class="fas fa-store text-3xl text-[#004aad] mb-4"></i>
                    <h3 class="text-xl font-semibold text-black mb-2">Catálogo Digital Personalizado</h3>
                    <p class="text-gray-600">Publique seus produtos com descrições, imagens e promoções em um catálogo online acessível a qualquer momento.</p>
                </div>
                <div class="max-w-[400px] bg-white p-6 rounded-lg shadow-md hover:-translate-y-2 transition">
                    <i class="fas fa-tools text-3xl text-[#004aad] mb-4"></i>
                    <h3 class="text-xl font-semibold text-black mb-2">Gestão de Serviços</h3>
                    <p class="text-gray-600">
                        Cadastre e organize seus serviços com descrições detalhadas e preços, facilitando o atendimento e a contratação pelos clientes.
                    </p>
                </div>
                <div class="max-w-[400px] bg-white p-6 rounded-lg shadow-md hover:-translate-y-2 transition">
                    <i class="fas fa-search text-3xl text-[#004aad] mb-4"></i>
                    <h3 class="text-xl font-semibold text-black mb-2">Busca Inteligente</h3>
                    <p class="text-gray-600">Facilite a navegação dos clientes com uma barra de pesquisa que encontra produtos de forma rápida e eficiente.</p>
                </div>
                <div class="max-w-[400px] bg-white p-6 rounded-lg shadow-md hover:-translate-y-2 transition">
                    <i class="fas fa-shopping-cart text-3xl text-[#004aad] mb-4"></i>
                    <h3 class="text-xl font-semibold text-black mb-2">Carrinho e Finalização</h3>
                    <p class="text-gray-600">Permita que os clientes selecionem produtos no carrinho e finalizem suas compras de forma rápida pelo WhatsApp.</p>
                </div>

                <div class="max-w-[400px] bg-white p-6 rounded-lg shadow-md hover:-translate-y-2 transition">
                    <i class="fas fa-tags text-3xl text-[#004aad] mb-4"></i>
                    <h3 class="text-xl font-semibold text-black mb-2">Promoções e Ofertas</h3>
                    <p class="text-gray-600">Crie promoções e destaque produtos populares para atrair mais vendas e fidelizar seus clientes.</p>
                </div>
                <div class="max-w-[400px] bg-white p-6 rounded-lg shadow-md hover:-translate-y-2 transition">
                    <i class="fas fa-database text-3xl text-[#004aad] mb-4"></i>
                    <h3 class="text-xl font-semibold text-black mb-2">Gerenciamento Fácil</h3>
                    <p class="text-gray-600">Acesse todas as informações da sua loja em um painel simples, incluindo produtos, pedidos e clientes.</p>
                </div>
            </div>
        </div>
    </section>
{{-- ============================================================ --}}
    <section id="planos" class=" py-12">
        <div class="px-5 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-[#004aad] dark:text-white mb-5">Transforme sua empresa com um Catálogo Digital completo e fácil de usar</h2>
            <p class="text-lg md:text-xl mb-10">Confira os principais benefícios inclusos no plano:</p>
            <div class="max-w-[1200px] mx-auto flex flex-wrap justify-center items-center gap-8">
                <div class="max-w-[550px]">
                    <img src="/img/bg-1.png" alt="background-app" class="w-full h-full object-contain">
                </div>
                <div class="w-full max-w-[400px] bg-[#fafafa] p-6 py-15 rounded-4xl border-2 border-[#004aad] shadow-[0_0_10px_#004aad] dark:shadow-[0_0_10px_#fff]  hover:-translate-y-1 transition">
                    <h3 class="text-2xl font-bold mb-4 text-gray-950">Assinatura Ilimitada</h3>
                    <p class="text-3xl font-bold text-[#004aad] mb-4">R$ 61,75/mês</p>
                    <p class="text-gray-600 font-bold">Ou R$ 597,00/ano (desconto de 20%)</p>
                    <p class="font-bold underline text-[#004aad] mb-5">menos de R$ 2 por dia</p>
                    <ul class="mb-6 text-left space-y-2">
                        <li class="flex items-center gap-2 text-gray-950"><span class="text-[#004aad]">✔</span> Cadastro de produtos ilimitados</li>
                        <li class="flex items-center gap-2 text-gray-950"><span class="text-[#004aad]">✔</span> Gestão de categorias de produtos</li>
                        <li class="flex items-center gap-2 text-gray-950"><span class="text-[#004aad]">✔</span> Receba pedidos ilimitados</li>
                        <li class="flex items-center gap-2 text-gray-950"><span class="text-[#004aad]">✔</span> Suporte 24 horas</li>
                        <li class="flex items-center gap-2 text-gray-950"><span class="text-[#004aad]">✔</span> Promoções destacadas para produtos</li>
                        <li class="flex items-center gap-2 text-gray-950"><span class="text-[#004aad]">✔</span> Integração com WhatsApp</li>
                        <li class="flex items-center gap-2 text-gray-950"><span class="text-[#004aad]">✔</span> Assinatura 12 meses</li>
                    </ul>
                    <button onclick="contratarPlano()" class="px-6 py-3 bg-[#004aad] border-2 border-[#004aad] text-white rounded-lg font-bold text-lg hover:bg-white hover:text-[#004aad] transition active:scale-95">Contrate agora</button>
                </div>
            </div>
        </div>
    </section>
{{-- ============================================================ --}}
    <section class="border-t-2 border-b border-slate-200 dark:border-gray-800 shadow-sm py-12 px-5 flex flex-col md:flex-row justify-center items-center gap-6">
        <img src="/img/garantia.png" alt="" class="w-[250px] md:w-[300px]">
        <div class="w-full max-w-[600px]">
            <h2 class="text-2xl md:text-3xl font-bold text-[#004aad] text-center">Garantia de Satisfação</h2>
            <p class="py-3 text-[1.05rem] text-justify">Estamos tão confiantes na qualidade do nosso serviço que oferecemos uma garantia de 7 dias para devolução. Se você não estiver satisfeito com nosso catálogo digital, devolvemos o seu dinheiro sem complicações.</p>
            <button onclick="abrirWhatsApp()" class="w-[220px] mx-auto md:mx-0 text-center mt-5 block px-10 py-3 rounded-2xl bg-[#004aad] text-white  hover:bg-[#0158cd] hover:text-white font-semibold text-sm transition-all shadow-sm hover:shadow-md">Saiba Mais</button>
        </div>
    </section>
    <script>
        function abrirWhatsApp() {
            window.open("https://wa.me/5579996820727?text=Olá, gostaria de saber mais sobre o StoreX.");
        }
    </script>
{{-- ============================================================ --}}
    <footer class="bg-gradient-to-b from-[#003378] to-[#0158cd] dark:from-gray-900 dark:to-gray-900 text-white pt-10 relative">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-[1200px] mx-auto px-4 text-center">
            <div>
                <h3 class="text-2xl mb-5">Links Rápidos</h3>
                <ul class="space-y-2 text-lg">
                    <li><a href="#funcionalidades" class="text-gray-300 hover:text-white transition">Funcionalidades</a></li>
                    <li><a href="#planos" class="text-gray-300 hover:text-white transition">Assinatura</a></li>
                    <li><a href="{{ route('dashboard.home') }}" class="text-gray-300 hover:text-white transition">Acesse sua conta</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-2xl mb-5">Fale Conosco</h3>
                <p class="text-lg">Email: storex.app.br@gmail.com</p>
                <p class="text-lg mb-4">Telefone: (79) 99682-0727</p>
            </div>
        </div>
        <div class="py-10 text-center text-slate-400 text-sm">
            &copy; {{ date('Y') }} StoreX - Todos os direitos reservados.
        </div>
    </footer>
{{-- ============================================================ --}}
<script>
    function contratarPlano(){
        window.location.href = '/register';
    }
</script>
</body>
</html>