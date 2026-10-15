<?php

namespace App\Http\Dtos;

use App\Http\Requests\EnterpriseUpdateRequest;

class EnterpriseUpdateDTO
{
    public function __construct(
        public readonly ?string $name,
        public readonly ?string $cnpj,
        public readonly ?string $password
    ) {}

    public static function fromRequest(EnterpriseUpdateRequest $request): self
    {
        return new self(
            name: $request->validated('name'),
            cnpj: $request->validated('cnpj'),
            password: $request->validated('password')
        );
    }
}