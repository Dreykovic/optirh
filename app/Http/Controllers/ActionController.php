<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ActionController extends Controller
{
    public function index(Request $request, $actionType = 'DEPOSIT')
    {
        try {
            $currentUser = Auth::user();

            $assistants = User::where('profile', 'ASSISTANT')->get();

            $query = Transaction::with(['employee', 'assistant', 'account'])->where('type', '=', $actionType)->where('employee_id', '=', $currentUser->getAuthIdentifier())->orderBy('transaction_date', 'DESC');

            $transactions = $query->get();
            $today = Carbon::today()->toDateString(); // Format 'YYYY-MM-DD'

            switch ($actionType) {
                case 'WITHDRAWAL':
                    $clients = User::where('profile', 'CLIENT')->get();
                    $clients = User::where('profile', 'CLIENT')->whereHas('accounts', function ($query) {
                        $query->where('type', ['SAVINGS', 'TONTINE']);
                    })->get();

                    return view('pages.admin.action.withdraw', compact('transactions', 'assistants', 'clients', 'today'));
                case 'REFUND':
                    $clients = User::where('profile', 'CLIENT')->whereHas('credits')->get();

                    return view('pages.admin.action.refund', compact('transactions', 'assistants', 'clients', 'today'));

                default:
                    $clients = User::where('profile', 'CLIENT')->whereHas('accounts', function ($query) {
                        $query->where('type', '=', 'SAVINGS');
                    })->get();

                    return view('pages.admin.action.deposit', compact('transactions', 'clients', 'today'));
            }
        } catch (\Throwable $th) {
            dd($th->getMessage());

            return view('errors.500');
        }
    }

    public function contribution($assistantId)
    {
        // try {
        $currentUser = Auth::user();

        $assistant = User::find($assistantId);

        $query = Transaction::with(['employee', 'assistant', 'account'])->where('type', '=', 'CONTRIBUTION')
        ->where('employee_id', '=', $currentUser->getAuthIdentifier())
        ->orderBy('transaction_date', 'DESC')
        ->where('assistant_id', '=', $assistantId);

        $transactions = $query->get();
        $today = Carbon::today()->toDateString(); // Format 'YYYY-MM-DD'
        $clients = User::where('profile', 'CLIENT')->whereHas('accounts', function ($query) {
            $query->where('type', '=', 'TONTINE');
        })->get();

        // code...
        return view('pages.admin.action.contribution', compact('transactions', 'assistant', 'clients', 'today'));
        // } catch (\Throwable $th) {
        //     return view('errors.500');
        // }
    }

    public function assistants()
    {
        try {
            // AJouter les with
            $assistants = User::where('profile', 'ASSISTANT')->get();

            // code...
            return view('pages.admin.action.assistant', compact('assistants'));
        } catch (\Throwable $th) {
            dd($th->getMessage());

            return view('errors.500');
        }
    }

    public function withdraw(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:0.01',

                'account_id' => 'required|exists:accounts,id',
                'client_id' => 'required|exists:users,id',
                'employee_id' => 'required|exists:users,id',
            ], [
                'amount.required' => 'Le montant est obligatoire.',
                'amount.numeric' => 'Le montant doit être un nombre.',
                'amount.min' => 'Le montant doit être supérieur à 0,01.',

                'client_id.required' => 'Vous devez choisir un client',
                'client_id.exists' => 'Le client sélectionné est introuvable dans la base de données.',

                'employee_id.required' => 'L\'identifiant de l\'employé est obligatoire.',
                'employee_id.exists' => 'L\'employé sélectionné est introuvable dans la base de données.',
            ]);
            $account = Account::find($request->input('account_id'));
            $transaction = new Transaction();
            $transaction->fill([
                'type' => 'WITHDRAWAL',
                'transaction_date' => now(),
                'account_id' => $request->input('account_id'),
                'account_type' => $account->type,
                'employee_id' => $request->input('employee_id'),
                'amount' => $request->input('amount'),
            ]);
            // Vérifier l'état du compte
            switch ($account->state) {
                case 'INACTIVE':
                    // code...
                    break;

                default:
                    // code...
                    break;
            }
            if (!$account->balance >= $request->input('amount')) {
                $transaction->status = 'FAILED';
                $transaction->save();

                return response()->json(['ok' => false, 'message' => 'Retrait échoué, Solde Insuffisant! Solde actuel: '.$account->balance]);
            }
            if ($account->state != 'ACTIVE') {
                $transaction->status = 'CANCELED';
                $transaction->save();

                return response()->json(['ok' => false, 'message' => 'Retrait échoué']);
            }

            $account->balance -= $transaction->amount;
            $account->save();
            $transaction->status = 'COMPLETED';
            $transaction->save();
            // dd($transaction);

            return response()->json(['ok' => true, 'message' => 'Retrait enregistré avec succès!']);
        } catch (ValidationException $e) {
            // Retourner les messages d'erreur de validation
            return response()->json([
                'ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(), // Contient tous les messages d'erreur de validation
            ], 422);
        } catch (\Exception $e) {
            // Retourner un message d'erreur générique pour les autres exceptions
            return response()->json([
                'ok' => false,
                'message' => 'Une erreur s\'est produite. Veuillez réessayer.',
            ], 500);
        }
    }

    public function contribute(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:0.01',

                'client_id' => 'required|exists:users,id',
                'employee_id' => 'required|exists:users,id',
                'assistant_id' => 'exists:users,id',
                'transaction_date' => 'required|date', // Ajout de la validation pour transaction_date
            ], [
                'amount.required' => 'Le montant est obligatoire.',
                'amount.numeric' => 'Le montant doit être un nombre.',
                'amount.min' => 'Le montant doit être supérieur à 0,01.',

                'client_id.required' => 'Vous devez choisir un client',
                'client_id.exists' => 'Le client sélectionné est introuvable dans la base de données.',

                'employee_id.required' => 'L\'identifiant de l\'employé est obligatoire.',
                'employee_id.exists' => 'L\'employé sélectionné est introuvable dans la base de données.',

                'assistant_id.exists' => 'L\'assistant sélectionné est introuvable dans la base de données.',

                'transaction_date.required' => 'La date de la transaction est obligatoire.', // Message d'erreur personnalisé
                'transaction_date.date' => 'La date de la transaction doit être une date valide.', // Message pour une date non valide
            ]);
            $account = Account::where('client_id', '=', $request->input('client_id'))
            ->where('type', 'TONTINE')
            ->first();
            if (!$account) {
                return response()->json(['ok' => false, 'message' => 'Aucun compte trouvé pour ce client et ce type de compte merci.'], 404);
            }
            $transaction = new Transaction();
            $transaction->fill([
                'type' => 'CONTRIBUTION',
                'transaction_date' => $request->input('transaction_date'),
                'account_id' => $account->id,
                'account_type' => $account->type,
                'employee_id' => $request->input('employee_id'),
                'amount' => $request->input('amount'),
                'assistant_id' => $request->input('assistant_id'),
            ]);
            // Vérifier l'état du compte
            switch ($account->state) {
                case 'INACTIVE':
                    // code...
                    break;

                default:
                    // code...
                    break;
            }

            if ($account->state != 'ACTIVE') {
                $transaction->status = 'CANCELED';
                $transaction->save();

                return response()->json(['ok' => false, 'message' => 'Cotisation échoué']);
            }

            $account->balance += $transaction->amount;
            $account->save();
            $transaction->status = 'COMPLETED';
            $transaction->save();
            // dd($transaction);

            return response()->json(['ok' => true, 'message' => 'Dépôt enregistré avec succès!']);
        } catch (ValidationException $e) {
            // Retourner les messages d'erreur de validation
            return response()->json([
                'ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(), // Contient tous les messages d'erreur de validation
            ], 422);
        } catch (\Exception $e) {
            // Retourner un message d'erreur générique pour les autres exceptions
            return response()->json([
                'ok' => false,
                'message' => 'Une erreur s\'est produite. Veuillez réessayer.',
            ], 500);
        }
    }

    public function deposit(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:0.01',

                'client_id' => 'required|exists:users,id',
                'employee_id' => 'required|exists:users,id',
                'transaction_date' => 'required|date', // Ajout de la validation pour transaction_date
            ], [
                'amount.required' => 'Le montant est obligatoire.',
                'amount.numeric' => 'Le montant doit être un nombre.',
                'amount.min' => 'Le montant doit être supérieur à 0,01.',

                'client_id.required' => 'Vous devez choisir un client',
                'client_id.exists' => 'Le client sélectionné est introuvable dans la base de données.',

                'employee_id.required' => 'L\'identifiant de l\'employé est obligatoire.',
                'employee_id.exists' => 'L\'employé sélectionné est introuvable dans la base de données.',

                'transaction_date.required' => 'La date de la transaction est obligatoire.', // Message d'erreur personnalisé
                'transaction_date.date' => 'La date de la transaction doit être une date valide.', // Message pour une date non valide
            ]);
            $account = Account::where('client_id', '=', $request->input('client_id'))
            ->where('type', 'SAVINGS')
            ->first();
            if (!$account) {
                return response()->json(['ok' => false, 'message' => 'Aucun compte trouvé pour ce client et ce type de compte merci.'], 404);
            }
            $transaction = new Transaction();
            $transaction->fill(attributes: [
                'type' => 'DEPOSIT',
                'transaction_date' => $request->input('transaction_date'),
                'account_id' => $account->id,
                'account_type' => $account->type,
                'employee_id' => $request->input('employee_id'),
                'amount' => $request->input('amount'),
            ]);
            // Vérifier l'état du compte
            switch ($account->state) {
                case 'INACTIVE':
                    // code...
                    break;

                default:
                    // code...
                    break;
            }

            if ($account->state != 'ACTIVE') {
                $transaction->status = 'CANCELED';
                $transaction->save();

                return response()->json(['ok' => false, 'message' => 'Dépôt échoué']);
            }

            $account->balance += $transaction->amount;
            $account->save();
            $transaction->status = 'COMPLETED';
            $transaction->save();
            // dd($transaction);

            return response()->json(['ok' => true, 'message' => 'Dépôt enregistré avec succès!']);
        } catch (ValidationException $e) {
            // Retourner les messages d'erreur de validation
            return response()->json([
                'ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(), // Contient tous les messages d'erreur de validation
            ], 422);
        } catch (\Exception $e) {
            // Retourner un message d'erreur générique pour les autres exceptions
            return response()->json([
                'ok' => false,
                'message' => 'Une erreur s\'est produite. Veuillez réessayer.',
            ], 500);
        }
    }
}
