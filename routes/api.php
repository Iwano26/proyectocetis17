<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ApiController;

// Login de React Native existente
Route::post('/login-movil', [LoginController::class, 'loginMovil']);

// --- NUEVAS 4 APIS REALES (Para Thunder Client / Railway) ---
Route::get('/cursos-movil', [ApiController::class, 'obtenerCursos']);
Route::get('/usuarios-movil', [ApiController::class, 'obtenerUsuarios']);
Route::get('/eventos-movil', [ApiController::class, 'obtenerEventos']);
Route::post('/biblioteca-movil', [ApiController::class, 'subirDocumento']);