<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Foro;
use App\Models\PreguntaForo;
use App\Models\RespuestaForo;
use App\Models\Curso;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ForoController extends Controller
{
    public function index($id)
    {
        $curso = Curso::findOrFail($id);
        $foro = Foro::where('id_curso', $id)->first();

        if (!$foro) {
            $foro = Foro::create([
                'id_curso' => $id,
                'nombre_foro' => 'Foro de Consultas: ' . $curso->nombre_curso,
                'descripcion' => 'Espacio dedicado a resolver dudas académicas.'
            ]);
        }

        $preguntas = PreguntaForo::where('id_foro', $foro->id_foro)
            ->with(['autor'])
            ->withCount('respuestas')
            ->orderBy('fecha_pregunta', 'desc')
            ->get();

        return view('CursosViews.foro', compact('curso', 'foro', 'preguntas'));
    }

    public function storePregunta(Request $request, $id)
    {
        $request->validate(['texto_pregunta' => 'required']);
        $foro = Foro::where('id_curso', $id)->first();

        PreguntaForo::create([
            'id_foro' => $foro->id_foro,
            'correo_persona' => Auth::user()->correo,
            'texto_pregunta' => $request->texto_pregunta,
            'fecha_pregunta' => Carbon::now()
        ]);

        return back()->with('success', 'Tu duda ha sido publicada correctamente.');
    }

    public function show($id, $id_pregunta)
    {
        $curso = Curso::findOrFail($id);
        $pregunta = PreguntaForo::with(['autor', 'respuestas.autor'])->findOrFail($id_pregunta);

        return view('CursosViews.foro_detalle', compact('curso', 'pregunta'));
    }

    // ESTE ES EL MÉTODO QUE TE FALTABA
    public function storeRespuesta(Request $request, $id, $id_pregunta)
    {
        $request->validate([
            'texto_respuesta' => 'required'
        ]);

        RespuestaForo::create([
            'id_pregunta_foro' => $id_pregunta,
            'correo_persona' => Auth::user()->correo,
            'texto_respuesta' => $request->texto_respuesta,
            'fecha_respuesta' => Carbon::now()
        ]);

        return back()->with('success', 'Tu respuesta ha sido enviada.');
    }
}