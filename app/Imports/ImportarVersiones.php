<?php

namespace App\Imports;

use App\Models\Versiones;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportarVersiones implements ToModel, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        //dd($row);
        $aleatoreo = rand(100, 999);
        return new Versiones([
            'nombre' => $row[0],
            'id_cliente' => $row[1],
            'id_curso' => $row[2],
            'id_modalidad' => $row[3],
            'fecha_version' => $row[4],
            'id_usuario_instructor' => $row[5],
            'id_usuario_firmante' => $row[6],
            'horas' => $row[7],
            'contraparte' => $row[8],
            'rut' => $row[9],
            'correo_electronico' => $row[10],
            'telefono' => $row[11],
            'fecha_certificado' => Carbon::now(),
            'slug' => $row[0] . "-" . $aleatoreo,
            'estado' => 1,
            'firma' => ' ',
            'ruta' => ' ',
        ]);
    }
    public function rules(): array
    {
        return [
            'id_cliente' => ['integer'],
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'id_cliente.required' => 'El ID del cliente es obligatorio.',
            'id_cliente.integer' => 'El ID del cliente debe ser numérico.',
        ];
    }
}
