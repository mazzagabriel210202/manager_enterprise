<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EnterpriseUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Pega o ID da empresa logada ou passada por parâmetro na rota
        $enterpriseId = $this->route('enterprise')?->id ?? $this->user()?->id;

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'cnpj' => ['sometimes', 'required', 'string', 'digits:14', 'unique:enterprises,cnpj,' . $enterpriseId],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome da empresa é obrigatório.',
            'name.string' => 'O nome da empresa deve ser um texto válido.',
            'name.max' => 'O nome da empresa não pode ultrapassar 255 caracteres.',

            'cnpj.required' => 'O CNPJ é obrigatório.',
            'cnpj.string' => 'O CNPJ deve ser um texto válido.',
            'cnpj.digits' => 'O CNPJ deve conter exatamente 14 números.',
            'cnpj.unique' => 'Este CNPJ já está cadastrado por outra empresa.',

            'password.string' => 'A senha deve ser um texto válido.',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
        ];
    }
}