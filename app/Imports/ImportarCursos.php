<?php

namespace App\Imports;

use App\Models\Cursos;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportarCursos implements ToModel, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $aleatoreo = rand(100, 999);
        return new Cursos([
            'nombre' => $row[0]
        ]);
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required'
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nombre.required' => 'El campo nombre del curso es requerido.',
        ];
    }
}
