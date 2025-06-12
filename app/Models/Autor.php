<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    protected $table = 'autor';
    protected $primaryKey = 'codigo_autor';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'nome_autor'
    ];

    // Relationships
    public function livros()
    {
        return $this->belongsToMany(
            LivroModel::class,
            'autor_livro',
            'autor_codigo_autor',
            'livro_codigo_livro',
            'codigo_autor',
            'codigo_livro'
        );
    }
} 