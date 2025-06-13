<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index () : JsonResponse 
    {
        $users = User::orderBy('matricula', 'ASC')->paginate(5);  // trocar o get() por paginate(numPorPaginas) ativa a paginação
        return response()-> json([                          // Query para buscar páginas: /api/usuarios?page=<numDaPagina>
            'status'=> true,
            'usuários' => $users
        ], 200);
    }

    public function show (User $usuario) : JsonResponse
    {
        return response()-> json([
            'status' => true,
            'usuario' => $usuario,
        ], 200);
    }

    public function store (UserRequest $request) : JsonResponse
    {
        DB::beginTransaction();
        try  {
            $validated = $request->validated();
            $usuario = new User();
            $usuario->nome = $validated['nome'];
            $usuario->sobrenome = $validated['sobrenome'];
            $usuario->cpf = $validated['cpf'];
            $usuario->data_nascimento = $validated['data_nascimento'];
            $usuario->senha = $validated['senha'];
            $usuario->email = $validated['email'];
            $usuario->rua = $validated['rua'];
            $usuario->numero = $validated['numero'];
            $usuario->bairro = $validated['bairro'];
            $usuario->cidade = $validated['cidade'];
            $usuario->complemento = $validated['complemento'] ?? null;
            $usuario->estado = $validated['estado'];
            $usuario->save();

            // Gerar token API
            $token = Str::random(60);
            $usuario->api_token = $token;
            $usuario->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'user' => [
                    'id' => $usuario->matricula,
                    'name' => $usuario->nome,
                    'email' => $usuario->email,
                ],
                'token' => $token,
                'message' => 'Usuário cadastrado com sucesso',
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => "Erro ao cadastrar usuário: {$e -> getMessage()}"
            ], 400);
        }
    }

    public function update (UserRequest $request, User $usuario) : JsonResponse
    { 
        DB::beginTransaction();

        try {
            $validated = $request->validated();
            $usuario->nome = $validated['nome'];
            $usuario->sobrenome = $validated['sobrenome'];
            $usuario->cpf = $validated['cpf'];
            $usuario->data_nascimento = $validated['data_nascimento'];
            $usuario->senha = $validated['senha'];
            $usuario->email = $validated['email'];
            $usuario->rua = $validated['rua'];
            $usuario->numero = $validated['numero'];
            $usuario->bairro = $validated['bairro'];
            $usuario->cidade = $validated['cidade'];
            $usuario->complemento = $validated['complemento'] ?? null;
            $usuario->estado = $validated['estado'];
            $usuario->save();

            DB::commit();

            return response() -> json([
                'status' => true,
                'usuario' => $usuario,
                'message' => 'Usuário editado com sucesso',
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response() -> json([
                'status' => false,
                'message' => "Erro ao editar usuário: {$e -> getMessage()}"
            ], 400);
        }
        
    }

    public function destroy (User $usuario) : JsonResponse
    {  
        try {
            $usuario->delete();

            return response() -> json([
                'status' => true,
                'user' => $usuario,
                'message' => 'Usuário deletado com sucesso'
            ], 200);

        } catch (Exception $e) {
            return response() -> json([
                'status' => false,
                'message' => "Erro ao excluir usuário: {$e -> getMessage()}"
            ], 400);
        }
    }
}
