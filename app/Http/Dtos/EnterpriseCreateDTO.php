<?php

namespace App\Http\Dtos;

use App\Http\Requests\EnterpriseCreateRequest;

class EnterpriseCreateDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $cnpj,
        public readonly string $password
    ) {}

    public static function fromRequest(EnterpriseCreateRequest $request): self
    {
        return new self(
            name: (string) $request->validated('name'),
            cnpj: (string) $request->validated('cnpj'),
            password: (string) $request->validated('password')
        );
    }
}