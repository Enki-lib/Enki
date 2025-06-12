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
           $usuario = User::create([
                'nome' => $request -> nome,
                'sobrenome' => $request -> sobrenome,
                'cpf' => $request -> cpf,
                'data_nascimento' => $request -> data_nascimento,
                'senha' => $request -> senha,
                'email' => $request -> email,
                'rua' => $request -> rua,
                'numero' => $request -> numero,
                'bairro' => $request -> bairro,
                'cidade' => $request -> cidade,
                'complemento' => $request -> complemento,
                'estado' => $request -> estado
            ]);

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
            $usuario->update([
                'nome' => $request -> nome,
                'sobrenome' => $request -> sobrenome,
                'cpf' => $request -> cpf,
                'data_nascimento' => $request -> data_nascimento,
                'senha' => $request -> senha,
                'email' => $request -> email,
                'rua' => $request -> rua,
                'numero' => $request -> numero,
                'bairro' => $request -> bairro,
                'cidade' => $request -> cidade,
                'complemento' => $request -> complemento,
                'estado' => $request -> estado
            ]);

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
