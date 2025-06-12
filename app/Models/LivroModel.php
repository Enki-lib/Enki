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
        'ISBN',
        'status'
    ];

    protected $casts = [
        'ano_publicacao' => 'date'
    ];

    // Relationships
    public function categoria()
    {
        return $this->belongsTo(CategoriaModel::class, 'id_categoria', 'id_categoria');
    }

    public function autores()
    {
        return $this->belongsToMany(
            Autor::class,
            'autor_livro',
            'livro_codigo_livro',
            'autor_codigo_autor',
            'codigo_livro',
            'codigo_autor'
        );
    }

    public function emprestimos()
    {
        return $this->hasMany(Emprestimo::class, 'livro_codigo_livro', 'codigo_livro');
    }

    public function emprestimoAtivo()
    {
        return $this->emprestimos()
            ->where('status_emprestimo', 'Em aberto')
            ->first();
    }

    // Helper methods
    public function isAvailable(): bool
    {
        return $this->status === 'Disponível';
    }

    public function isReserved(): bool
    {
        return $this->status === 'Reservado';
    }

    public function isBorrowed(): bool
    {
        return $this->status === 'Emprestado';
    }
}
