<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'usuario';
    protected $primaryKey = 'matricula';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'nome',
        'sobrenome',
        'cpf',
        'data_nascimento',
        'senha',
        'email',
        'rua',
        'numero',
        'bairro',
        'cidade',
        'complemento',
        'estado',
        'api_token'
    ];

    public function emprestimos () {
        return $this->hasMany(Emprestimo::class, 'usuario_matricula_usuario', 'matricula');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'senha',
        'api_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'senha' => 'hashed',
            'data_nascimento' => 'date',
        ];
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'matricula';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getAttribute($this->getAuthIdentifierName());
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->senha;
    }
}
