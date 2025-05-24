<?php

namespace App\Http\Controllers;

use App\Http\Requests\LivroRequest;
use App\Models\LivroModel;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LivroController extends Controller
{
    public function index () : JsonResponse
    {
        $livros = LivroModel::all();
        return response() -> json([
            'status' => true,
            'livros' => $livros
        ], 200);
    }

    public function show (LivroModel $livro) : JsonResponse
    {
        return response () -> json ([
            'status' => true,
            'livro' => $livro
        ], 200);
    }

    public function store (LivroRequest $request) : JsonResponse
    {
        DB::beginTransaction();
        try {
            $livro = LivroModel::create([
                'id_categoria' => $request -> id_categoria,
                'titulo_livro' => $request -> titulo_livro,
                'edicao_livro' => $request -> edicao_livro,
                'ano_publicacao' => $request -> ano_publicacao,
                'assunto' => $request -> assunto,
                'ISBN' => $request -> ISBN
            ]);

            DB::commit();

            return response () -> json ([
                'status' => true,
                'livro' => $livro
            ], 201);

        // } catch (Exception $e) {
        //     DB::rollback();
        //     return response () -> json ([
        //         'status' => false,
        //         'message' => "Erro ao registrar livro: {$e -> getMessage()}"
        //     ], 422);
        // }

        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => "Erro ao registrar livro",
                'exception' => get_class($e),
                'error' => $e->getMessage(),
                'trace' => $e->getTrace()
            ], 422);
        }
        
    }

    public function update (LivroRequest $request, LivroModel $livro) : JsonResponse
    {
        DB::beginTransaction ();
        try {
            $livro->update ([
                'id_categoria' => $request -> id_categoria,
                'titulo_livro' => $request -> titulo_livro,
                'edicao_livro' => $request -> edicao_livro,
                'ano_publicacao' => $request -> ano_publicacao,
                'assunto' => $request -> assunto,
                'ISBN' => $request -> ISBN
            ]);

            DB::commit();

            return response () -> json ([
                'status' => true,
                'livro' => $livro,
                'message' => 'Livro editado com sucesso'
            ], 200);

        } catch (Exception $e) {
            DB::rollback ();
            return response () -> json ([
                'status' => false,
                'message' => "Erro ao editar informações do livro: {$e -> getMessage()}"
            ], 422);
        }
    }

    public function destroy (LivroModel $livro) : JsonResponse
    {
        try {
            $livro -> delete();
            return response () -> json ([
                'status' => true,
                'livro' => $livro,
                'message' => 'Livro excluído com sucesso'
            ]);
        } catch (Exception $e) {
            return response () -> json ([
                'status' => false,
                'livro' => $livro,
                'message' => "Erro ao deletar livro: {$e -> getMessage()}"
            ], 422);
        }
    }
}
