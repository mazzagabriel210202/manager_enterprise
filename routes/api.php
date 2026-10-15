<?php

use App\Http\Controllers\EnterpriseAuthController;
use App\Http\Controllers\EnterpriseController;
use App\Http\Controllers\DepartmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rotas públicas de autenticação
Route::post('/enterprise/register', [EnterpriseAuthController::class, 'register']);
Route::post('/enterprise/login', [EnterpriseAuthController::class, 'login']);

// Rotas protegidas (exigem autenticação via Laravel Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/enterprise/logout', [EnterpriseAuthController::class, 'logout']);
    
    // Dados da empresa logada atualmente
    Route::get('/enterprise/me', function (Request $request) {
        return response()->json([
            'data' => $request->user()
        ]);
    });

    // CRUD Completo para Enterprises
    Route::apiResource('enterprises', EnterpriseController::class);

    // CRUD Completo para Departments
    Route::apiResource('departments', DepartmentController::class);

    // Rota específica para adicionar/vincular um Employee a um Department (relação 1 para 1)
    Route::post('/departments/{id}/employee', [DepartmentController::class, 'addEmployee']);
});