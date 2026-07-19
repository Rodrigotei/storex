# Storex

Plataforma SaaS de catálogo digital para comércios.

## 📌 Sobre o projeto

O **Storex** é uma plataforma SaaS desenvolvida para ajudar empresas a criarem um catálogo digital profissional, moderno e responsivo, sem precisar de conhecimento técnico.

A plataforma atende diferentes segmentos, incluindo:

* Lojas
* Lanchonetes

Cada empresa possui:

* Um painel administrativo completo
* Um catálogo público personalizado
* Subdomínio próprio
* Gestão de produtos
* Controle visual moderno e responsivo

---

# 🌐 Estrutura da aplicação

## Landing Page

Página institucional da plataforma:

```txt
storex.app.br
```

Responsável por:

* Apresentação do serviço
* Conversão de clientes
* Cadastro de novos usuários
* Informações sobre planos e funcionalidades

---

## Painel Administrativo

Acesso administrativo do cliente:

```txt
storex.app.br/dashboard
```

Funcionalidades:

* Gestão do catálogo
* Cadastro de produtos
* Upload de imagens
* Configuração da loja
* Controle de informações públicas

---

## Catálogo Público

Catálogo acessível pelos clientes finais:

```txt
{slug}.storex.app.br/loja
```

Exemplo:

```txt
minhaloja.storex.app.br/loja
```

---

# 🚀 Funcionalidades

## Gestão de Catálogo

* Cadastro de produtos
* Organização por categorias
* Upload de imagens
* Controle de disponibilidade
* Destaque de itens

## Personalização

* Nome da empresa
* Logo
* Informações de contato

## Estrutura Multi-tenant

Cada empresa possui:

* Dados isolados
* Catálogo independente
* Subdomínio próprio
* Ambiente separado

## Responsividade

Interface otimizada para:

* Smartphones
* Tablets
* Desktop

## Experiência do usuário

* Interface moderna
* Navegação simples
* Carregamento rápido
* Design focado em conversão

---

# 🛠️ Stack utilizada

## Backend

* PHP
* Laravel

## Frontend

* Blade
* TailwindCSS
* Alpine.js

## Banco de Dados

* MySQL

## Infraestrutura

* VPS Linux
* Nginx
* SSL
* Sistema multi-tenant por subdomínio

---

# 🧱 Arquitetura

## Multi-tenancy

O sistema utiliza arquitetura multi-tenant baseada em subdomínios.

Cada cliente acessa seu catálogo através de um slug único:

```txt
empresa.storex.app.br
```

---

# 🔒 Segurança

O projeto segue boas práticas de segurança:

* Validação de dados
* Proteção CSRF
* Sanitização de inputs
* Isolamento de tenants
* Controle de permissões
* Proteção contra acesso indevido

---

# ⚡ Performance

Estratégias utilizadas:

* Queries otimizadas
* Indexação no banco de dados
* Lazy loading
* Assets otimizados
* Estrutura preparada para cache

---

# 📁 Estrutura do projeto

```bash
app/
bootstrap/
config/
database/
public/
resources/
routes/
storage/
```

---

# 🔧 Instalação

## Clonar repositório

```bash
git clone https://github.com/seuusuario/storex.git
```

## Entrar na pasta

```bash
cd storex
```

## Instalar dependências

```bash
composer install
npm install
```

## Configurar ambiente

```bash
cp .env.example .env
php artisan key:generate
```

## Configurar banco de dados

Edite o arquivo `.env`:

```env
DB_DATABASE=storex
DB_USERNAME=root
DB_PASSWORD=
```

## Rodar migrations

```bash
php artisan migrate
```

## Executar projeto

```bash
php artisan serve
npm run dev
```

---

# 🌍 Deploy

Recomendado:

* VPS Linux
* Ubuntu 22+
* Nginx
* PHP 8.3+
* MySQL 8+
* SSL configurado

---

# 📈 Objetivo do projeto

O Storex foi criado com foco em:

* Simplicidade
* Velocidade
* Conversão
* Facilidade de uso
* Escalabilidade

A proposta é permitir que pequenas e médias empresas tenham presença digital profissional sem complexidade.

---

# 👨‍💻 Autor

Desenvolvido por Rodrigo Teixeira.
