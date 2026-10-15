<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'enterprise_id' => ['sometimes', 'required', 'exists:enterprises,id'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'enterprise_id.required' => 'O campo ID da empresa é obrigatório.',
            'enterprise_id.exists' => 'A empresa informada não existe.',
            'name.required' => 'O nome do departamento é obrigatório.',
            'name.string' => 'O nome do departamento deve ser um texto válido.',
            'name.max' => 'O nome do departamento não pode ter mais de 255 caracteres.',
        ];
    }
}