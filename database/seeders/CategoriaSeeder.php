<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CategoriaModel;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            ['nome_categoria' => 'Romance'],
            ['nome_categoria' => 'Terror'],
            ['nome_categoria' => 'Suspense'],
            ['nome_categoria' => 'Ficção Científica'],
            ['nome_categoria' => 'Fantasia'],
            ['nome_categoria' => 'Literatura Infantil'],
            ['nome_categoria' => 'Autoajuda'],
            ['nome_categoria' => 'Biografia'],
            ['nome_categoria' => 'História'],
            ['nome_categoria' => 'Ciências'],
            ['nome_categoria' => 'Tecnologia'],
            ['nome_categoria' => 'Educação'],
            ['nome_categoria' => 'Poesia'],
            ['nome_categoria' => 'Drama'],
            ['nome_categoria' => 'Aventura']
        ];

        foreach ($categorias as $categoria) {
            CategoriaModel::create($categoria);
        }
    }
}
