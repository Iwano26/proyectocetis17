<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Usuarios</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <link rel="stylesheet" href="../css/estiloinicio.css">
</head>
<body>
  
  <div class="main-content container mt-4 mb-5">
    <h2 class="text-danger text-center mb-4">Gestión de Usuarios</h2>

    <div class="row">
      <!-- FORMULARIO A LA IZQUIERDA -->
      <div class="col-13 col-lg-6 mb-4">
        <div class="card shadow">
          <div class="card-header bg-danger text-white">
            Registro de Usuario
          </div>
          <div class="card-body">
            <form method="get" action=".." id="registroForm">
              <div class="mb-3">
                <label>Nombre</label>
                <input type="text" name="nombre" placeholder="nombre" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Apellido paterno</label>
                <input type="text" name="apellido" placeholder="apellido paterno" class="form-control" required>
              </div>
            <div class="mb-3">
            <label>Apellido materno</label>
            <input type="text" name="apellido" placeholder="apellido materno" class="form-control" required>
            </div>
              <div class="mb-3">
                <label>Correo Electrónico</label>
                <input type="email" name="correo" class="form-control" placeholder="usuario@cetis17.edu.mx" required>
              </div>
              <div class="mb-3">
                <label>Teléfono</label>
                <input type="tel" name="telefono" placeholder="* * * * * * * * * *" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Rol</label>
                <select name="rol" class="form-select" required>
                  <option value="">Seleccione un rol</option>
                  <option value="1">Administrador</option>
                  <option value="2">Maestro</option>
                  <option value="3">Alumno</option>
                </select>
              </div>
              <div class="text-end">
                <button type="submit" class="btn btn-danger">Registrar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      
      <!-- TABLA ABAJO -->
      <div class="col-12">
        <div class="card shadow">
          <div class="card-header bg-danger text-white">
            Usuarios Registrados
          </div>                  
              <table class="table table-bordered text-center align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Nombre</th>
                    <th>Apellido paterno</th>
                    <th>Apellido materno</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>                  
                  <tr>
                    <td><?= htmlspecialchars($datos['nombre']) ?></td>
                    <td><?= htmlspecialchars($datos['apellido paterno']) ?></td>
                    <td><?= htmlspecialchars($datos['apellido materno']) ?></td>
                    <td><?= htmlspecialchars($datos['correo']) ?></td>
                    <td><?= htmlspecialchars($datos['telefono']) ?></td>
                    <td><?= $roles[$datos['id_rol']] ?? 'Desconocido' ?></td>
                    <td>
                      <a href="../../servidor/eliminar_usu.php?id=<?= $datos['id_persona'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de querer eliminar a este usuario?');">Eliminar</a>
                      <a href="form_editar_usuario.php?id=<?= $datos['id_persona'] ?>" class="btn btn-warning btn-sm text-white">Editar</a>
                    </td>
                  </tr>                  
                </tbody>
              </table>                  
        </div>
      </div>
    </div>
  </div>

  <script>
    document.getElementById("registroForm").addEventListener("submit", function(e) {
      const correo = document.querySelector("[name='correo']").value;
      const regex = /^[a-zA-Z0-9._%+-]+@cetis17\.edu\.mx$/;

      if (!regex.test(correo)) {
        e.preventDefault();
        Swal.fire({
          icon: 'error',
          title: 'Correo inválido',
          text: 'El correo debe ser institucional (@cetis17.edu.mx)',
        });
      }
    });
  </script>

</body>
</html>
