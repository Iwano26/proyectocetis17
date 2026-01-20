<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cambiar Contraseña - CETIS 17</title>

    <link rel="stylesheet" href="{{ asset('css/estiloindex.css') }}">
    
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css"> 
    <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    
    <style>
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
            
            <h2>Nueva Contraseña</h2>
            
            <p class="login-box-msg" style="text-align: center; margin-bottom: 20px;">
                Ingresa tu nueva clave de acceso y confírmala.
            </p>
            
            <form id="registroForm" action="#" method="post">
                @csrf
                
                <label for="password">Nueva Contraseña</label>
                <input type="password" id="password" class="form-control" name="password" placeholder="Mínimo 8 caracteres">
                
                <label for="password_confirmation" style="margin-top: 15px;">Confirmar Contraseña</label>
                <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" placeholder="Repite tu contraseña">
                
                <button type="submit" style="margin-top: 25px;">CAMBIAR CONTRASEÑA</button>

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
            $(function () {
                $.validator.setDefaults({
                    submitHandler: function (form) {
                        $('#preloader').css('display', 'flex');
                        form.submit();
                    }
                });

                $('#registroForm').validate({
                    rules: {
                        password: {
                            required: true,
                            minlength: 8
                        },
                        password_confirmation: {
                            required: true,
                            equalTo: "#password"
                        },
                    },
                    messages: {
                        password: {
                            required: "Ingresa una nueva contraseña",
                            minlength: "Debe tener al menos 8 caracteres"
                        },
                        password_confirmation: {
                            required: "Confirma tu contraseña",
                            equalTo: "Las contraseñas no coinciden"
                        },
                    },
                    errorElement: 'span',
                    errorPlacement: function (error, element) {
                        error.addClass('invalid-feedback');
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