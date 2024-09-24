<?php

namespace App\Http\Controllers;

use App\Imports\ImportarAlumnos;
use Illuminate\Http\Request;
use App\Imports\ImportarCursos;
use App\Imports\ImportarVersiones;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;

class ExcelImportController extends Controller
{
    public function index()
    {
        $users = User::get();
        return view('cursos.importar', compact('users'));
    }

    public function importExcelCSV(Request $request)
    {

        $rules = [
            'file' => 'required|mimes:xlsx,xls',
        ];

        $customMessages = [
            'file.required' => 'El archivo es requerido.',
            'file.mimes' => 'El archivo debe ser de tipo xlsx o xls.',
        ];

        $this->validate($request, $rules, $customMessages);
        try {
            Excel::import(new ImportarCursos, $request->file('file'));
        } catch (\Exception $e) {
            return redirect('cursos/lista')->with('error', 'Formato de Excel incorrecto.');
        }

        return redirect('cursos/lista')->with('message', 'Datos importados correctamente.');
    }

    public function importar_version(Request $request)
    {

        $rules = [
            'file' => 'required|mimes:xlsx,xls',
        ];

        $customMessages = [
            'file.required' => 'El archivo es requerido.',
            'file.mimes' => 'El archivo debe ser de tipo xlsx o xls.',
        ];

        $this->validate($request, $rules, $customMessages);
        try {
            Excel::import(new ImportarVersiones, $request->file('file'));
        } catch (\Exception $e) {

            return redirect('versiones/lista')->with('error', 'Formato de Excel incorrecto.');
        }

        return redirect('versiones/lista')->with('message', 'Datos importados correctamente.');
    }

    public function importar_alumno(Request $request)
    {

        $rules = [
            'file' => 'required|mimes:xlsx,xls',
        ];

        $customMessages = [
            'file.required' => 'El archivo es requerido.',
            'file.mimes' => 'El archivo debe ser de tipo xlsx o xls.',
        ];

        $this->validate($request, $rules, $customMessages);
        try {
            Excel::import(new ImportarAlumnos, $request->file('file'));
        } catch (\Exception $e) {

            return redirect('alumnos/lista')->with('error', 'Formato de Excel incorrecto.');
        }

        return redirect('alumnos/lista')->with('message', 'Datos importados correctamente.');
    }

    public function downloadImportTemplate()
    {
        $path = base_path('/template/users.xlsx');;

        return response()->download($path, 'users.xlsx', [
            'Content-Type' => 'text/xlsx',
        ]);
    }
}
