<?php

namespace App\Http\Requests\Mail\MailCanClientSend;

use Illuminate\Foundation\Http\FormRequest;

class MailCanClientSendToUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'title' => 'required|string|max:255',
            'mail_limit' => 'required|integer|min:100|max:3000',
            'is_active' => 'boolean',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after:valid_from',
        ];
    }
}
