<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Recuperação de Palavra-passe</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <h1 style="color: #0d9488; margin: 0;">ErasmusConecta</h1>
    </div>

    <div style="background-color: #ffffff; padding: 30px; border: 1px solid #e5e7eb; border-radius: 8px;">
        <h2 style="color: #111827; margin-top: 0;">Recuperação de Palavra-passe</h2>
        
        <p>Olá <strong>{{ $userName }}</strong>,</p>
        
        <p>Recebeste este email porque solicitaste a recuperação da tua palavra-passe na plataforma ErasmusConecta.</p>
        
        <p>O teu código de verificação é:</p>
        
        <div style="background-color: #f3f4f6; border: 2px dashed #0d9488; padding: 20px; text-align: center; margin: 20px 0; border-radius: 8px;">
            <span style="font-size: 32px; font-weight: bold; color: #0d9488; letter-spacing: 8px; font-family: monospace;">{{ $code }}</span>
        </div>
        
        <p style="color: #6b7280; font-size: 14px;">
            <strong>Importante:</strong> Este código é válido por 15 minutos. Se não solicitaste esta recuperação, podes ignorar este email.
        </p>
        
        <p style="margin-top: 30px;">
            Se não conseguires usar este código, podes solicitar um novo na página de recuperação de palavra-passe.
        </p>
    </div>

    <div style="margin-top: 20px; padding: 20px; background-color: #f8f9fa; border-radius: 8px; text-align: center; color: #6b7280; font-size: 12px;">
        <p style="margin: 0;">© {{ date('Y') }} IPVC - ErasmusConecta</p>
        <p style="margin: 5px 0 0 0;">Este é um email automático, por favor não respondas.</p>
    </div>
</body>
</html>

