<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaModel extends Model
{
    protected $table = 'categoria';
    protected $primaryKey = 'id_categoria';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'nome_categoria'
    ];

    public function livros()
    {
        return $this->hasMany(LivroModel::class, 'id_categoria', 'id_categoria');
    }
} 