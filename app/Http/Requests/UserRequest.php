<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use PhpParser\Node\Expr\Cast\Array_;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'error' => $validator->errors(),
        ], 422));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = request()->route('usuario');
        return [
            'nome' => 'required',
            'sobrenome' => 'required',
            'cpf' => 'required|min:11|max:11|unique:usuario,cpf,' . ($userId ? $userId->matricula : 'null') . ',matricula',
            'data_nascimento' => 'required|date',
            'senha' => 'required|min:8',
            'email' => 'required|email|unique:usuario,email,' . ($userId ? $userId->matricula : 'NULL') . ',matricula',
            'rua' => 'required',
            'numero' => 'required',
            'bairro' => 'required',
            'cidade' => 'required',
            'complemento',
            'estado' => 'required|min:2|max:2'
        ];
    }

    public function messages() : array
    {
        return [
            'nome.required' => 'Campo nome é obrigatório',
            'sobrenome.required' => 'Campo Sobrenome é obrigatório',
            'cpf.required' => 'Campo CPF é obrigatório',
            'cpf.min' => 'CPF deve conter 11 dígitos',
            'cpf.max' => 'CPF deve conter 11 dígitos',
            'cpf.unique' => 'CPF já registrado na base de dados',
            'data_nascimento' => 'Campo data de nascimento é obrigatório',
            'data_nascimento.date' => 'Campo data de nascimento deve ter formato AAAA-MM-DD',
            'senha.required' => 'Campo senha é obrigatório',
            'senha.min' => 'Senha deve ter no mínimo 8 caracteres',
            'email.required' => 'Campo email é obrigatório',
            'email.email' => 'Campo email deve ser um email válido',
            'email.unique' => 'O email já está cadastrado',
            'rua.required' => 'Campo rua é obrigatório',
            'numero.required' => 'Campo número é obrigatório',
            'bairro.required' => 'Campo bairro é obrigatório',
            'cidade.required' => 'Campo cidade é obrigatório',
            'estado.required' => 'Campo estado é obrigatório',
            'estado.min' => 'Campo estado deve conter apenas 2 letras',
            'estado.max' => 'Campo estado deve conter apenas 2 letras'
        ];
    }
}
