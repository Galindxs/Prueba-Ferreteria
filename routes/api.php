<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductoController;
use App\Http\Controllers\ProductoController as ControllersProductoController;
use App\Http\Controllers\API\EmpleadoController as ControllersEmpleadoController;

use App\Http\Controllers\API\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Route::apiResource('producto', ControllersProductoController::class);

Route::apiResource('empleado', ControllersEmpleadoController::class)->names('api.empleados');

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('producto', ControllersProductoController::class)->names('api.productos');
});