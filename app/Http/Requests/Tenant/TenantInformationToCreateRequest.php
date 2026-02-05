<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class TenantInformationToCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tenant_name' => ['required','string','max:255'],
            'address' => ['nullable','string','max:255'],
            'phone' => ['nullable','string','max:50'],
            'email' => ['nullable','email','max:255'],
            'RIB' => ['nullable','string','max:255'],
            'SIRET' => ['nullable','string','max:255'],
            'VAT_number' => ['nullable','string','max:255'],
            'logo_path' => ['nullable','file'],
        ];
    }
}
