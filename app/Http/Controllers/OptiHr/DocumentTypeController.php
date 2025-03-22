<?php

namespace App\Http\Controllers\OptiHr;

use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DocumentTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:configurer-un-document|voir-un-tout'], ['only' => ['index']]);
        $this->middleware(['permission:configurer-un-document|créer-un-tout'], ['only' => ['store', 'update', 'create']]);

        $this->middleware(['permission:configurer-un-document|écrire-un-tout'], ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $documentTypes = DocumentType::all();

        return view('pages.admin.documents.types.index', compact('documentTypes'));

    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Valider les fichiers et l'image
        $request->validate([
            'libelle' => 'required|string',
            'description' => 'sometimes',
            'type' => 'sometimes',
        ]);

        DocumentType::create([
            'label' => $request->input('libelle'),
            'description' => $request->input('description'),
            'type' => $request->input('type') ?? 'NORMAL',
        ]);

        // Redirection avec message de succès
        return response()->json(['message' => 'Type Document créé avec succès.', 'ok' => true]);

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $documentTypeId)
    {

        // Valider les fichiers et l'image
        $request->validate([
            'libelle' => 'required|string',
            'description' => 'sometimes',
            'type' => 'sometimes',
        ]);
        $documentType = DocumentType::find($documentTypeId);
        $documentType->label = $request->input('libelle');
        $documentType->description = $request->input('description');
        $documentType->type = $request->input('type') ?? $documentType->type;

        $documentType->save();

        // Redirection avec message de succès
        return response()->json(['message' => 'Type Document mis à jour avec succès.', 'ok' => true]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        \DB::table('document_types')->where('id', $id)->delete();

        return response()->json(['ok' => true, 'message' => 'Le type d\document a été retiré avec succès.']);

    }
}
