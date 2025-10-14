<?php

namespace App\Http\Requests\Files;

use Illuminate\Foundation\Http\FormRequest;

class StoreMediaRequest extends FormRequest
{
    /**
     * Autoriser la requête.
     */
    public function authorize(): bool
    {
        // Tu peux mettre une logique ici (ex: auth()->check())
        return true;
    }

    /**
     * Règles de validation pour l’upload.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'=>'required|string|max:255|unique:media,title',
            'file' => 'required|file|max:131072', 
        ];
    }
}
