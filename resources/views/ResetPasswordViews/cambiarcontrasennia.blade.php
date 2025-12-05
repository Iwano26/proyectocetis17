<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema de Almacén</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style y necesario para el mensaje Toast-->
  <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">

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
    </style>

</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <p class="h1"><b>Almacén</b>PLUS</p>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Ahora puedes escribir una nueva cotraseña</p>
      <form id="registroForm" action="{{route("password.update")}}" method="post">
          @csrf
          @method('PUT')

          <input type="hidden" name="mytoken" value="{{$token}}">
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="contrasennia" id="contrasennia"  placeholder="Contraseña, min 8 [a-z|0-9|@#$%&]">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="recontrasennia"  placeholder="Confirma tu Contraseña">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" id="enviarFormulario" class="btn btn-primary btn-block">Cambiar Contraseña</button>
          </div>
        </div>
      </form>

      <p class="mt-3 mb-1">
        <a href="{{route("login")}}" class="text-center">Iniciar Sesión</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<div id="preloader">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden"> </span>
    </div>
</div>

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2y necesario para el mensaje Toast -->
<script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- jquery-validation -->
<script src="../../plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../../plugins/jquery-validation/additional-methods.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>

<script>
    $(document).ready(function() {
        const regex = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,}$/;

        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        $.validator.addMethod(
            //En jQuery Validation, un campo se considera opcional si no tiene el atributo required
            // o si se cumple alguna otra condición que lo haga opcional.
            "regexContrasennia",
            function(value, element) {
                //si optional es true indica que elcomponente contiene un required y el JS de la librearía se encargara de la validación
                //por otro lado regex se encargará
                return this.optional(element) || regex.test(value);
            }
        );

        $.validator.addMethod(
            "compararContrasennias",
            function(value, element) {
                return value == $("#contrasennia").val();
            }
        );

        $(function () {
            $.validator.setDefaults({
                submitHandler: function () {
                    $('#preloader').css('display', 'flex'); // Muestra el preloader
                    $('#registroForm').submit();
                }
            });

            $('#registroForm').validate({
                rules: {
                    contrasennia: {
                        required: true,
                        minlength: 5,
                        regexContrasennia: true
                    },
                    recontrasennia: {
                        required: true,
                        minlength: 5,
                        compararContrasennias: true,
                    },
                },
                messages: {
                    contrasennia: {
                        required: "Favor de ingresar una contraseña",
                        minlength: "La contraseña debe tener al menos 5 caracteres",
                        regexContrasennia: "La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula y un número.",
                    },
                    recontrasennia:{
                        required: "Favor de ingresar una contraseña",
                        minlength: "La contraseña debe tener al menos 5 caracteres",
                        compararContrasennias: "Las contraseñas no coinciden."
                    },
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.input-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });


            //Arroba if es una sentencia de laravel y verifica la existencia de la session flash
            //que proviene de la función store del ApiUserController
            @if (session('sessionCambiarContrasennia') == 'false')
            //Código Java Script para activar el mensaje Toast
            Toast.fire({
                icon: 'error',
                title: '{{session('mensaje')}}'
            })
            @endif

        });
    });
</script>

</body>
</html>
