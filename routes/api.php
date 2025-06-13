<?php

use App\Http\Controllers\EmprestimoController;
use App\Http\Controllers\LivroController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;

// Route::get('/users', function (Request $request) {
//     return response()->json([
//         'status' => true,
//         'message' => 'listar usuários',
//     ], 200);
// });


//* ROTAS DO CRUD DE USUÁRIOS

// Public routes
Route::post('/login', [AuthController::class, 'login']);                                       // * POST -> 127.0.0.1:8000/api/login
Route::post('/usuarios/registrar', [UserController::class, 'store']);                          // * POST -> 127.0.0.1:8000/api/users/register

// Protected routes
Route::middleware('auth:api')->group(function () {
    // User routes
Route::get('/usuarios', [UserController::class, 'index']);                                      // * GET -> 127.0.0.1:8000/api/users?page=1
Route::get('/usuarios/{usuario}', [UserController::class, 'show']);                             // * GET -> 127.0.0.1:8000/api/users/<idDoUsuario>
Route::put('/usuarios/editar/{usuario}', [UserController::class, 'update']);                    // * PUT -> 127.0.0.1/api/users/edit/<idDoUsuario>
Route::delete('/usuarios/excluir/{usuario}', [UserController::class, 'destroy']);               // * DELETE -> 127.0.0.1/api/users/delete/<idDoUsuario>

    // Book routes
Route::get('/livros', [LivroController::class, 'index']);                                       // * GET -> 127.0.0.1:8000/api/livros?page=1
Route::get('/livros/{livro}', [LivroController::class, 'show']);                                // * GET -> 127.0.0.1:8000/api/livros/<idDoUsuario>
Route::post('/livros/registro', [LivroController::class, 'store']);                             // * POST -> 127.0.0.1:8000/api/livros/registro
Route::put('/livros/editar/{livro}', [LivroController::class, 'update']);                       // * PUT -> 127.0.0.1/api/users/editar/<idDoUsuario>
Route::delete('/livros/excluir/{livro}', [LivroController::class, 'destroy']);                  // * DELETE -> 127.0.0.1/api/livros/excluir/<idDoLivro>

    // Loan routes
Route::get('/emprestimos', [EmprestimoController::class, 'index']);                             // * GET -> 127.0.0.1/api/emprestimos
Route::get('/emprestimos/{emprestimo}', [EmprestimoController::class, 'show']);                 // * GET -> 127.0.0.1/api/emprestimos/<idDoEmprestimo>
Route::post('/emprestimos/registro', [EmprestimoController::class, 'store']);                   // * POST -> 127.0.0.1/api/emprestimos/registro
Route::put('/emprestimos/editar/{emprestimo}', [EmprestimoController::class, 'update']);        // * PUT -> 127.0.0.1/api/emprestimos/editar/<idDoEmprestimo>
Route::delete('/emprestimos/excluir/{emprestimo}', [EmprestimoController::class, 'destroy']);   // * DELETE -> 127.0.0.1/api/emprestimos/excluir/<idDoEmprestimo>

    // Category routes
    Route::get('/categorias', [CategoriaController::class, 'index']);                               // * GET -> 127.0.0.1:8000/api/categorias
});