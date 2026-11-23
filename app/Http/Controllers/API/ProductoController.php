<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ProductoResource;
use App\Models\Producto;
use Symfony\Contracts\Service\Attribute\Required;

class ProductoController extends Controller
{
    public function index(){
        $productos = Producto::orderBy('id','desc')->paginate(10);
        
        return response()->json([
            'status' => 'success',
            'data'   => ProductoResource::collection($productos),
            'pagination' => [
                'total'        => $productos->total(),
                'per_page'     => $productos->perPage(),
                'current_page' => $productos->currentPage(),
                'last_page'    => $productos->lastPage(),
            ]
        ], 200);
    }

    public function store(Request $request){

        $validados = $request->validate([
            'codigo_barra' => 'required|string|unique:productos,codigo_barra|max:30',
            'nombre' => 'required|string|max:150',
            'precio_venta' => 'Required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id'
        ]);

        $producto = Producto::create($validados);
        return response()->json([
            'status' => 'created',
            'message' => 'Articulo ingresado exitosamente',
            'data' => new ProductoResource($producto)
        ], 201);
    }

    public function show(string $id){
        $producto = Producto::find($id);
        if(!$producto){
            return response()->json([
                'status' => 'error',
                'message' => "El producto no se ha encontrado. ID Producto: {$id}"
            ], 404);

            return response()->json([
                'status' => 'success',
                'data'   => new ProductoResource($producto)
            ],200);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Producto no encontrado para actualizar'
            ], 404);
        }

        $validados = $request->validate([
            'nombre'       => 'required|string|max:150',
            'precio_venta' => 'required|numeric|min:0.01',
            'stock'        => 'required|integer|min:0',
        ]);

        $producto->update($validados);

        return response()->json([
            'status'  => 'updated',
            'message' => 'Producto actualizado correctamente',
            'data'    => new ProductoResource($producto)
        ], 200);
    }

    public function destroy(string $id){
        
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'status'  => 'error',
                'message' => 'El producto a eliminar no existe en bodega'
            ], 404);
        }

        $producto->delete();

        return response()->json([
            'status'  => 'deleted',
            'message' => 'Producto dado de baja exitosamente'
        ], 200);
    }
}
