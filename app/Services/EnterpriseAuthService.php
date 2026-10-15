<?php

namespace App\Services;

use App\Http\Dtos\EnterpriseLoginDTO;
use App\Http\Dtos\EnterpriseRegisterDTO;
use App\Models\Enterprise;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class EnterpriseAuthService 
{
    public function register(EnterpriseRegisterDTO $dto): Enterprise
    {
        $enterprise = new Enterprise();
        $enterprise->name = $dto->name;
        $enterprise->cnpj = $dto->cnpj;
        $enterprise->password = Hash::make($dto->password);
        
        $enterprise->save();

        return $enterprise;
    }

    public function login(EnterpriseLoginDTO $dto): Enterprise
    {
        $enterprise = Enterprise::where('name', $dto->name)->first();

        if (!$enterprise || !Hash::check($dto->password, $enterprise->password)) {
            throw ValidationException::withMessages([
                'credentials' => 'Nome ou senha estão inválidos',
            ]);
        }
          
        return $enterprise;
    }

   public function logout(Enterprise $enterprise): void
{
    // Deleta o token atual ativo da empresa de forma segura
    $token = $enterprise->currentAccessToken();

    if ($token) {
        // Força a exclusão buscando pelo ID do token atual na tabela
        $enterprise->tokens()->where('id', $token->id)->delete();
    }
}

}