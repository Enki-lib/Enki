<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class LivroModel extends Model
{
    use HasFactory, Notifiable;


    protected $table = 'livro';
    protected $primaryKey = 'codigo_livro';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'id_categoria',
        'titulo_livro',
        'edicao_livro',
        'ano_publicacao',
        'assunto',
        'ISBN'
    ];
}
