<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmprestimoRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Emprestimo;
use Exception;
use Illuminate\Support\Facades\DB;

class EmprestimoController extends Controller
{
    public function index () : JsonResponse
     {
        $emprestimos = Emprestimo::all();
        return response() -> json([
            'status' => true,
            'emprestimos' => $emprestimos
        ], 200);
    }

    public function show (Emprestimo $emprestimo) : JsonResponse {
        return response() -> json ([
            'status' => true,
            'emprestimo' => $emprestimo
        ], 200);
    }

    public function store (EmprestimoRequest $request) : JsonResponse {
        DB::beginTransaction();
        try {
            $emprestimo = Emprestimo::create([
                'livro_codigo_livro' => $request -> livro_codigo_livro,
                'usuario_matricula_usuario' => $request -> usuario_matricula_usuario,
                'data_emprestimo' => $request -> data_emprestimo
            ]);
            DB::commit();
            
            return response () -> json([
                'status' => true,
                'emprestimo' => $emprestimo,
                'message' => 'Empréstimo realizado com sucesso'
            ], 201);
        } catch (Exception $e) {
            DB::rollback();
            return response () -> json ([
                'status' => false,
                'message' => 'Erro ao registrar empréstimo',
                'exception' => get_class($e),
                'error' => $e -> getMessage(),
                'trace' => $e -> getTrace()
            ], 400);
        }
    }

    public function update (EmprestimoRequest $request, Emprestimo $emprestimo) :JsonResponse {
        DB::beginTransaction();
        try {
            $emprestimo -> update ([
                'livro_codigo_livro' => $request -> livro_codigo_livro,
                'usuario_matricula_usuario' => $request -> usuario_matricula_usuario,
                'data_emprestimo' => $request -> data_emprestimo
            ]);
            DB::commit();
            return response () -> json ([
                'status' => true,
                'emprestimo' => $emprestimo,
                'message' => 'Atualizado com sucesso'
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response () -> json ([
                'status' => false,
                'message' => 'Erro ao atualizar',
                'exception' => get_class($e),
                'error' => $e -> getMessage(),
                'trace' => $e -> getTrace()
            ], 422);
        }
    }

    public function destroy (Emprestimo $emprestimo) : JsonResponse {
        try {
            $emprestimo -> delete();
                return response () -> json ([
                'status' => true,
                'message' => 'Empréstimo deletado com sucesso'
            ], 200);
        } catch (Exception $e) {
            return response () -> json ([
                'status' => true,
                'message' => 'Erro ao deletar dados'
            ], 422);
        }
    }
}
