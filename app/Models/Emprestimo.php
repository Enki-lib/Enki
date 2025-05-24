<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emprestimo extends Model
{
    protected $table = 'emprestimo';
    protected $primaryKey = 'codigo_emprestimo';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'livro_codigo_livro',
        'usuario_matricula_usuario',
        'data_emprestimo',
        'data_devolucao',
        'status_emprestimo',
        'multa_emprestimo'
    ];

    protected $guarded = [
        'status_emprestimo',
        'multa_emprestimo',
        'data_devolucao',
    ];


    // * EQUIVALE A CONSTRAINT FOREIGN KEY (livro_codigo_livro) REFERENCES livro(codigo_livro) ON UPDATE CASCADE ON DELETE SET NULL
    public function livro () {
        return $this->belongsTo(LivroModel::class, 'livro_codigo_livro', 'codigo_livro');
    }
    
    // * EQUIVALE A CONSTRAINT FOREIGN KEY (usuario_matricula_usuario) REFERENCES usuario(matricula) ON UPDATE CASCADE ON DELETE CASCADE);
    public function usuario () {
        return $this->belongsTo(User::class, 'usuario_matricula_usuario', 'matricula_usuario');
    }
}
