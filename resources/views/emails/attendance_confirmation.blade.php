<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirmación de Asistencia</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .header { background-color: #1B396A; /* Azul TecNM */ color: #ffffff; padding: 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px; text-align: center; color: #333333; }
        .btn { display: inline-block; background-color: #1B396A; color: #ffffff; padding: 15px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 20px; font-size: 16px; }
        .btn:hover { background-color: #142c52; }
        .footer { background-color: #eeeeee; padding: 15px; text-align: center; font-size: 12px; color: #777777; }
        .warning { color: #e74c3c; font-size: 14px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Tecnológico de Salina Cruz</h1>
        </div>

        <div class="content">
            <h2>Hola, {{ $name }}</h2>
            <p>Hemos recibido una solicitud para registrar tu asistencia en el evento académico.</p>
            
            <p>Para confirmar que realmente estás presente, por favor haz clic en el siguiente botón:</p>

            <a href="{{ $link }}" class="btn">CONFIRMAR ASISTENCIA AHORA</a>

            <p class="warning">
                ⚠️ Tienes <strong>15 minutos</strong> para confirmar antes de que este enlace expire.
            </p>
            
            <p>Si tú no solicitaste esto, ignora este correo.</p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Plataforma de Gestión de Eventos Académicos - CONSTANCIAB.<br>
            Este es un mensaje automático, no respondas a este correo.
        </div>
    </div>
</body>
</html>