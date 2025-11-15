<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Biblioteca</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <link rel="stylesheet" href="../css/estiloinicio.css">
</head>
<body>
  
  <div class="main-content container mt-4 mb-5">
    <h2 class="text-danger text-center mb-4">Gestión de Biblioteca</h2>

    <div class="row">
      <div class="col-13 col-lg-6 mb-4">
        <div class="card shadow">
          <div class="card-header bg-danger text-white">
            Registro de Archivos
          </div>
          <div class="card-body">
            <form method="get" action=".." id="registroForm">
              <div class="mb-3">
                <label>Nombre del archivo</label>
                <input type="text" name="nombre_archivo" placeholder="Nombre del documento" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Url del archivo</label>
                <input type="url" name="url_archivo" placeholder="https://ejemplo.com/archivo.pdf" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Fecha:</label>
                <input type="date" name="fecha_registro" placeholder="0000/00/00" class="form-control" required>
              </div>                                        
              <div class="text-end">
                <button type="submit" class="btn btn-danger">Registrar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      
      <div class="col-12 col-lg-6"> 
        <div class="card shadow">
          <div class="card-header bg-danger text-white">
            Archivos Registrados
          </div> 
          <div class="card-body p-0">
            <table class="table table-bordered text-center align-middle m-0">
              <thead class="table-light">
                <tr>
                  <th>Nombre</th>
                  <th>URL</th>
                  <th>Fecha</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>                  
                <tr>
                  <td>Manual de PHP</td>
                  <td><a href="#" target="_blank">Ver Enlace</a></td>
                  <td>2024/10/01</td>                   
                  <td>
                    <a href="#" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de querer eliminar este archivo?');">Eliminar</a>
                    <a href="#" class="btn btn-warning btn-sm text-white">Editar</a>
                  </td>
                </tr> 
                <tr>
                  <td>Guía de Bootstrap</td>
                  <td><a href="#" target="_blank">Ver Enlace</a></td>
                  <td>2024/09/15</td>                   
                  <td>
                    <a href="#" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de querer eliminar este archivo?');">Eliminar</a>
                    <a href="#" class="btn btn-warning btn-sm text-white">Editar</a>
                  </td>
                </tr>
                </tbody>
            </table> 
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.getElementById("registroForm").addEventListener("submit", function(e) {

    });
  </script>

</body>
</html>