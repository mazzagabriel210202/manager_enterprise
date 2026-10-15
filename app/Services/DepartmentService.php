<?php

namespace App\Services;

use App\Http\Dtos\DepartmentCreateDTO;
use App\Http\Dtos\DepartmentUpdateDTO;
use App\Http\Requests\DepartmentCreateRequest;
use App\Http\Requests\DepartmentUpdateRequest;
use App\Http\Dtos\EmployeeCreateDTO;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Collection;

class DepartmentService
{
    public function create(DepartmentCreateDTO $dto): Department
    {
     $department = new Department();

     $department->name = $dto->name;
     $department->enterprise_id =  $dto->enterpriseId;

     $department->save();

     return $department;



    }

    public function read(): Collection
    {
        return Department::all();
    }

    public function findById(int $id): Department
    {
  
    return Department::findOrFail($id);

    }

    public function update(DepartmentUpdateDTO $dto, int $id): Department
    {

        $department = Department::findOrFail($id);

        // Filtra apenas os campos que foram enviados (não nulos)
        $data = array_filter([
            'enterprise_id' => $dto->enterpriseId,
            'name' => $dto->name,
        ], fn ($value) => !is_null($value));

        $department->update($data);

        return $department->fresh();

    }


    public function delete(int $id): bool
    {
        // Encontra o departamento ou dispara 404 automaticamente se não existir
        $department = Department::findOrFail($id);

        return $department->delete();
    }


    public function addEmployee(int $departmentId, EmployeeCreateDTO $dto): Employee
{
    // 1. Garante que o departamento existe (retorna 404 se não achar)
    $department = Department::findOrFail($departmentId);

    // 2. Valida a regra de 1 para 1 (se já tem funcionário, impede a criação)
    if ($department->employee()->exists()) {
        // Você pode lançar uma ValidationException ou um erro customizado da API
        throw new \InvalidArgumentException('Este departamento já possui um funcionário vinculado.');
    }

    // 3. Cria o funcionário atrelado a este departamento
    return $department->employee()->create([
        'name' => $dto->name,
        'email' => $dto->email,
        'position' => $dto->position,
    ]);
}


   
}


