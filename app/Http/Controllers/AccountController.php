<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountType;
use App\Models\Client;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    public function index()
    {
        try {
            $accounts = Account::all();
            $assistants = User::where('profile', 'ASSISTANT')->get();
            $clients = Client::all();
            $accountTypes = AccountType::all();
            $today = Carbon::today()->toDateString(); // Format 'YYYY-MM-DD'

            return view('pages/admin/account.index', compact('assistants', 'today', 'accounts', 'clients', 'accountTypes'));
        } catch (\Throwable $th) {
            dd($th->getMessage());
            abort(500);
        }
    }

    public function cotisations($accountId)
    {
        try {
            $account = Account::with(['transactions'])->where('id', $accountId)->get();

            $today = Carbon::today()->toDateString(); // Format 'YYYY-MM-DD'

            return view('pages/admin/account.cotisations', compact('today', 'account'));
        } catch (\Throwable $th) {
            dd($th->getMessage());
            abort(500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Valider les fichiers et l'image
            $request->validate([
                'account_type' => 'required|exists:account_types,id',
                'initial_balance' => 'required|numeric|min:0.01',
                'client_id' => 'required|exists:users,id',
                'employee_id' => 'required|exists:users,id',
                'assistant_id' => 'nullable|exists:users,id',
                'fees' => 'nullable|numeric|min:0.01',
                'creation_date' => 'required|date',
            ]);

            $accountType = AccountType::find($request->input('account_type'));
            // Création de l'article
            if ($request->input('initial_balance') < $accountType->minimum_account_balance) {
                return response()->json(['ok' => false,
                    'message' => 'Solde initiale doit être supérieur à '.$accountType->minimum_account_balance,
                ], 401);
            }

            $account = Account::create([
                'account_type_id' => $request->input('account_type'),
                'creation_date' => $request->input('creation_date'),
                'current_balance' => $request->input('initial_balance'),
                'employee_id' => $request->input('employee_id'),
                'assistant_id' => $request->input('assistant_id'),
                'client_id' => $request->input('client_id'),
                'fees' => $request->input('fees') ?? 0,
            ]);

            // Redirection avec message de succès
            return response()->json(['message' => 'Compte créé avec succès.', 'ok' => true]);
        } catch (ValidationException $e) {
            // Gestion des erreurs de validation
            // return response()->json(['ok' => false, 'errors' => $e->errors(), 'message' => 'Données invalides. Veuillez vérifier votre saisie.']);
            return response()->json(['ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(), // Contient tous les messages d'erreur de validation
            ], 422);
        } catch (\Throwable $th) {
            // return response()->json(['ok' => false,  'message' => 'Une erreur s\'est produite. Veuillez réessayer.'], 500);

            return response()->json(['ok' => false,  'message' => $th->getMessage()], 500);
        }
    }
}
