<?php

namespace App\Http\Requests\Files;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMediaRequest extends FormRequest
{
    /**
     * Autoriser la requête.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation pour la mise à jour.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Le fichier est optionnel ici
            'title'=>'required|string|max:255|unique:media,title,'.$this->route('media')->id,
            'file' => 'nullable|file|max:2048|mimes:jpg,jpeg,png,pdf,docx',
        ];
    }
}
