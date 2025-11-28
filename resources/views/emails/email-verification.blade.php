<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação de Email</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 30px; border-radius: 8px; border: 1px solid #e0e0e0;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #0d9488; margin: 0;">ErasmusConecta</h1>
        </div>
        
        <h2 style="color: #333; margin-top: 0;">Verificação de Email</h2>
        
        <p>Olá <strong>{{ $userName }}</strong>,</p>
        
        <p>Obrigado por te registares na plataforma ErasmusConecta!</p>
        
        <p>Para completar o teu registo, por favor verifica o teu endereço de email clicando no botão abaixo:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $verificationUrl }}" 
               style="display: inline-block; background-color: #0d9488; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                Verificar Email
            </a>
        </div>
        
        <p>Ou copia e cola o seguinte link no teu navegador:</p>
        <p style="word-break: break-all; background-color: #f0f0f0; padding: 10px; border-radius: 4px; font-size: 12px;">
            {{ $verificationUrl }}
        </p>
        
        <p style="margin-top: 30px; font-size: 14px; color: #666;">
            <strong>Nota:</strong> Este link expira em 24 horas. Se não verificares o teu email dentro deste período, poderás solicitar um novo link de verificação.
        </p>
        
        <p style="margin-top: 20px; font-size: 14px; color: #666;">
            Se não criaste uma conta na ErasmusConecta, podes ignorar este email.
        </p>
        
        <hr style="border: none; border-top: 1px solid #e0e0e0; margin: 30px 0;">
        
        <p style="font-size: 12px; color: #999; text-align: center; margin: 0;">
            © {{ date('Y') }} IPVC - ErasmusConecta. Todos os direitos reservados.
        </p>
    </div>
</body>
</html>

