<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Cursos</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <link rel="stylesheet" href="../css/estiloinicio.css">
</head>
<body>
  
  <div class="main-content container mt-4 mb-5">
    <h2 class="text-danger text-center mb-4">Gestión de Curso</h2>

    <div class="row">
      <div class="col-13 col-lg-6 mb-4">
        <div class="card shadow">
          <div class="card-header bg-danger text-white">
            Registro de Cursos
          </div>
          <div class="card-body">
            <form method="get" action=".." id="registroForm">
              <div class="mb-3">
                <label>Nombre del curso</label>
                <input type="text" name="nombre" placeholder="nombre del curso" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Descripcion</label>
                <input type="text" name="descripcion" placeholder="Una descripción pequeña del curso" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Fecha del inicio</label>
                <input type="date" name="fecha_in" placeholder="0000/00/00" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Fecha del fin</label>
                <input type="date" name="fecha_fin" placeholder="0000/00/00" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Materia</label>
                <input type="text" name="materia" class="form-control" placeholder="Ej: Matemáticas, Programación" required>
              </div>              
              <div class="mb-3">
                <label>Horas disponibles</label>
                <input type="number" name="horas" placeholder="1 o mas horas" class="form-control" required min="1">
              </div>
              <div class="mb-3">
                <label>Estado</label>
                <select name="estado" class="form-select" required>
                  <option value="">Seleccione un estado</option>
                  <option value="1">Disponible</option>
                  <option value="2">Ocupado</option>
                  <option value="3">Terminado</option>
                </select>
              </div>
              <div class="text-end">
                <button type="submit" class="btn btn-danger">Registrar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      
      <div class="col-12"> 
        <div class="card shadow">
          <div class="card-header bg-danger text-white">
            Cursos Registrados
          </div> 
          <div class="card-body p-0">
            <table class="table table-bordered text-center align-middle m-0">
              <thead class="table-light">
                <tr>
                  <th>Nombre del curso</th>
                  <th>Descripcion</th>
                  <th>Fecha inicio</th>
                  <th>Fecha fin</th>
                  <th>Materia</th>
                  <th>Horas</th>
                  <th>Estado</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>                
                <tr>
                  <td>Curso Introductorio de C#</td>
                  <td>Fundamentos de la programación orientada a objetos</td>
                  <td>2026-01-10</td>
                  <td>2026-03-10</td>
                  <td>Programación</td>
                  <td>40</td>
                  <td>Disponible</td>
                  <td>
                    <a href="#" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de querer eliminar este curso?');">Eliminar</a>
                    <a href="#" class="btn btn-warning btn-sm text-white">Editar</a>
                  </td>
                </tr> 
                 <tr>
                  <td>Cálculo Diferencial Avanzado</td>
                  <td>Temas selectos de cálculo para ingeniería</td>
                  <td>2025-09-01</td>
                  <td>2025-12-15</td>
                  <td>Matemáticas</td>
                  <td>60</td>
                  <td>Ocupado</td>
                  <td>
                    <a href="#" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de querer eliminar este curso?');">Eliminar</a>
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