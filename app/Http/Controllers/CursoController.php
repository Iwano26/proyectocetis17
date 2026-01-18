<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CursoController extends Controller
{
    // Vista principal de búsqueda
    public function index() {
        $cursos = []; 
        return view('BuscarCurso', compact('cursos'));
    }

    // Mostrar formulario de crear
    public function create() {
        return view('CursosViews/crear');
    }

    // Mostrar formulario de editar (con datos de prueba mejorados)
    public function edit($id) {
        // Agregamos 'estado', 'password_curso' y más horarios para que la vista luzca completa
        $curso = (object)[
            'id' => $id,
            'nombre_curso' => 'Matemáticas IV',
            'materia' => 'Matemáticas',
            'estado' => 'Cerrado', // <--- Agregado: Prueba con 'Cerrado' para ver la contraseña
            'password_curso' => 'CETIS123', // <--- Agregado
            'horarios' => collect([
                (object)['dia' => 'Lunes', 'hora_inicio' => '10:00', 'hora_fin' => '12:00'],
                (object)['dia' => 'Miércoles', 'hora_inicio' => '14:00', 'hora_fin' => '16:00'] // <--- Un segundo horario de prueba
            ])
        ];
        
        return view('CursosViews/editar', compact('curso'));
    }

    // Métodos de respuesta rápida para pruebas
    public function store(Request $request) { 
        // Esto te permite ver en pantalla qué datos está enviando tu formulario de Crear
        return response()->json([
            'mensaje' => 'Simulación de guardado exitosa',
            'datos_recibidos' => $request->all()
        ]);
    }

    public function update(Request $request, $id) { 
        // Esto te permite ver qué datos está enviando tu formulario de Editar
        return response()->json([
            'mensaje' => "Simulación de actualización del curso $id exitosa",
            'datos_recibidos' => $request->all()
        ]);
    }

    public function show($id) {
    $curso = (object)[
        'id' => $id,
        'nombre' => 'MATEMÁTICAS IV',
        'materia' => 'Cálculo Integral',
        'descripcion' => 'Espacio dedicado a la resolución de dudas sobre métodos de integración y aplicaciones de la integral definida.',
        'instructor' => 'Ing. Juan Pérez',
        'asesorias' => [
            (object)[
                'tema' => 'Fracciones Parciales',
                'dia' => 'Lunes',
                'fecha' => '20 de Octubre',
                'hora' => '10:00 - 12:00',
                'salon' => 'Laboratorio A',
                'tipo' => 'Presencial'
            ],
            (object)[
                'tema' => 'Integración por Partes',
                'dia' => 'Miércoles',
                'fecha' => '22 de Octubre',
                'hora' => '14:00 - 16:00',
                'salon' => 'Aula 12',
                'tipo' => 'Presencial'
            ],
            (object)[
                'tema' => 'Repaso de Examen',
                'dia' => 'Viernes',
                'fecha' => '24 de Octubre',
                'hora' => '09:00 - 11:00',
                'salon' => 'Biblioteca',
                'tipo' => 'Grupal'
            ],
        ]
    ];
    return view('CursosViews/vercurso', compact('curso'));
}
}