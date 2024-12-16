<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreAccounteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //return true;
        $user = Auth::user();
        // return auth()->check();
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'firstname' => 'required|string|min:3|max:255',
            'lastname' => 'required|string|min:3|max:255',
            'phone' => 'required|digits:8',  // Numéro de téléphone exact de 8 chiffres
            'address1' => 'required|string|min:3|max:255',
            'job' => 'required|string|min:3|max:255',
            'gender' => 'required|in:Male,Female',  // Genre limité à "M" ou "F"
            'creation_date' => 'required|date',
            'assistant' => 'required|string|min:3|max:255',
            'type' => 'required|array',
            'type.*' => 'in:SAVINGS,TONTINE',

        ];
    }
}
