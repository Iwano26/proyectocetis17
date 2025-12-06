<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar Contraseña - CETIS 17</title>

    <link rel="stylesheet" href="{{ asset('css/estiloindex.css') }}">
    
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css"> 
    <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    
    <style>
        /* Mantenemos el estilo del preloader */
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
        
        /* Aseguramos que el cuerpo use el estilo del fondo definido en estiloindex.css */
        /* Eliminamos 'hold-transition login-page' y usamos la clase 'fondo' del login */
        body {
            background: none !important; 
        }
    </style>
</head>

<body>
    <div class="fondo">
        <div class="login-box">
            
            <div class="logo">
                <img src="{{ asset('img/cetis.png') }}" alt="Logo Escuela" />
            </div>
            
            <h2>Recuperar Contraseña</h2>
            
            <p class="login-box-msg" style="text-align: center; margin-bottom: 20px;">
                Ingresa tu correo institucional para recibir el enlace de recuperación.
            </p>
            
            <form id="registroForm" action="http://cetis17.test/resetpass" method="post">
                @csrf
                @method('PUT')
                
                <label for="correo">Correo institucional</label>
                <input type="text" id="correo" class="form-control" name="correo" placeholder="Ingresa tu correo institucional">
                
                <button type="submit">SOLICITAR RESTABLECIMIENTO</button>

                <div class="links" style="text-align: center; margin-top: 15px;">
                    <a href="{{route('login')}}">Volver a Iniciar Sesión</a>
                </div>
            </form>
            
        </div>
    </div>
    <div id="preloader">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden"> </span>
        </div>
    </div>
    
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../dist/js/adminlte.min.js"></script>
    <script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="../../plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="../../plugins/jquery-validation/additional-methods.min.js"></script>

    <script>
        $(document).ready(function () {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            @if (session('sessionRecuperarContrasennia') == 'false')
                Toast.fire({
                    icon: 'success',
                    title: '{{session('mensaje')}}'
                })
            @endif

            $(function () {
                $.validator.setDefaults({
                    submitHandler: function () {
                        $('#preloader').css('display', 'flex'); // Muestra el preloader
                        $('#registroForm').submit();
                    }
                });

                $('#registroForm').validate({
                    rules: {
                        correo: {
                            required: true,
                            email: true
                        },
                    },
                    messages: {
                        correo: {
                            required: "Ingresa tu correo institucional",
                            email: "Ingresa un correo válido"
                        },
                    },
                    errorElement: 'span',
                    // Adaptamos errorPlacement para que funcione con el estilo del login
                    errorPlacement: function (error, element) {
                        error.addClass('invalid-feedback');
                        // En lugar de closest('.input-group') usamos after(error) para colocar el mensaje debajo del input
                        error.insertAfter(element); 
                    },
                    highlight: function (element, errorClass, validClass) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function (element, errorClass, validClass) {
                        $(element).removeClass('is-invalid');
                    }
                });
            });
        });
    </script>
</body>
</html>