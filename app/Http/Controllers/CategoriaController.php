<?php

namespace App\Http\Controllers;

use App\Models\CategoriaModel;
use Illuminate\Http\JsonResponse;

class CategoriaController extends Controller
{
    public function index(): JsonResponse
    {
        $categorias = CategoriaModel::all();
        return response()->json([
            'status' => true,
            'categorias' => $categorias
        ], 200);
    }
} 