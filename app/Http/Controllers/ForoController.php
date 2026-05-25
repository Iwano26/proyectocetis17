<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Foro;
use App\Models\PreguntaForo;
use App\Models\RespuestaForo;
use App\Models\Curso;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ForoController extends Controller
{
    public function index($id)
    {
        // ── CORRECCIÓN: mismo JOIN que EventoController para obtener nombre_asesor ──
        $curso = DB::table('curso')
            ->join('persona', 'curso.correo_persona', '=', 'persona.correo')
            ->select(
                'curso.*',
                DB::raw("CONCAT(persona.nombre, ' ', persona.apellidoPa, ' ', persona.apellidoMa) as nombre_asesor")
            )
            ->where('curso.id_curso', $id)
            ->first();

        if (!$curso) {
            return redirect()->route('cursos.index')->with('mensaje', 'Curso no encontrado');
        }

        $foro = Foro::where('id_curso', $id)->first();

        if (!$foro) {
            $foro = Foro::create([
                'id_curso'     => $id,
                'nombre_foro'  => 'Foro de Consultas: ' . $curso->nombre_curso,
                'descripcion'  => 'Espacio dedicado a resolver dudas académicas.'
            ]);
        }

        $preguntas = PreguntaForo::where('id_foro', $foro->id_foro)
            ->with(['autor'])
            ->withCount('respuestas')
            ->orderBy('fecha_pregunta', 'desc')
            ->get();

        $yaInscrito = false;
        if (Auth::check() && Auth::user()->rol === 'Estudiante') {
            $yaInscrito = DB::table('inscripcion')
                ->where('id_curso', $id)
                ->where('correo_estudiante', Auth::user()->correo)
                ->exists();
        }

        return view('CursosViews.foro', compact('curso', 'foro', 'preguntas', 'yaInscrito'));
    }

    public function storePregunta(Request $request, $id)
    {
        $request->validate(['texto_pregunta' => 'required']);

        // Filtrado de groserías para preguntas
        if ($this->contieneGroserias($request->texto_pregunta)) {
            return back()->with('error', 'Tu publicación contiene lenguaje inapropiado. Por favor, mantén el respeto en el foro escolar.');
        }

        $foro = Foro::where('id_curso', $id)->first();

        PreguntaForo::create([
            'id_foro'         => $foro->id_foro,
            'correo_persona'  => Auth::user()->correo,
            'texto_pregunta'  => $request->texto_pregunta,
            'fecha_pregunta'  => Carbon::now()
        ]);

        return back()->with('success', 'Tu duda ha sido publicada correctamente.');
    }

    public function show($id, $id_pregunta)
    {
        // ── CORRECCIÓN: mismo JOIN para que nombre_asesor también funcione en el detalle ──
        $curso = DB::table('curso')
            ->join('persona', 'curso.correo_persona', '=', 'persona.correo')
            ->select(
                'curso.*',
                DB::raw("CONCAT(persona.nombre, ' ', persona.apellidoPa, ' ', persona.apellidoMa) as nombre_asesor")
            )
            ->where('curso.id_curso', $id)
            ->first();

        if (!$curso) {
            return redirect()->route('cursos.index')->with('mensaje', 'Curso no encontrado');
        }

        $pregunta = PreguntaForo::with(['autor', 'respuestas.autor'])->findOrFail($id_pregunta);

        $yaInscrito = false;
        if (Auth::check() && Auth::user()->rol === 'Estudiante') {
            $yaInscrito = DB::table('inscripcion')
                ->where('id_curso', $id)
                ->where('correo_estudiante', Auth::user()->correo)
                ->exists();
        }

        return view('CursosViews.foro_detalle', compact('curso', 'pregunta', 'yaInscrito'));
    }

    public function storeRespuesta(Request $request, $id, $id_pregunta)
    {
        $request->validate([
            'texto_respuesta' => 'required'
        ]);

        // Filtrado de groserías para respuestas
        if ($this->contieneGroserias($request->texto_respuesta)) {
            return back()->with('error', 'Tu respuesta contiene lenguaje inapropiado. Por favor, mantén el respeto en el foro escolar.');
        }

        RespuestaForo::create([
            'id_pregunta_foro' => $id_pregunta,
            'correo_persona'   => Auth::user()->correo,
            'texto_respuesta'  => $request->texto_respuesta,
            'fecha_respuesta'  => Carbon::now()
        ]);

        return back()->with('success', 'Tu respuesta ha sido enviada.');
    }

    /**
     * Filtro para verificar si el texto contiene palabras ofensivas comunes.
     */
    private function contieneGroserias($texto)
    {
        // Lista de insultos o palabras no permitidas comunes
        $groserias = [
            'puto', 'puta', 'pendejo', 'pendeja', 'mierda', 'culero', 'culera', 
            'cabron', 'cabrona', 'chingar', 'chinga', 'pito', 'verga', 'joto', 
            'pendejada', 'maricon', 'putita', 'putito', 'asshole', 'bitch'
        ];

        // Pasamos todo el texto a minúsculas para que no evadan el filtro usando Mayúsculas
        $textoMinuscula = mb_strtolower($texto, 'UTF-8');

        foreach ($groserias as $groseria) {
            // El regex \b busca la palabra exacta para evitar falsos positivos (ej. "disPUTA" o "caBRONce")
            if (preg_match('/\b' . preg_quote($groseria, '/') . '\b/u', $textoMinuscula)) {
                return true;
            }
        }

        return false;
    }
}