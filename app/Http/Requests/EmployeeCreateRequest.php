<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'name' => ['required', 'string', 'max:255'],
            'age' => ['required', 'integer', 'min:16', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:employees,email'],
            'position' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'department_id.required' => 'O campo ID do departamento é obrigatório.',
            'department_id.exists' => 'O departamento informado não existe.',
            'name.required' => 'O nome do funcionário é obrigatório.',
            'age.required' => 'A idade do funcionário é obrigatória.',
            'age.integer' => 'A idade deve ser um número inteiro.',
            'age.min' => 'O funcionário deve ter pelo menos 16 anos.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Informe um endereço de e-mail válido.',
            'email.unique' => 'Este e-mail já está cadastrado para outro funcionário.',
            'position.required' => 'O cargo é obrigatório.',
        ];
    }
}