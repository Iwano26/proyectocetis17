@extends('layouts.app')
@section('content')

<style>
    :root { --cetis-rojo: #8C001A; }

    .pregunta-examen {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 28px;
        margin-bottom: 16px;
        border-left: 4px solid var(--cetis-rojo);
    }

    .opcion-label {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .opcion-label:hover {
        border-color: var(--cetis-rojo);
        background: #fff0f2;
    }

    .opcion-label input[type="radio"],
    .opcion-label input[type="checkbox"] {
        accent-color: var(--cetis-rojo);
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    .cronometro-bar {
        position: sticky;
        top: 0;
        z-index: 100;
        background: white;
        border-bottom: 2px solid #f0f0f0;
        padding: 12px 0;
        margin-bottom: 24px;
    }

    .cronometro-display {
        font-size: 1.4rem;
        font-weight: 800;
        font-family: 'Courier New', monospace;
        color: var(--cetis-rojo);
    }

    .cronometro-display.urgente { color: #dc2626; animation: parpadeo 1s infinite; }

    @keyframes parpadeo {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }

    .numero-pregunta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        background: var(--cetis-rojo);
        color: white;
        border-radius: 50%;
        font-size: 0.8rem;
        font-weight: 700;
        flex-shrink: 0;
    }
</style>

{{-- Cronómetro --}}
<div class="cronometro-bar shadow-sm">
    <div class="container d-flex align-items-center justify-content-between">
        <div>
            <span class="fw-bold text-dark">{{ $cuestionario->nombre_cuestionario }}</span>
            <span class="text-muted ms-2" style="font-size:0.85rem;">· Intento #{{ $intento->numero_intento }}</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-clock text-danger"></i>
            <span class="cronometro-display" id="cronometro">--:--</span>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <form action="{{ route('examen.guardar', $intento->id_intento) }}"
                  method="POST" id="formExamen">
                @csrf

                @foreach($cuestionario->preguntas as $i => $pregunta)
                    <div class="pregunta-examen">
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="numero-pregunta">{{ $i + 1 }}</div>
                            <p class="fw-semibold mb-0" style="line-height:1.5;">
                                {{ $pregunta->texto_pregunta }}
                            </p>
                        </div>

                        @if($pregunta->tipo === 'abierta')
                            <textarea
                                name="pregunta_{{ $pregunta->id_pregunta }}"
                                class="form-control border-2"
                                rows="4"
                                placeholder="Escribe tu respuesta aquí..."
                                required></textarea>

                        @elseif($pregunta->tipo === 'verdadero_falso' || $pregunta->tipo === 'opcion_multiple')
                            @foreach($pregunta->opciones as $opcion)
                                <label class="opcion-label">
                                    <input type="radio"
                                        name="pregunta_{{ $pregunta->id_pregunta }}"
                                        value="{{ $opcion->id_opcion }}"
                                        required>
                                    {{ $opcion->texto_opcion }}
                                </label>
                            @endforeach

                        @elseif($pregunta->tipo === 'multiple_correcta')
                            <small class="text-muted d-block mb-2">
                                <i class="bi bi-info-circle me-1"></i>
                                Selecciona todas las opciones correctas.
                            </small>
                            @foreach($pregunta->opciones as $opcion)
                                <label class="opcion-label">
                                    <input type="checkbox"
                                        name="pregunta_{{ $pregunta->id_pregunta }}[]"
                                        value="{{ $opcion->id_opcion }}">
                                    {{ $opcion->texto_opcion }}
                                </label>
                            @endforeach
                        @endif
                    </div>
                @endforeach

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-danger fw-bold px-5 py-3 shadow-sm fs-6"
                            onclick="return confirm('¿Estás seguro de enviar el examen? No podrás modificar tus respuestas.')">
                        <i class="bi bi-send-fill me-2"></i> Enviar Examen
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
let segundos = {{ $segundosRestantes }};
const display = document.getElementById('cronometro');
const form = document.getElementById('formExamen');

function actualizarCronometro() {
    if (segundos <= 0) {
        display.textContent = '00:00';
        display.classList.add('urgente');
        form.submit();
        return;
    }

    const horas = Math.floor(segundos / 3600);
    const mins = Math.floor((segundos % 3600) / 60);
    const segs = segundos % 60;

    if (horas > 0) {
        display.textContent =
            String(horas).padStart(2, '0') + ':' +
            String(mins).padStart(2, '0') + ':' +
            String(segs).padStart(2, '0');
    } else {
        display.textContent =
            String(mins).padStart(2, '0') + ':' +
            String(segs).padStart(2, '0');
    }

    if (segundos <= 60) display.classList.add('urgente');

    segundos--;
}

actualizarCronometro();
setInterval(actualizarCronometro, 1000);
</script>

@endsection