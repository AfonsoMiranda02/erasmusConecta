<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Verificação</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 30px; border-radius: 8px; border: 1px solid #e0e0e0;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #0d9488; margin: 0;">ErasmusConecta</h1>
        </div>
        
        <h2 style="color: #333; margin-top: 0;">Código de Verificação de Email</h2>
        
        <p>Olá <strong>{{ $userName }}</strong>,</p>
        
        <p>Obrigado por te registares na plataforma ErasmusConecta!</p>
        
        <p>Para completar o teu registo, utiliza o seguinte código de verificação:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <div style="display: inline-block; background-color: #0d9488; color: #ffffff; padding: 20px 40px; border-radius: 8px; font-size: 32px; font-weight: bold; letter-spacing: 5px;">
                {{ $code }}
            </div>
        </div>
        
        <p style="margin-top: 30px; font-size: 14px; color: #666;">
            <strong>Nota:</strong> Este código expira em 15 minutos. Se não completares o registo dentro deste período, poderás solicitar um novo código.
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

