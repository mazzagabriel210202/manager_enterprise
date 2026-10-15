<?php

namespace App\Http\Dtos;

use App\Http\Requests\EnterpriseRegisterRequest;

class EnterpriseRegisterDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $cnpj,
        public readonly string $password
    ) {}

    public static function fromRequest(EnterpriseRegisterRequest $request): self
    {
        return new self(
            name: (string) $request->validated('name'),
            cnpj: (string) $request->validated('cnpj'),
            password: (string) $request->validated('password')
        );
    }
}