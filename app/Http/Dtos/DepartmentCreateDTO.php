<?php

namespace App\Http\Dtos;

use App\Http\Requests\DepartmentCreateRequest;

class DepartmentCreateDTO
{
    public function __construct(
        public readonly int $enterpriseId,
        public readonly string $name
    ) {}

    public static function fromRequest(DepartmentCreateRequest $request): self
    {
        return new self(
            enterpriseId: (int) $request->validated('enterprise_id'),
            name: $request->validated('name')
        );
    }


}