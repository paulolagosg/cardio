<?php

namespace App\Imports;

use App\Models\Alumnos;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportarAlumnos implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Alumnos([
            'id_version' => $row[0],
            'rut' => $row[1],
            'nombre' => $row[2],
            'correo_electronico' => $row[3],
            'nota' => $row[4],
            'asistencia' => $row[5]
        ]);
    }
}
