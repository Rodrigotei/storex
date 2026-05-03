<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sua loja está ativa!</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 40px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
        .header { background: linear-gradient(135deg, #004aad 0%, #0158cd 100%); padding: 40px 20px; text-align: center; }
        .logo { font-size: 28px; font-weight: 900; color: #ffffff; text-transform: uppercase; letter-spacing: -1px; text-decoration: none; }
        .content { padding: 40px; text-align: center; color: #1e293b; }
        .success-icon { font-size: 50px; color: #10b981; margin-bottom: 20px; }
        .welcome-title { font-size: 26px; font-weight: 800; color: #004aad; margin-bottom: 10px; }
        .text { font-size: 16px; line-height: 1.6; color: #64748b; margin-bottom: 25px; }
        
        /* Box do Link da Loja */
        .store-box { background-color: #f0f7ff; border: 2px solid #004aad; border-radius: 25px; padding: 25px; margin: 30px 0; }
        .store-url { font-size: 20px; font-weight: 900; color: #004aad; word-break: break-all; }
        .label { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #64748b; margin-bottom: 10px; display: block; font-weight: bold; }

        .button { display: inline-block; padding: 18px 40px; background-color: #004aad; color: #ffffff !important; text-decoration: none; font-weight: bold; border-radius: 20px; box-shadow: 0 10px 20px rgba(0,74,173,0.2); }
        .footer { padding: 30px; text-align: center; font-size: 12px; color: #94a3b8; background-color: #f8f9fa; }
        .grid-steps { text-align: left; margin: 30px 0; padding: 0; list-style: none; }
        .step-item { margin-bottom: 15px; font-size: 14px; color: #1e293b; display: flex; align-items: center; }
        .step-check { color: #10b981; margin-right: 10px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="#" class="logo">StoreX</a>
        </div>

        <div class="content">
            <div class="success-icon">✅</div>
            <h1 class="welcome-title">Pagamento Confirmado!</h1>
            <p class="text">Excelente notícia, <strong>{{ $name }}</strong>! Sua assinatura foi ativada e sua loja já está pronta para receber pedidos.</p>

            <div class="store-box">
                <span class="label">O endereço da sua loja é:</span>
                <div class="store-url">{{ $slug }}.{{ config('app.domain') }}/loja</div>
            </div>

            <div style="text-align: left; margin: 30px 0;">
                <p style="font-weight: bold; color: #1e293b; margin-bottom: 15px;">O que fazer agora?</p>
                <div class="step-item"><span class="step-check">✓</span> Acesse seu painel administrativo.</div>
                <div class="step-item"><span class="step-check">✓</span> Cadastre seus primeiros produtos ou serviços.</div>
                <div class="step-item"><span class="step-check">✓</span> Compartilhe o link acima com seus clientes.</div>
            </div>

            <a href="{{ route('dashboard.home') }}" class="button">ACESSAR MEU PAINEL</a>
        </div>

        <div class="footer">
            <p><strong>Dica:</strong> Salve o link da sua loja nos favoritos e coloque-o na bio do seu Instagram!</p>
            <p style="margin-top: 15px;">&copy; 2026 StoreX. Todos os direitos reservados.</p>
        </div>
    </div>
</body>
</html>