<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmpleadoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'identidad'     => $this->identidad,
            'nombre'        => $this->nombre, 
            'cargo'         => $this->cargo,
            'salario'       => 'L.' . number_format($this->salario,2),
            'fecha_ingreso' => $this->fecha_ingreso,
        ];
    }

}
