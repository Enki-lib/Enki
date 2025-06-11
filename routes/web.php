<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});

Route::get('/cadastro-livro', function () {
    return view('cadastro-livro');
});

Route::get('/livro-detalhes', function () {
    return view('livro-detalhes');
});
