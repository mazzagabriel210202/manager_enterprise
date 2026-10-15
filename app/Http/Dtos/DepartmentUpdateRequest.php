<?php

namespace App\Http\Dtos;

use App\Http\Requests\DepartmentUpdateRequest;

class DepartmentUpdateDTO
{
    public function __construct(
        public readonly ?int $enterpriseId = null,
        public readonly ?string $name = null
    ) {}

    public static function fromRequest(DepartmentUpdateRequest $request): self
    {
        return new self(
            enterpriseId: $request->has('enterprise_id') ? (int) $request->validated('enterprise_id') : null,
            name: $request->validated('name')
        );
    }

   
}