<?php

namespace App\Http\Controllers;

use App\Http\Dtos\EnterpriseLoginDTO;
use App\Http\Dtos\EnterpriseRegisterDTO;
use App\Http\Requests\EnterpriseLoginRequest;
use App\Http\Requests\EnterpriseRegisterRequest;
use App\Services\EnterpriseAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EnterpriseAuthController extends Controller
{
    // Injeção de dependência do Service
    public function __construct(
        protected EnterpriseAuthService $authService
    ) {}

    /**
     * Registra uma nova empresa.
     */
    public function register(EnterpriseRegisterRequest $request): JsonResponse
    {
        $dto = EnterpriseRegisterDTO::fromRequest($request);

        $enterprise = $this->authService->register($dto);

        return response()->json([
            'message' => 'Empresa cadastrada com sucesso!',
            'data' => $enterprise
        ], 201);
    }

    /**
     * Autentica uma empresa e retorna o token de acesso (Sanctum).
     */
    public function login(EnterpriseLoginRequest $request): JsonResponse
    {
        $dto = EnterpriseLoginDTO::fromRequest($request);

        $enterprise = $this->authService->login($dto);

        // Gera o token de acesso via Laravel Sanctum
        $token = $enterprise->createToken('enterprise_auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login realizado com sucesso!',
            'data' => $enterprise,
            'token' => $token
        ], 200);
    }

    /**
     * Encerra a sessão/token da empresa autenticada.
     */
    public function logout(Request $request): JsonResponse
    {
        $enterprise = $request->user();

        $this->authService->logout($enterprise);

        return response()->json([
            'message' => 'Logout realizado com sucesso!'
        ], 200);
    }
}