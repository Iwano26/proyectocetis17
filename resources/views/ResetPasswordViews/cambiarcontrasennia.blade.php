<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nueva Contraseña - CETIS 17</title>

    <link rel="stylesheet" href="{{ asset('css/estiloindex.css') }}">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css"> 
    <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    
    {{-- SCRIPT OBLIGATORIO DE RECAPTCHA --}}
    {!! NoCaptcha::renderJs() !!}

    <style>
        #preloader { 
            position: fixed; 
            top: 0; left: 0; 
            width: 100%; height: 100%; 
            background: rgba(255, 255, 255, 0.8); 
            display: none; 
            justify-content: center; 
            align-items: center; 
            z-index: 1000; 
        }
        .spinner-border { width: 3rem; height: 3rem; }
        body { background: none !important; }
        .error-captcha { color: #dc3545; font-size: 80%; margin-top: 5px; display: block; text-align: center; }
    </style>
</head>
<body>
    <div class="fondo">
        <div class="login-box">
            <div class="logo">
                <img src="{{ asset('img/cetis.png') }}" alt="Logo Escuela" />
            </div>
            
            <h2>Nueva Contraseña</h2>
            
            <p class="login-box-msg" style="text-align: center; margin-bottom: 20px;">
                Crea una nueva clave de acceso para tu cuenta.
            </p>

            <form id="registroForm" action="{{ route('password.update') }}" method="post">
                @csrf
                <input type="hidden" name="mytoken" value="{{ $token }}">
                
                <label for="password">Nueva Contraseña</label>
                <input type="password" id="password" class="form-control" name="password" placeholder="Mínimo 8 y máximo 50 caracteres" maxlength="50">
                
                <label for="password_confirmation" style="margin-top: 15px;">Confirmar Contraseña</label>
                <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" placeholder="Repite tu contraseña" maxlength="50">
                
                {{-- CASILLA DEL RECAPTCHA --}}
                <div style="margin-top: 20px; display: flex; flex-direction: column; align-items: center;">
                    {!! NoCaptcha::display() !!}
                    @if ($errors->has('g-recaptcha-response'))
                        <span class="error-captcha">
                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                        </span>
                    @endif
                </div>

                <button type="submit" style="margin-top: 25px;">ACTUALIZAR CONTRASEÑA</button>

                <div class="links" style="text-align: center; margin-top: 15px;">
                    <a href="{{route('login')}}">Cancelar y volver</a>
                </div>
            </form>
        </div>
    </div>

    <div id="preloader">
        <div class="spinner-border text-primary" role="status"></div>
    </div>

    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="../../plugins/jquery-validation/additional-methods.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#registroForm').validate({
                // Ignoramos el token del captcha para evitar conflictos con jQuery Validate local
                ignore: ":hidden, #g-recaptcha-response",
                rules: {
                    password: { 
                        required: true, 
                        minlength: 8,
                        maxlength: 50 // Límite de 50 caracteres en JS
                    },
                    password_confirmation: { 
                        required: true, 
                        equalTo: "#password" 
                    }
                },
                messages: {
                    password: {
                        required: "Ingresa la nueva contraseña",
                        minlength: "Debe tener al menos 8 caracteres",
                        maxlength: "No puede tener más de 50 caracteres"
                    },
                    password_confirmation: {
                        required: "Repite la contraseña para confirmar",
                        equalTo: "Las contraseñas no coinciden"
                    }
                },
                errorElement: 'span',
                errorPlacement: function (error, element) { 
                    error.addClass('invalid-feedback'); 
                    error.insertAfter(element); 
                },
                highlight: function (element) { 
                    $(element).addClass('is-invalid'); 
                },
                unhighlight: function (element) { 
                    $(element).removeClass('is-invalid'); 
                },
                submitHandler: function (form) {
                    $('#preloader').css('display', 'flex');
                    form.submit();
                }
            });
        });
    </script>
</body>
</html>