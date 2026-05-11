<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir senha - StoreX</title>
    <style>
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; margin: 0; padding: 0; }
    .container { max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 40px; overflow: hidden; border: 1px solid #e2e8f0; }
    .header { background: linear-gradient(135deg, #004aad 0%, #0158cd 100%); padding: 40px 20px; text-align: center; }
    .logo { font-size: 28px; font-weight: 900; color: #ffffff; text-transform: uppercase; letter-spacing: -1px; text-decoration: none; }
    .content { padding: 40px; text-align: center; color: #1e293b; }
    .icon { font-size: 50px; margin-bottom: 20px; }
    .title { font-size: 28px; font-weight: 800; color: #004aad; margin-bottom: 15px; }
    .text { font-size: 16px; line-height: 1.7; color: #64748b; margin-bottom: 25px; }
    .info-box { background-color: #f0f7ff; border: 2px solid #004aad; border-radius: 25px; padding: 25px; margin: 30px 0; text-align: left; }
    .label { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #64748b; margin-bottom: 10px; display: block; font-weight: bold; }
    .highlight { font-size: 18px; font-weight: 800; color: #004aad; word-break: break-word; }
    .button { display: inline-block; padding: 18px 40px; background-color: #004aad; color: #ffffff !important; text-decoration: none; font-weight: bold; border-radius: 20px; margin-top: 10px; }
    .security-box { text-align: left; background-color: #f8fafc; border-radius: 20px; padding: 20px; margin-top: 35px; }
    .security-title { font-size: 15px; font-weight: bold; color: #1e293b; margin-bottom: 15px; }
    .security-item { margin-bottom: 12px; font-size: 14px; color: #475569; }
    .security-check { color: #10b981; font-weight: bold; margin-right: 8px; }
    .footer { padding: 30px; text-align: center; font-size: 12px; color: #94a3b8; background-color: #f8f9fa; }
    .footer-text { margin-top: 15px; }
    .link-copy { margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0; font-size: 12px; color: #64748b; line-height: 1.6; word-break: break-all; }
    .link-copy a { color: #004aad; text-decoration: none; }
    @media only screen and (max-width: 600px) {.content { padding: 30px 20px; }.title { font-size: 24px; }.button { display: block; width: 100%; box-sizing: border-box; }}
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <a href="{{ config('app.url') }}" class="logo">StoreX</a>
    </div>
    <div class="content">
        <div class="icon">🔐</div>
        <h1 class="title">Redefinir senha</h1>
        <p class="text">Olá, <strong>{{ $name }}</strong>.<br>Recebemos uma solicitação para redefinir a senha da sua conta StoreX.</p>
        <div class="info-box">
            <span class="label">Segurança da conta</span>
            <div class="highlight">Este link expira em 60 minutos.</div>
        </div>
        <a href="{{ $resetUrl }}" class="button">REDEFINIR MINHA SENHA</a>
        <div class="security-box">
            <div class="security-title">Informações importantes</div>
            <div class="security-item">
                <span class="security-check">✓</span>
                Nunca compartilhe este link com outras pessoas.
            </div>
            <div class="security-item">
                <span class="security-check">✓</span>
                Caso não tenha solicitado a redefinição, ignore este email.
            </div>
            <div class="security-item">
                <span class="security-check">✓</span>
                Sua senha atual continuará funcionando até ser alterada.
            </div>
        </div>
        <div class="link-copy">
            Se o botão acima não funcionar, copie e cole o link abaixo no navegador:<br><br>
            <a href="{{ $resetUrl }}">{{ $resetUrl }}</a>
        </div>
    </div>
    <div class="footer">
        <p>Este email foi enviado automaticamente pela plataforma StoreX.</p>
        <p class="footer-text">&copy; {{ date('Y') }} StoreX. Todos os direitos reservados.</p>
    </div>
</div>
</body>
</html>