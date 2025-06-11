<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LivroRequest extends FormRequest
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
        return [
            'id_categoria' => 'required|integer',
            'titulo_livro' => 'required',
            'edicao_livro' => 'required',
            'ano_publicacao' => 'required|date',
            'assunto' => 'required',
            'ISBN' => 'required|numeric'
        ];
    }

    public function messages() : array 
    {
        return [
            'id_categoria.required' => 'Campo id_ategoria é obrigatório',
            'id_categoria.numeric' => 'Campo id_categoria deve ser numérico',
            'titulo_livro.required' => 'Campo titulo_livro é obrigatório',
            'edicao_livro.required' => 'Campo edicao_livro é obrigatório',
            'ano_publicacao.required' => 'Campo ano_publicacao é obrigatório',
            'ano_publicacao.date' => 'Campo ano_publicacao deve ser uma data no formato AAAA/MM/DD',
            'assunto.required' => 'Campo assunto é obrigatório',
            'ISBN.required' => 'Campo ISBN é obrigatório',
            'ISBN.numeric' => 'Campo ISBN deve ser numérico'
        ];
    }
}
