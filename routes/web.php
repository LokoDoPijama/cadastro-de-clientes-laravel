<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Models\Cliente;

Route::get('/', function () {
    return view('welcome', ['clientes' => Cliente::all()]);
});

Route::post('/cadastrarRoute', [ClienteController::class, 'cadastrar'])->name('cadastrar');

Route::post('/editarRoute', [ClienteController::class, 'editar'])->name('editar');

Route::post('/deletarRoute', [ClienteController::class, 'deletar'])->name('deletar');