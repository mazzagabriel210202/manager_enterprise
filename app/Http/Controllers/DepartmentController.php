<?php

namespace App\Http\Controllers;

use App\Http\Dtos\DepartmentCreateDTO;
use App\Http\Dtos\DepartmentUpdateDTO;
use App\Http\Dtos\EmployeeCreateDTO;
use App\Http\Requests\DepartmentCreateRequest;
use App\Http\Requests\DepartmentUpdateRequest;
use App\Http\Requests\EmployeeCreateRequest;
use App\Services\DepartmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class DepartmentController extends Controller 
{
    public function __construct(
        protected DepartmentService $departmentService
    ) {}

    /**
     * Lista todos os departamentos.
     */
    public function index(): JsonResponse
    {
        $departments = $this->departmentService->read();

        return response()->json([
            'success' => true,
            'data' => $departments
        ], 200);
    }

    /**
     * Cria um novo departamento.
     */
    public function store(DepartmentCreateRequest $request): JsonResponse
    {
        $dto = DepartmentCreateDTO::fromRequest($request);
        $department = $this->departmentService->create($dto);

        return response()->json([
            'success' => true,
            'message' => 'Departamento criado com sucesso.',
            'data' => $department
        ], 201);
    }

    /**
     * Exibe um departamento específico.
     */
    public function show(int $id): JsonResponse
    {
        $department = $this->departmentService->findById($id);

        return response()->json([
            'success' => true,
            'data' => $department
        ], 200);
    }

    /**
     * Atualiza um departamento existente.
     */
    public function update(DepartmentUpdateRequest $request, int $id): JsonResponse
    {
        $dto = DepartmentUpdateDTO::fromRequest($request);
        $department = $this->departmentService->update($dto, $id);

        return response()->json([
            'success' => true,
            'message' => 'Departamento atualizado com sucesso.',
            'data' => $department
        ], 200);
    }

    /**
     * Remove um departamento.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->departmentService->delete($id);

        return response()->json(null, 204);
    }

    /**
     * Adiciona um funcionário ao departamento (relação 1 para 1).
     */
    public function addEmployee(EmployeeCreateRequest $request, int $id): JsonResponse
    {
        $dto = EmployeeCreateDTO::fromRequest($request);
        $employee = $this->departmentService->addEmployee($id, $dto);

        return response()->json([
            'success' => true,
            'message' => 'Funcionário vinculado ao departamento com sucesso.',
            'data' => $employee
        ], 201);
    }
}