<?php

namespace App\Http\Controllers;

use App\Models\AccountType;
use App\Models\Client;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $clients = Client::all();

            return view('pages.admin.client.index', compact('clients'));
        } catch (\Throwable $th) {
            // throw $th;
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
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // validation pour l'image
                'nom_client' => 'required|string|min:2',
                'date' => 'required|date',
                'email' => 'email',
                'profession' => 'required|string',
                'telephone' => 'required|string',
                'location' => 'required|string',
                'ville' => 'required|string',
                'quartier' => 'required|string',
                'sexe' => 'required|in:Masculin,Féminin',
            ]);

            // Création de l'article
            $client = Client::create([
                'name' => $request->input('nom_client'),
                'birthdate' => $request->input('date'),
                'email' => $request->input('email'),
                'country' => $request->input('location'),
                'city' => $request->input('ville'),
                'phone' => $request->input('telephone'),
                'address1' => $request->input('quartier'),
                'job' => $request->input('profession'),
                'gender' => $request->input('sexe'),
                'address2' => $request->input('quartier'),
                'status' => 'ACTIF',
            ]);
            // Upload de la nouvelle image si fournie
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $image_name = time().'.'.$photo->getClientOriginalExtension();
                $image_path = $photo->storeAs('images/client/photos', $image_name, 'public'); // Stocker l'image
                $client->profile_picture = Storage::url($image_path);
            }
            $client->save();

            // Redirection avec message de succès
            return response()->json(['message' => 'Client créé avec succès.', 'ok' => true]);
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
    public function show($clientId)
    {
        try {
            $client = Client::find($clientId);
            $accountTypes = AccountType::all();
            $assistants = User::where('profile', 'ASSISTANT')->get();
            $today = Carbon::today()->toDateString(); // Format 'YYYY-MM-DD'

            return view('pages.admin.client.show', compact('client', 'accountTypes', 'assistants', 'today'));
        } catch (\Throwable $th) {
            // throw $th;
            dd($th->getMessage());
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        try {
            // Valider les fichiers et l'image
            $request->validate([
                'nom_client' => 'required|string|min:2',
                'date' => 'required|date',
                'email' => 'email',
                'profession' => 'required|string',
                'telephone' => 'required|string',
                'location' => 'required|string',
                'ville' => 'required|string',
                'quartier' => 'required|string',
                'sexe' => 'required|in:Masculin,Féminin',
            ]);

            // Création de l'article
            $client->update([
                'name' => $request->input('nom_client'),
                'birthdate' => $request->input('date'),
                'email' => $request->input('email'),
                'country' => $request->input('location'),
                'city' => $request->input('ville'),
                'phone' => $request->input('telephone'),
                'address1' => $request->input('quartier'),
                'job' => $request->input('profession'),
                'gender' => $request->input('sexe'),
                'address2' => $request->input('quartier'),
                'status' => 'ACTIF',
            ]);

            // Redirection avec message de succès
            return response()->json(['message' => 'Données du client mise à jour avec succès.', 'ok' => true]);
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

    public function updateStatus(Request $request, Client $client)
    {
        try {
            // Valider les fichiers et l'image
            $request->validate([
                'status' => 'required|in:ACTIF,INACTIF',
            ]);

            // Création de l'article
            $client->update([
                'status' => $request->input('status'),
            ]);
            foreach ($client->accounts as $account) {
                $account->state = $request->input('status') == 'ACTIF' ? 'ACTIVE' : 'INACTIVE';
                $account->save();
            }

            // Redirection avec message de succès
            return response()->json(['message' => 'Client désactivé avec succès.', 'ok' => true]);
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
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
    }
}
