<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Usuarios | CETIS 17</title>

  <!-- Usando Bootstrap y SweetAlert2 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <style>
    /* Estilos base para la interfaz de administración */
    body {
        background-color: #f8f9fa;
    }
    .card-header.bg-danger {
        background-color: #dc3545 !important;
    }
    .error-message {
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 0.25rem;
    }
    /* Estilo para manejar la validación del correo institucional */
    .was-validated input:invalid, .was-validated select:invalid {
        border-color: #dc3545;
    }
  </style>
</head>
<body>
  
  <div class="main-content container mt-4 mb-5">
    <h2 class="text-danger text-center mb-4">Gestión de Usuarios</h2>
    
    {{-- BLOQUE DE MENSAJES DE SESIÓN (ÉXITO/ERROR) --}}
    @if(session('mensaje'))
    {{-- La clase de alerta depende de si fue éxito (true) o error (false) --}}
    <div class="alert alert-{{ session('sessionInsertado') == 'true' || session('sessionEliminado') == 'true' ? 'success' : 'danger' }}" role="alert">
        {{ session('mensaje') }}
    </div>
    @endif

    <div class="row">
      <!-- FORMULARIO DE REGISTRO/CREACIÓN/EDICIÓN A LA IZQUIERDA (Ahora col-lg-5) -->
      <div class="col-12 col-lg-5 mb-4">
        <div class="card shadow">
          <div class="card-header bg-danger text-white">
            <span id="formTitle">Registro de Usuario</span>
          </div>
          <div class="card-body">
            <!-- CONEXIÓN: action apunta a la ruta nombrada 'gestionusuario.store' por defecto -->
            <form method="POST" action="{{ route('gestionusuario.store') }}" id="registroForm" class="needs-validation" novalidate>
              @csrf 
              
              <!-- CAMPO OCULTO PARA SIMULAR PUT/PATCH O DELETE (Por defecto es POST para store) -->
              <input type="hidden" name="_method" value="POST" id="methodField">

              <div class="mb-3">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre" class="form-control" value="{{ old('nombre') }}" required>
              </div>
              <div class="mb-3">
                <label for="apellidoPa">Apellido Paterno</label>
                <input type="text" name="apellidoPa" id="apellidoPa" placeholder="Apellido paterno" class="form-control" value="{{ old('apellidoPa') }}" required>
              </div>
              <div class="mb-3">
                <label for="apellidoMa">Apellido Materno</label>
                <input type="text" name="apellidoMa" id="apellidoMa" placeholder="Apellido materno" class="form-control" value="{{ old('apellidoMa') }}" required>
              </div>
              <div class="mb-3">
                <label for="correo">Correo Electrónico</label>
                <input type="email" name="correo" id="correo" class="form-control" placeholder="usuario@cetis17.edu.mx" value="{{ old('correo') }}" required pattern="^[a-zA-Z0-9._%+-]+@cetis17\.edu\.mx$">
                <div class="invalid-feedback">
                    El correo debe ser institucional (@cetis17.edu.mx).
                </div>
                <!-- Mostrar error de validación de Laravel -->
                @error('correo')
                    <div class="error-message">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="telefono">Teléfono</label>
                <input type="tel" name="telefono" id="telefono" placeholder="10 dígitos" class="form-control" value="{{ old('telefono') }}" required pattern="\d{10}" maxlength="10">
                <div class="invalid-feedback">
                    El teléfono debe contener 10 dígitos.
                </div>
              </div>
              <div class="mb-3">
                <label for="rol">Rol</label>
                <select name="rol" id="rol" class="form-select" required>
                  <option value="" disabled selected>Seleccione un rol</option>
                  <option value="Administrador" {{ old('rol') == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                  <option value="Maestro" {{ old('rol') == 'Maestro' ? 'selected' : '' }}>Maestro</option>
                  <option value="Estudiante" {{ old('rol') == 'Estudiante' ? 'selected' : '' }}>Estudiante</option>
                </select>
                <div class="invalid-feedback">
                    Seleccione un rol.
                </div>
              </div>
              <div class="mb-3">
                <label for="pass">Contraseña</label>
                <!-- Al editar, la contraseña es opcional. El 'required' solo se activa si no está vacío, pero lo quitamos aquí por JS. -->
                <input type="password" name="contrasennia" id="pass" placeholder="Contraseña (Dejar vacío para no cambiar)" class="form-control">
                <div class="form-text">Deje vacío si no desea cambiar la contraseña al editar.</div>
                
                <!-- Mostrar error de validación de Laravel -->
                @error('contrasennia')
                    <div class="error-message">{{ $message }}</div>
                @enderror
              </div>
              <div class="text-end d-flex justify-content-end align-items-center">
                <!-- Botón de Cancelar (Inicialmente oculto) -->
                <button type="button" class="btn btn-secondary me-2 d-none" id="cancelarEdicionBtn" onclick="cancelarEdicion()">Cancelar Edición</button>
                
                <button type="submit" class="btn btn-danger" id="submitBtn">Registrar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      
      <!-- TABLA DE VISUALIZACIÓN DE USUARIOS (Ahora col-lg-7) -->
      <div class="col-12 col-lg-7">
        <div class="card shadow">
          <div class="card-header bg-danger text-white">
            Usuarios Registrados
          </div>                  
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-bordered table-striped text-center align-middle m-0">
                <thead class="table-light">
                  <tr>
                    <th>Nombre Completo</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody> 
                    {{-- BUCLE DINÁMICO: Itera sobre la colección de usuarios ($usuarios) --}}
                    @foreach ($usuarios ?? [] as $usuario) 
                    <tr>
                        <td>{{ $usuario->nombre }} {{ $usuario->apellidoPa }} {{ $usuario->apellidoMa }}</td>
                        <td>{{ $usuario->correo }}</td>
                        <td>{{ $usuario->telefono }}</td>
                        <td>{{ $usuario->rol }}</td>
                        <td>
                            {{-- CONEXIÓN EDITAR: Pasa la URL de actualización y los datos del usuario a la función JS --}}
                            <a href="#" class="btn btn-warning btn-sm text-white" 
                                onclick="iniciarEdicion(event, 
                                    '{{ route('gestionusuario.update', ['gestionusuario' => $usuario->correo]) }}', 
                                    {
                                        correo: '{{ $usuario->correo }}', 
                                        nombre: '{{ $usuario->nombre }}', 
                                        apellidoPa: '{{ $usuario->apellidoPa }}', 
                                        apellidoMa: '{{ $usuario->apellidoMa }}', 
                                        telefono: '{{ $usuario->telefono }}', 
                                        rol: '{{ $usuario->rol }}'
                                    }
                                )">Editar</a>

                            {{-- CONEXIÓN DELETE --}}
                            <a href="#" class="btn btn-danger btn-sm" 
                                onclick="mostrarAlertaEliminar(event, '{{ $usuario->correo }}', '{{ route('gestionusuario.destroy', ['gestionusuario' => $usuario->correo]) }}')">Eliminar</a>
                        </td>
                    </tr> 
                    @endforeach
                    @if (empty($usuarios) || count($usuarios) == 0)
                      <tr>
                        <td colspan="5">No hay usuarios registrados.</td>
                      </tr>
                    @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Variables globales para guardar el estado inicial del formulario
    const defaultAction = "{{ route('gestionusuario.store') }}";
    const defaultMethod = "POST";
    const correoPattern = '^[a-zA-Z0-9._%+-]+@cetis17\.edu\.mx$';

    /**
     * Función para iniciar el modo de edición y pre-cargar los datos del usuario.
     * @param {Event} event Evento de clic.
     * @param {string} updateUrl URL de la ruta 'gestionusuario.update'.
     * @param {object} userData Objeto con los datos del usuario.
     */
    function iniciarEdicion(event, updateUrl, userData) {
      event.preventDefault();
      
      // 1. Llenar el formulario con los datos del usuario
      document.getElementById('correo').value = userData.correo;
      document.getElementById('nombre').value = userData.nombre;
      document.getElementById('apellidoPa').value = userData.apellidoPa;
      document.getElementById('apellidoMa').value = userData.apellidoMa;
      document.getElementById('telefono').value = userData.telefono;
      document.getElementById('rol').value = userData.rol;
      
      // 2. Deshabilitar campos clave para la edición
      document.getElementById('correo').setAttribute('readonly', 'readonly');
      document.getElementById('correo').removeAttribute('pattern'); // Eliminar patrón de validación al editar
      document.getElementById('pass').value = ''; // Limpiar campo de contraseña
      
      // 3. Cambiar la acción y el método del formulario para la actualización (PUT/PATCH)
      document.getElementById('registroForm').action = updateUrl;
      document.getElementById('methodField').value = 'PUT';
      
      // 4. Actualizar textos de botones e interfaz
      document.getElementById('formTitle').textContent = 'Edición de Usuario: ' + userData.correo;
      document.getElementById('submitBtn').textContent = 'Actualizar Datos';
      document.getElementById('cancelarEdicionBtn').classList.remove('d-none'); // Mostrar botón de cancelar
      document.getElementById('registroForm').classList.remove('was-validated'); // Limpiar validación
      
      // Scroll hacia el formulario para mejor UX
      document.getElementById('registroForm').scrollIntoView({ behavior: 'smooth' });
    }

    /**
     * Función para revertir el formulario al modo de registro (Creación).
     */
    function cancelarEdicion() {
      // 1. Resetear el formulario y limpiar estilos de validación
      document.getElementById('registroForm').reset();
      document.getElementById('registroForm').classList.remove('was-validated');
      
      // 2. Restaurar la acción y el método por defecto (POST para store)
      document.getElementById('registroForm').action = defaultAction;
      document.getElementById('methodField').value = defaultMethod;
      
      // 3. Restaurar campos
      document.getElementById('correo').removeAttribute('readonly');
      document.getElementById('correo').setAttribute('pattern', correoPattern); // Restaurar patrón de correo
      
      // 4. Restaurar botones e interfaz
      document.getElementById('formTitle').textContent = 'Registro de Usuario';
      document.getElementById('submitBtn').textContent = 'Registrar';
      document.getElementById('cancelarEdicionBtn').classList.add('d-none'); // Ocultar botón de cancelar
    }

    // FUNCIÓN: Simula el envío DELETE para Laravel usando un formulario oculto.
    function mostrarAlertaEliminar(event, correo, deleteUrl) {
      event.preventDefault(); // Detiene el evento de clic por defecto
      Swal.fire({
        title: '¿Estás seguro?',
        text: `Se eliminará al usuario: ${correo}. ¡Esta acción es irreversible!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
            // Crea un formulario oculto para enviar la petición POST con el método DELETE
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = deleteUrl; // Usa la URL de la ruta 'gestionusuario.destroy'
            
            // 1. Añadir el token CSRF (obligatorio en Laravel)
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            // Usa la función Blade para obtener el token.
            csrfInput.value = '{{ csrf_token() }}'; 
            form.appendChild(csrfInput);

            // 2. Añadir el campo de método para simular DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            // Enviar el formulario y recargar la página
            document.body.appendChild(form);
            form.submit();
        }
      });
    }

    // Validación de formularios de Bootstrap
    (function () {
      'use strict'
      var form = document.getElementById('registroForm');
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    })();
  </script>

</body>
</html>