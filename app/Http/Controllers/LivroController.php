<?php

namespace App\Http\Controllers;

use App\Http\Requests\LivroRequest;
use App\Models\LivroModel;
use App\Models\Emprestimo;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LivroController extends Controller
{
    public function index(): JsonResponse
    {
        $livros = LivroModel::with(['categoria'])->get();
        return response()->json([
            'status' => true,
            'data' => $livros
        ], 200);
    }

    public function show(LivroModel $livro): JsonResponse
    {
        // Load relationships
        $livro->load(['categoria', 'autores']);

        // Get active loan information if exists
        $emprestimo = Emprestimo::where('livro_codigo_livro', $livro->codigo_livro)
            ->where('status_emprestimo', 'Em aberto')
            ->with('usuario:matricula,nome,sobrenome')
            ->first();

        $data = $livro->toArray();
        $data['emprestimo'] = null;
        $data['usuario_atual_id'] = Auth::id();

        if ($emprestimo) {
            $data['emprestimo'] = [
                'id' => $emprestimo->codigo_emprestimo,
                'data_emprestimo' => $emprestimo->data_emprestimo->format('Y-m-d'),
                'data_devolucao' => $emprestimo->data_devolucao->format('Y-m-d'),
                'usuario_id' => $emprestimo->usuario_matricula_usuario,
                'usuario_nome' => $emprestimo->usuario->nome . ' ' . $emprestimo->usuario->sobrenome,
                'status' => $emprestimo->status_emprestimo,
                'multa' => $emprestimo->multa_emprestimo,
                'num_renovacoes' => $emprestimo->num_renovacoes
            ];
        }

        return response()->json([
            'status' => true,
            'data' => $data
        ], 200);
    }

    public function store(LivroRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $livro = LivroModel::create($validated);

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $livro
            ], 201);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => "Erro ao registrar livro",
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function update(LivroRequest $request, LivroModel $livro): JsonResponse
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $livro->update($validated);

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $livro,
                'message' => 'Livro editado com sucesso'
            ], 200);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => "Erro ao editar informações do livro: {$e->getMessage()}"
            ], 422);
        }
    }

    public function destroy(LivroModel $livro): JsonResponse
    {
        try {
            $livro->delete();
            return response()->json([
                'status' => true,
                'livro' => $livro,
                'message' => 'Livro excluído com sucesso'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'livro' => $livro,
                'message' => "Erro ao deletar livro: {$e->getMessage()}"
            ], 422);
        }
    }
}
