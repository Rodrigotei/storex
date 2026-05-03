<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo à StoreX</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 40px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
        .header { background: linear-gradient(135deg, #003378 0%, #0158cd 100%); padding: 40px 20px; text-align: center; }
        .logo { font-size: 28px; font-weight: 900; color: #ffffff; text-transform: uppercase; letter-spacing: -1px; text-decoration: none; }
        .content { padding: 40px; text-align: center; color: #1e293b; }
        .welcome-title { font-size: 24px; font-weight: 800; color: #004aad; margin-bottom: 15px; }
        .text { font-size: 16px; line-height: 1.6; color: #64748b; margin-bottom: 25px; }
        .box-info { background-color: #f1f5f9; border-radius: 25px; padding: 20px; margin-bottom: 30px; border: 1px dashed #004aad; }
        .alert-text { color: #e11d48; font-weight: bold; font-size: 14px; }
        .button { display: inline-block; padding: 18px 40px; background-color: #004aad; color: #ffffff !important; text-decoration: none; font-weight: bold; border-radius: 20px; box-shadow: 0 10px 20px rgba(0,74,173,0.2); transition: all 0.3s; }
        .footer { padding: 30px; text-align: center; font-size: 12px; color: #94a3b8; background-color: #f8f9fa; }
        .url-highlight { color: #004aad; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <a href="#" class="logo">StoreX</a>
        </div>

        <!-- Conteúdo Principal -->
        <div class="content">
            <h1 class="welcome-title">Olá, {{ $name }}!</h1>
            <p class="text">
                Ficamos muito felizes em ter você conosco. Seu cadastro foi realizado com sucesso e sua loja <span class="url-highlight">{{ $storeSlug }}.{{config('app.domain')}}/loja</span> está quase pronta para ir ao ar!
            </p>

            <div class="box-info">
                <p style="margin: 0; font-size: 15px; color: #1e293b;">
                    <strong>Seu próximo passo:</strong><br>
                    Para ativar seu catálogo e começar a receber pedidos, finalize o pagamento da sua assinatura na Cakto.
                </p>
            </div>

            <p class="alert-text">
                ⚠️ LEMBRETE: Use o e-mail <u>{{ $email }}</u> na Cakto para ativação automática.
            </p>

            <a href="https://pay.cakto.com.br/3d77pew_871382" class="button">FINALIZAR PAGAMENTO</a>
            
            <p style="margin-top: 30px; font-size: 13px; color: #94a3b8;">
                Se você já realizou o pagamento, desconsidere este e-mail. A ativação pode levar alguns minutos.
            </p>
        </div>

        <!-- Rodapé -->
        <div class="footer">
            <p><strong>StoreX - Catálogos Digitais Profissionais</strong></p>
            <p>Dúvidas? Chame nosso suporte no WhatsApp: (79) 99682-0727</p>
            <p style="margin-top: 15px;">&copy; 2026 StoreX. Todos os direitos reservados.</p>
        </div>
    </div>
</body>
</html>