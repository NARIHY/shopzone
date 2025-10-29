<?php

namespace App\Http\Requests\Access\Permission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermissionStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /* 
    'name', 
        'description',
        'is_active'
    */
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', Rule::unique('permissions', 'name')->ignore($this->permission), 'max:255'],
            'description' => ['required'],
            'is_active'=>['boolean']
        ];
    }
}
