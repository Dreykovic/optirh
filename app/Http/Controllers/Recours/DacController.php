<?php

namespace App\Http\Controllers\Recours;

use App\Http\Controllers\Controller;
use App\Models\Recours\Applicant;
use App\Models\Recours\Dac;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DacController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('recours::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('recours::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function dacStore(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'reference' => 'required|unique:dacs,reference|string|max:255',
                'object' => 'required|string|max:500',
                'ac' => 'required|string|max:255',
            ]);

            // Create the dac
            Dac::create([
                'reference' => $validatedData['reference'],
                'object' => $validatedData['object'],
                'ac' => $validatedData['ac'],
                // 'created_by' =>  Auth::user()->employee->id ?? null
            ]);

            return response()->json(['message' => 'Dac créé avec succès.', 'ok' => true]);
        } catch (ValidationException $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(), // Contient tous les messages d'erreur de validation
            ], 422);
        } catch (\Throwable $th) {
            return response()->json(['ok' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function applicantStore(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nif' => 'required|unique:applicants,nif|string|max:255',
                'name' => 'required|string|max:255',
                'phone_number' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:255',
            ]);

            // Create the applicant
            Applicant::create([
                'nif' => $validatedData['nif'],
                'name' => $validatedData['name'],
                'phone_number' => $validatedData['phone_number'] ?? null,
                'address' => $validatedData['address'] ?? null,
                // 'created_by' =>  Auth::user()->employee->id ?? null
            ]);

            return response()->json(['message' => 'Requerant créé avec succès.', 'ok' => true]);
        } catch (ValidationException $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(), // Contient tous les messages d'erreur de validation
            ], 422);
        } catch (\Throwable $th) {
            return response()->json(['ok' => false, 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('recours::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('recours::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
    }
}
