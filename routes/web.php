<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\PieceController;
use App\Http\Controllers\PieceRecordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas de Proyectos
    Route::resource('projects', ProjectController::class);

    // Rutas de Bloques
    Route::resource('blocks', BlockController::class);

    // Rutas de Piezas
    Route::resource('pieces', PieceController::class);

    // Rutas de Registros de Piezas
    Route::resource('piece-records', PieceRecordController::class);

    // Rutas de Usuarios
    Route::resource('users', UserController::class);
});
