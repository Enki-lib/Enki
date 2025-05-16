<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Route::get('/users', function (Request $request) {
//     return response()->json([
//         'status' => true,
//         'message' => 'listar usuários',
//     ], 200);
// });

Route::get('/users', [UserController::class, 'index']);               // GET -> 127.0.0.1:8000/api/users?page=1

Route::get('/users/{user}', [UserController::class, 'show']);      // Get -> 127.0.0.1:8000/api/users/<idDoUsuario>

Route::post('/users/register', [UserController::class, 'store']);               // POST -> 127.0.0.1:8000/api/users

Route::put('/users/edit/{user}', [UserController::class, 'update']);  

Route::delete('/users/delete/{user}', [UserController::class, 'destroy']);