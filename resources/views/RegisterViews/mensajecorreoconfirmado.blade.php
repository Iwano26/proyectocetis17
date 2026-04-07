<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cuenta Confirmada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="background-color: #f4f4f4;">
    <div class="container vh-100 d-flex align-items-center justify-content-center">
        <div class="card shadow-lg text-center p-5" style="max-width: 500px; border-radius: 20px; border: none;">
            <div class="card-body">
                <h1 style="color: #A71F21; font-size: 60px;">✔</h1>
                <h2 class="fw-bold mb-3">¡Registro Completado!</h2>
                <p class="text-muted mb-4">Tu cuenta en el sistema de <strong>Asesorías CETIS 17</strong> ha sido activada correctamente. Ya puedes iniciar sesión en la plataforma.</p>
                <a href="{{ route('login') }}" class="btn btn-lg w-100" style="background-color: #A71F21; color: white; border-radius: 10px;">
                    IR AL LOGIN
                </a>
            </div>
        </div>
    </div>

    <script>
        Swal.fire({
            title: '¡Excelente!',
            text: 'Tu correo ha sido verificado con éxito.',
            icon: 'success',
            confirmButtonColor: '#A71F21'
        });
    </script>
</body>
</html>