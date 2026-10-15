<?php

namespace App\Http\Dtos;

use App\Http\Requests\EnterpriseLoginRequest;

class EnterpriseLoginDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $password
    ) {}

    public static function fromRequest(EnterpriseLoginRequest $request): self
    {
        return new self(
            name: (string) $request->validated('name'),
            password: (string) $request->validated('password')
        );
    }
}