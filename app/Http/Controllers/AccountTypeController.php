<?php

namespace App\Http\Controllers;

use App\Models\AccountType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AccountTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $accountTypes = AccountType::all();
            $today = Carbon::today()->toDateString(); // Format 'YYYY-MM-DD'

            return view('pages.admin.account.types', compact('today', 'accountTypes'));
        } catch (\Throwable $th) {
            dd($th->getMessage());
            abort(500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Valider les fichiers et l'image
            $request->validate([
                'name' => 'required|string|max:255',
                'account_number_prefix' => 'nullable|string|max:10',
                'starting_account_number' => 'required|integer',
                'next_account_number' => 'required|integer',
                'currency' => 'required|string|max:10',
                'interest_rate' => 'nullable|numeric|min:0',
                'interest_method' => 'nullable|string|max:50',
                'interest_period' => 'nullable|string|max:50',
                'minimum_balance_for_interest' => 'nullable|numeric|min:0',
                'allow_withdraw' => 'boolean',
                'minimum_deposit_amount' => 'nullable|numeric|min:0',
                'minimum_account_balance' => 'nullable|numeric|min:0',
                'maintenance_fee' => 'nullable|numeric|min:0',
                'maintenance_fee_posting_month' => 'nullable|string|max:50',
                'status' => 'required|string|max:50',
            ]);

            $accountType = AccountType::create([
                'name' => $request->input('name'),
                'account_number_prefix' => $request->input('account_number_prefix'),
                'starting_account_number' => $request->input('starting_account_number'),
                'next_account_number' => $request->input('next_account_number'),
                'currency' => $request->input('currency'),
                'interest_rate' => $request->input('interest_rate'),
                'interest_method' => $request->input('interest_method'),
                'interest_period' => $request->input('interest_period'),
                'minimum_balance_for_interest' => $request->input('minimum_balance_for_interest'),
                'allow_withdraw' => $request->input('allow_withdraw'),
                'minimum_deposit_amount' => $request->input('minimum_deposit_amount'),
                'minimum_account_balance' => $request->input('minimum_account_balance'),
                'maintenance_fee' => $request->input('maintenance_fee'),
                'maintenance_fee_posting_month' => $request->input('maintenance_fee_posting_month'),
                'status' => $request->input('status'),
            ]);

            // Redirection avec message de succès
            return response()->json(['message' => 'Type de Compte créé avec succès.', 'ok' => true]);
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

    /**
     * Display the specified resource.
     */
    public function show(AccountType $accountType)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccountType $accountType)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccountType $accountType)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountType $accountType)
    {
    }
}
