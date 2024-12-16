<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Client;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function index($employeeId = null)
    {
        try {
            $assistants = User::where('profile', 'ASSISTANT')->get();

            $query = Transaction::with(['employee', 'assistant', 'account'])->orderBy('transaction_date', 'DESC');
            if ($employeeId) {
                // code...
                $query->where('employee_id', '=', $employeeId);
            }
            $transactions = $query->get();
            $today = Carbon::today()->toDateString(); // Format 'YYYY-MM-DD'

            $clients = Client::all();
            // dd($clients);

            return view('pages.admin.transaction.history', compact('transactions', 'assistants', 'clients', 'today'));
        } catch (\Throwable $th) {
            dd($th->getMessage());

            return view('errors.500');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:0.01',
                'transaction_type_id' => 'required|exists:transaction_types,id',
                'account_id' => 'required|exists:accounts,id',
                'employee_id' => 'required|exists:users,id',
            ]);
            $transaction_type_id = $request->input('transaction_type_id');
            $transaction_type = TransactionType::find($transaction_type_id);
            $account = Account::find($request->input('account_id'));
            if (!$account) {
                return response()->json(['ok' => false, 'message' => 'Aucun compte trouvé pour ce client et ce type de compte merci.'], 404);
            }
            $transaction = new Transaction();
            $transaction->fill([
                'transaction_type_id' => $transaction_type_id,
                'transaction_date' => now(),
                'account_id' => $request->input('account_id'),
                'employee_id' => $request->input('employee_id'),
                'amount' => $request->input('amount'),
            ]);
            // TODO: Vérifier l'état du compte

            if ($transaction_type->related_to === 'DEBIT') {
                if (!$account->balance >= $request->input('amount')) {
                    $transaction->status = 'FAILED';
                    $transaction->save();

                    return response()->json(['ok' => false, 'message' => 'Transaction échoué, Solde Insuffisant! Solde actuel: '.$account->balance]);
                }

                $account->current_balance -= $transaction->amount;
            } else {
                $account->current_balance += $transaction->amount;
            }
            $account->save();
            $transaction->status = 'COMPLETED';
            $transaction->save();

            return response()->json(['ok' => true, 'message' => 'Transaction ajoutée avec succès!']);
        } catch (ValidationException $e) {
            // Retourner les messages d'erreur de validation
            return response()->json([
                'ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(), // Contient tous les messages d'erreur de validation
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => $e->getMessage(),
            ], 500);

            // Retourner un message d'erreur générique pour les autres exceptions
            return response()->json([
                'ok' => false,
                'message' => 'Une erreur s\'est produite. Veuillez réessayer.',
            ], 500);
        }
    }

    public function getClientAccounts($clientId)
    {
        try {
            $accounts = Account::where('client_id', $clientId)->with('account_type')->get();

            return response()->json(['ok' => true, 'accounts' => $accounts]);
        } catch (\Exception $e) {
            // return response()->json(['ok' => false, 'message' => $e->getMessage()]);

            return response()->json(['ok' => false, 'message' => 'Une erreur s\'est produite. Veuillez réessayer.']);
        }
    }

    public function getRelatedTransactionTypes($relatedTo)
    {
        try {
            $transactionTypes = TransactionType::where('related_to', $relatedTo)->get();

            return response()->json(['ok' => true, 'transactionTypes' => $transactionTypes]);
        } catch (\Exception $e) {
            // return response()->json(['ok' => false, 'message' => $e->getMessage()]);

            return response()->json(['ok' => false, 'message' => 'Une erreur s\'est produite. Veuillez réessayer.']);
        }
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return response()->json(['success' => 'Transaction supprimée avec succès']);
    }
}
