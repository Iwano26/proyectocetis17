<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Asesorias</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <link rel="stylesheet" href="../css/estiloinicio.css">
</head>
<body>
  
  <div class="main-content container mt-4 mb-5">
    <h2 class="text-danger text-center mb-4">Gestión de Asesorias</h2>

    <div class="row">
      <div class="col-13 col-lg-6 mb-4">
        <div class="card shadow">
          <div class="card-header bg-danger text-white">
            Registro de Asesoría
          </div>
          <div class="card-body">
            <form method="get" action=".." id="registroForm">
              <div class="mb-3">
                <label>Fecha</label>
                <input type="date" name="fecha" placeholder="0000/00/00" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Hora inicio</label>
                <input type="time" name="hora_inicio" placeholder="00:00" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Hora fin</label>
                <input type="time" name="hora_fin" placeholder="00:00" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Lugar</label>
                <input type="text" name="lugar" class="form-control" placeholder="Salón u oficina" required>
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
            Asesorías Registradas
          </div> 
          <div class="card-body p-0">
            <table class="table table-bordered text-center align-middle m-0">
              <thead class="table-light">
                <tr>
                  <th>Fecha</th>
                  <th>Hora inicio</th>
                  <th>Hora fin</th>
                  <th>Lugar</th>                                        
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>                  
                <tr>
                  <td>2025/11/15</td>
                  <td>10:00</td>
                  <td>11:00</td>
                  <td>Salón A-101</td>                                        
                  <td>
                    <a href="#" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de querer eliminar esta asesoría?');">Eliminar</a>
                    <a href="#" class="btn btn-warning btn-sm text-white">Editar</a>
                  </td>
                </tr>
                <tr>
                  <td>2025/11/16</td>
                  <td>14:30</td>
                  <td>16:00</td>
                  <td>Biblioteca</td>                                        
                  <td>
                    <a href="#" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de querer eliminar esta asesoría?');">Eliminar</a>
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