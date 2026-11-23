<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\EmpleadoResource;
use App\Models\Empleado;


class EmpleadoController extends Controller
{
    public function index(){
        $empleados = Empleado::orderBy('id')->paginate(10);
        
        return response()->json([
            'status' => 'success',
            'data'   => EmpleadoResource::collection($empleados),
            'pagination' => [
                'total'        => $empleados->total(),
                'per_page'     => $empleados->perPage(),
                'current_page' => $empleados->currentPage(),
                'last_page'    => $empleados->lastPage(),
            ]
        ], 200);
    }

    public function store(Request $request){

        $validados = $request->validate([
            'identidad' => 'required|string|unique|max:13',
            'nombre' => 'required|string|max:150',
            'cargo' => 'Required|string',
            'salario' => 'required|numeric|min:0',
            'codigo_empleado' => 'required|numeric|min:13|max:13'
        ]);

        $empleado = Empleado::create($validados);
        return response()->json([
            'status' => 'created',
            'message' => 'Empleado ingresado exitosamente',
            'data' => new EmpleadoResource($empleado)
        ], 201);
    }

    public function show(string $id){
        $empleado = Empleado::find($id);
        if(!$empleado){
            return response()->json([
                'status' => 'error',
                'message' => "El empleado {$id} no se ha encontrado."
            ], 404);

            return response()->json([
                'status' => 'success',
                'data'   => new EmpleadoResource($empleado)
            ],200);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $empleado = Empleado::find($id);

        if (!$empleado) {
            return response()->json([
                'status'  => 'error',
                'message' => 'empleado no encontrado para actualizar'
            ], 404);
        }

        $validados = $request->validate([
            'nombre'       => 'required|string|max:150',
            'cargo'        => 'required|string',
            'salario'      => 'required|numeric|min:0',
        ]);

        $empleado->update($validados);

        return response()->json([
            'status'  => 'updated',
            'message' => 'empleado actualizado correctamente',
            'data'    => new EmpleadoResource($empleado)
        ], 200);
    }

    public function destroy(string $id){
        
        $empleado = Empleado::find($id);

        if (!$empleado) {
            return response()->json([
                'status'  => 'error',
                'message' => 'El empleado a eliminar no existe en bodega'
            ], 404);
        }

        $empleado->delete();

        return response()->json([
            'status'  => 'deleted',
            'message' => 'empleado dado de baja exitosamente'
        ], 200);
    }
}

