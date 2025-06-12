<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmprestimoRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Emprestimo;
use App\Models\LivroModel;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EmprestimoController extends Controller
{
    public function index(): JsonResponse
    {
        $emprestimos = Emprestimo::with(['livro', 'usuario'])->get();
        return response()->json([
            'status' => true,
            'emprestimos' => $emprestimos
        ], 200);
    }

    public function show(Emprestimo $emprestimo): JsonResponse
    {
        $emprestimo->load(['livro', 'usuario']);
        return response()->json([
            'status' => true,
            'emprestimo' => $emprestimo
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            // Get the book
            $livro = LivroModel::findOrFail($request->livro_codigo_livro);

            // Check if book is available
            if (!$livro->isAvailable()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Este livro não está disponível para empréstimo.'
                ], 400);
            }

            // Check if user has active loans
            $userActiveLoans = Emprestimo::where('usuario_matricula_usuario', Auth::id())
                ->where('status_emprestimo', 'Em aberto')
                ->count();

            if ($userActiveLoans >= 3) {
                return response()->json([
                    'status' => false,
                    'message' => 'Você atingiu o limite máximo de 3 empréstimos ativos.'
                ], 400);
            }

            // Check if user has overdue books or pending fines
            $overdueLoans = Emprestimo::where('usuario_matricula_usuario', Auth::id())
                ->where('status_emprestimo', 'Em aberto')
                ->where('data_devolucao', '<', now())
                ->exists();

            if ($overdueLoans) {
                return response()->json([
                    'status' => false,
                    'message' => 'Você possui livros em atraso. Por favor, devolva-os antes de realizar novo empréstimo.'
                ], 400);
            }

            $pendingFines = Emprestimo::where('usuario_matricula_usuario', Auth::id())
                ->where('multa_emprestimo', '>', 0)
                ->exists();

            if ($pendingFines) {
                return response()->json([
                    'status' => false,
                    'message' => 'Você possui multas pendentes. Por favor, quite-as antes de realizar novo empréstimo.'
                ], 400);
            }

            // Create the loan
            $emprestimo = Emprestimo::create([
                'livro_codigo_livro' => $request->livro_codigo_livro,
                'usuario_matricula_usuario' => Auth::id(),
                'data_emprestimo' => now(),
                'status_emprestimo' => 'Em aberto',
                'multa_emprestimo' => 0,
                'num_renovacoes' => 0
            ]);

            // * Banco de dados contém um trigger que atualiza data de devolução e status do livro

            DB::commit();
            
            return response()->json([
                'status' => true,
                'emprestimo' => $emprestimo,
                'message' => 'Empréstimo realizado com sucesso'
            ], 201);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Erro ao registrar empréstimo',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function update(Request $request, Emprestimo $emprestimo): JsonResponse
    {
        DB::beginTransaction();
        try {
            // Verify if the user is the one who borrowed the book
            if ($emprestimo->usuario_matricula_usuario !== Auth::id()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Você não tem permissão para atualizar este empréstimo'
                ], 403);
            }

            if ($request->action === 'devolver') {
                // Update loan status
                $emprestimo->update([
                    'status_emprestimo' => 'Devolvido',
                    'data_devolucao' => now()
                ]);

                // Update book status
                $emprestimo->livro->update([
                    'status' => 'Disponível'
                ]);
                
                $message = 'Livro devolvido com sucesso';
            } 
            elseif ($request->action === 'renovar') {
                if (!$emprestimo->canBeRenewed()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Este empréstimo não pode ser renovado. Verifique o número de renovações ou multas pendentes.'
                    ], 400);
                }

                // Update loan return date and increment renewals
                $emprestimo->update([
                    'data_devolucao' => now()->addDays(15),
                    'num_renovacoes' => $emprestimo->num_renovacoes + 1
                ]);

                $message = 'Empréstimo renovado com sucesso';
            }
            else {
                return response()->json([
                    'status' => false,
                    'message' => 'Ação inválida'
                ], 400);
            }

            DB::commit();
            return response()->json([
                'status' => true,
                'emprestimo' => $emprestimo->fresh(['livro', 'usuario']),
                'message' => $message
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Erro ao atualizar empréstimo',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function destroy(Emprestimo $emprestimo): JsonResponse
    {
        try {
            // * Apenas permite deleção de livros emprestados
            if ($emprestimo->status_emprestimo === 'Em aberto') {
                return response()->json([
                    'status' => false,
                    'message' => 'Não é possível excluir um empréstimo em aberto'
                ], 400);
            }

            $emprestimo->delete();
            return response()->json([
                'status' => true,
                'message' => 'Empréstimo deletado com sucesso'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao deletar dados'
            ], 422);
        }
    }
}
