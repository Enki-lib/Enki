<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmprestimoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $codigo_emprestimo = $this->route('emprestimo');
        return [
            'livro_codigo_livro' => 'required|integer|exists:livro,codigo_livro',
            'usuario_matricula_usuario' => 'required|integer|exists:usuario,matricula',
            'data_emprestimo' => 'required|date'
        ];
    }

    public function messages () : array {
        return [
            'livro_codigo_livro.required' => 'Código do livro é obrigatório',
            'livro_codigo_livro.integer' => 'Código do livro deve ser numérico',
            'livro_codigo_livro.exists' => 'O livro informado não existe na base de dados',

            'usuario_matricula_usuario.required' => 'matrícula do usuário é obrigatório',
            'usuario_matricula_usuario.integer' => 'matrícula do usuário deve ser numérico',
            'usuario_matricula_usuario.exists' => 'O usuário informado não existe na base de dados',

            'data_emprestimo.required' => 'Campo de data do empréstimo é obrigatório',
            'data_emprestimo.date' => 'Campo data deve ter formato AAAA-MM-DD'
        ];
    }
}
