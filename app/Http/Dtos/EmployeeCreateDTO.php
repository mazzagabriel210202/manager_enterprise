<?php

namespace App\Http\Dtos;

use App\Http\Requests\EmployeeCreateRequest;

class EmployeeCreateDTO
{
    public function __construct(
        public readonly int $departmentId,
        public readonly string $name,
        public readonly int $age,
        public readonly string $email,
        public readonly string $position
    ) {}

    public static function fromRequest(EmployeeCreateRequest $request): self
    {
        return new self(
            departmentId: (int) $request->validated('department_id'),
            name: $request->validated('name'),
            age: (int) $request->validated('age'),
            email: $request->validated('email'),
            position: $request->validated('position')
        );
    }
}