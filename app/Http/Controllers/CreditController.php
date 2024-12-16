<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Credit;
use App\Models\User;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereHas('createdCredits')
            ->with(['createdCredits', 'accounts' => function ($query) {
                $query->whereHas('credits');  // Filtrer les comptes qui ont des crédits
            }])
            ->orderBy('created_at', 'desc')

            ->paginate(4);
        $credits = Credit::with('account', 'employee')->paginate(4);

        $clients = User::role('client')->get();

        return view('pages.admin.credit.index', compact('users', 'credits', 'clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function orders()
    {
        $query = Credit::query();

        $pendingCredits = $query->with('account', 'employee')->where('state', 'PENDING_APPROVAL')->get();
        $overdueCredits = $query->with('account', 'employee')->where('state', 'OVERDUE')->get();

        return view('pages.admin.credit.enregistrement', compact('pendingCredits', 'overdueCredits'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'interest_rate' => 'required|numeric|min:0',
            'balance' => 'required|numeric|min:0',
            // FIXEME: Add validation for date
            'agreement_date' => 'required|date|after_or_equal:today',
            'due_date' => 'nullable|date|after_or_equal:today',
            'user_id' => 'required|exists:users,id',
        ]);

        $input = $request->all();

        if ($validated['due_date'] == null) {
            // code...
            $input['state'] = 'PENDING_APPROVAL';
        }

        $employee = auth()->user();

        $user_id = $validated['user_id'];
        $account = Account::query()->where('client_id', $user_id)->where('state', 'ACTIVE')->where('type', 'TONTINE')->first();

        $input['account_id'] = $account->id;
        $input['employee_id'] = $validated['user_id'];

        Credit::create($input);

        // TODOS: Add success message
        return response()->json(['ok' => true], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Trouver le crédit par son ID
        $credits = Credit::with('account', 'employee')->where('employee_id', $id)->get();
        $client = User::findOrFail($id);

        // Retourner la vue avec les détails du crédit
        return view('pages.admin.credit.show', compact('credits', 'client'));
    }

    // public function show($id)
    // {
    //     // Trouver le crédit par son ID
    //     $credit = Credit::findOrFail($id);

    //     // Retourner la vue avec les détails du crédit
    //     return view('pages.admin.credit.show', compact('credit'));
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Credit $credit)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $credit)
    {
        $validated = $request->validate([
            'interest_rate' => 'required|numeric|min:0',
            'balance' => 'required|numeric|min:0',
            // FIXEME: Add validation for date
            'agreement_date' => 'required|date|after_or_equal:today',
            'due_date' => 'required|date|after_or_equal:today',
        ]);

        Credit::query()->where('id', $credit)->update($validated);

        // $credit->update($validated);

        return response()->json(['ok' => true, 'success' => $credit], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Credit $credit)
    {
        $credit->delete();

        return view('pages.admin.credit.index');
    }
}
