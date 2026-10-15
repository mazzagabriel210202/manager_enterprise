<?php

namespace App\Http\Controllers;

use App\Http\Dtos\EnterpriseCreateDTO;
use App\Http\Dtos\EnterpriseUpdateDTO;
use App\Services\EnterpriseService;
use App\Http\Requests\EnterpriseCreateRequest;
use App\Http\Requests\EnterpriseUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class EnterpriseController extends Controller
{
    public function __construct(
        protected EnterpriseService $service
    ) {}
 
    public function store(EnterpriseCreateRequest $request): JsonResponse
    {
        $dto = EnterpriseCreateDTO::fromRequest($request);
        $enterprise = $this->service->create($dto);

        return response()->json([
            'message' => 'Empresa criada com sucesso',
            'data' => $enterprise,
        ], 201); // 201 correto para criação
    }

    public function index(): JsonResponse
    {
        $enterprises = $this->service->readAll(); // Mudado para plural para clareza

        return response()->json([
            'message' => 'Empresas retornadas com sucesso',
            'data' => $enterprises,
        ]); // Retorna 200 por padrão
    }

    public function show(int $id): JsonResponse
    {
        $enterprise = $this->service->findById($id);

        return response()->json([
            'message' => 'Empresa retornada com sucesso',
            'data' => $enterprise,
        ]); // Retorna 200 por padrão
    } 

    public function update(int $id, EnterpriseUpdateRequest $request): JsonResponse
    {
        $enterprise = $this->service->findById($id);
        $dto = EnterpriseUpdateDTO::fromRequest($request);
        
        $updatedEnterprise = $this->service->update($enterprise, $dto);

        return response()->json([
            'message' => 'Empresa atualizada com sucesso',
            'data' => $updatedEnterprise,
        ]); // Retorna 200 por padrão
    }

    public function destroy(int $id): JsonResponse
    {
        // Garante que existe (joga 404 se não achar)
        $this->service->findById($id);

        // Executa a exclusão usando o ID
        $this->service->delete($id);

        return response()->json(null, 204); // 204 correto para No Content
    }
}
