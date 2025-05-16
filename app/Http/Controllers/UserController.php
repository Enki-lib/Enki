<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function show (User $user) : JsonResponse
    {
        return response()-> json([
            'status' => true,
            'usuario' => $user,
        ], 200);
    }

    public function store (UserRequest $request) : JsonResponse
    {
        DB::beginTransaction();
        try  {
           $user = User::create([
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

            return response()->json([
            'status' => true,
            'user' => $user,
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

    public function update (UserRequest $request, User $user) : JsonResponse
    { 

        DB::beginTransaction();

        try {
            $user->update([
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
                'user' => $user,
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

    public function destroy (User $user) : JsonResponse
    {  
        try {
            $user->delete();

            return response() -> json([
                'status' => true,
                'user' => $user,
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
