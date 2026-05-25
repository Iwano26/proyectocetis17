<div class="tarjeta-curso shadow-sm bg-white p-4 mb-3 rounded"
     style="border-left: 5px solid #8C001A; transition: transform 0.2s;">
    <div class="row align-items-center">

        {{-- Info principal --}}
        <div class="col-md-5 d-flex align-items-center">
            <div class="me-3 d-flex align-items-center justify-content-center rounded-circle"
                 style="background-color: #8C001A; color: white; width: 45px; height: 45px; min-width: 45px;">
                <i class="bi bi-book" style="font-size: 1.2rem;"></i>
            </div>
            <div>
                <h4 class="mb-1 text-uppercase m-0" style="font-size: 1.35rem; letter-spacing: -0.3px;">
                    {{ $curso->nombre_curso }}
                </h4>
                <p class="text-muted small mb-1 fw-medium">
                    <i class="bi bi-tags-fill me-1 text-danger"></i>{{ $curso->materia }}
                </p>
                <p class="small text-secondary mb-0">
                    {{ Str::limit($curso->descripcion ?? 'Sin descripción disponible.', 100) }}
                </p>
                @if($curso->acceso)
                    <span class="badge bg-warning text-dark mt-1" style="font-size:0.7rem;">
                        <i class="bi bi-lock-fill me-1"></i> Requiere clave
                    </span>
                @endif
            </div>
        </div>

        {{-- Horarios --}}
        <div class="col-md-4 border-start border-end my-3 my-md-0 px-4">
            <h6 class="fw-bold small text-muted text-uppercase mb-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                <i class="bi bi-clock-history me-1 text-danger"></i> Horarios:
            </h6>
            @forelse($curso->horarios as $horario)
                <div class="p-2 border rounded bg-light d-flex justify-content-between align-items-center mb-1"
                     style="font-size: 0.85rem;">
                    <span class="fw-bold text-dark">
                        <i class="bi bi-calendar-event text-danger me-1"></i> {{ $horario->dia_semana }}
                    </span>
                    <span class="badge bg-white text-dark border fw-semibold shadow-sm">
                        {{ date('g:i A', strtotime($horario->hora_inicio)) }} -
                        {{ date('g:i A', strtotime($horario->hora_fin)) }}
                    </span>
                </div>
            @empty
                <p class="text-muted small m-0">Sin horarios asignados</p>
            @endforelse
        </div>

        {{-- Estado y acciones --}}
        <div class="col-md-3 text-md-end d-flex flex-column justify-content-between h-100">
            <div class="mb-3">
                @php
                    $badgeEstado = match($curso->estado) {
                        'ACTIVO'     => 'bg-success',
                        'COMPLETADO' => 'bg-primary',
                        default      => 'bg-secondary'
                    };
                @endphp
                <span class="badge {{ $badgeEstado }} px-3 py-2 rounded-pill text-uppercase"
                      style="font-size: 0.75rem;">
                    {{ $curso->estado }}
                </span>
            </div>

            <div>
                <div class="btn-group w-100 shadow-sm" style="border-radius: 8px; overflow: hidden;">

                    @if(Auth::user()->rol === 'Administrador' || $curso->correo_persona === Auth::user()->correo)
                        <form id="delete-form-{{ $curso->id_curso }}"
                              action="{{ route('cursos.destroy', $curso->id_curso) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" onclick="confirmarEliminacion({{ $curso->id_curso }})"
                                class="btn btn-sm btn-danger px-3 btn-accion" style="border-radius: 0;">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        <a href="{{ route('cursos.edit', $curso->id_curso) }}"
                           class="btn btn-outline-warning btn-sm fw-bold px-3 btn-accion"
                           style="border-radius: 0;">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                    @endif

                   

                    {{-- SECCIÓN CORREGIDA DENTRO DE ACCIONES EN TARJETA-CURSO.BLADE.PHP --}}
                    @if(Auth::user()->rol === 'Estudiante')
                        @if(in_array($curso->id_curso, $misInscripciones))
                            <a href="{{ route('cursos.show', $curso->id_curso) }}"
                            class="btn btn-success btn-sm fw-bold px-3 btn-accion w-100"
                            style="font-size: 0.8rem; border-radius: 0;">
                                <i class="bi bi-box-arrow-in-right me-1"></i> ENTRAR
                            </a>
                        @elseif($curso->estado === 'ACTIVO')
                            @if($curso->acceso)
                                {{-- Curso con clave: abre modal (Le agregamos w-100) --}}
                                <button type="button"
                                    class="btn btn-danger btn-sm fw-bold px-3 btn-accion w-100"
                                    style="background-color: #8C001A; border: none; font-size: 0.8rem; border-radius: 0;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalClave{{ $curso->id_curso }}">
                                    <i class="bi bi-lock-fill me-1"></i> UNIRSE
                                </button>
                            @else
                                {{-- Curso libre (Le agregamos w-100 al button para que venza el aislamiento del form) --}}
                                <form action="{{ route('cursos.inscribir', $curso->id_curso) }}"
                                    method="POST" style="display: contents;">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-danger btn-sm fw-bold px-3 btn-accion w-100"
                                        style="background-color: #8C001A; border: none; font-size: 0.8rem; border-radius: 0;">
                                        <i class="bi bi-person-plus-fill me-1"></i> UNIRSE
                                    </button>
                                </form>
                            @endif
                        @else
                            <button class="btn btn-secondary btn-sm fw-bold px-3 btn-accion w-100"
                                style="font-size: 0.8rem; border-radius: 0;" disabled>
                                No disponible
                            </button>
                        @endif
                    @else
                    {{-- Asesor o Admin --}}
                    @if(Auth::user()->rol === 'Administrador' || $curso->correo_persona === Auth::user()->correo)
                        {{-- Es su propio curso: puede entrar y gestionar --}}
                        <a href="{{ route('cursos.show', $curso->id_curso) }}"
                        class="btn btn-danger btn-sm fw-bold px-3 btn-accion"
                        style="background-color: #8C001A; border: none; font-size: 0.8rem; border-radius: 0;">
                            <i class="bi bi-gear-fill me-1"></i> GESTIONAR
                        </a>
                    @else
                        {{-- Curso de otro asesor: solo puede ver --}}
                        <a href="{{ route('cursos.show', $curso->id_curso) }}"
                        class="btn btn-outline-secondary btn-sm fw-bold px-3 btn-accion"
                        style="font-size: 0.8rem; border-radius: 0;">
                            <i class="bi bi-eye me-1"></i> VER
                        </a>
                    @endif
                @endif

                </div>

                {{-- Modal clave de acceso --}}
                @if($curso->acceso && Auth::user()->rol === 'Estudiante')
                    <div class="modal fade" id="modalClave{{ $curso->id_curso }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-sm">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header bg-dark text-white py-3">
                                    <h6 class="modal-title fw-bold">
                                        <i class="bi bi-lock-fill text-danger me-2"></i> Clave de Acceso
                                    </h6>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">

                                    @if(session('error_clave_' . $curso->id_curso))
                                        <div class="alert alert-danger py-2 px-3 mb-3" style="font-size:0.85rem;">
                                            <i class="bi bi-x-circle me-1"></i>
                                            {{ session('error_clave_' . $curso->id_curso) }}
                                        </div>
                                    @endif

                                    <p class="text-muted mb-3" style="font-size:0.85rem;">
                                        Este curso requiere una clave para unirse. Pídela a tu asesor.
                                    </p>

                                    <form action="{{ route('cursos.inscribir', $curso->id_curso) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small">Clave del curso:</label>
                                            <input type="text"
                                                name="clave_acceso"
                                                class="form-control border-2 text-center fw-bold"
                                                placeholder="Ej: PRO20"
                                                maxlength="20"
                                                required
                                                autocomplete="off">
                                        </div>
                                        <button type="submit" class="btn btn-danger fw-bold w-100 shadow-sm">
                                            <i class="bi bi-door-open-fill me-2"></i> Ingresar al Curso
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- Fin modal --}}

            </div>
        </div>

    </div>
</div>