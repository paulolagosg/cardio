<?php

namespace App\Http\Controllers;

use App\Models\Cursos;
use App\Models\Modalidades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CursosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public  function index()
    {
        $datos = Cursos::get();

        return view('cursos.lista', compact('datos'));
    }

    public function agregar(Request $request)
    {
        // $modalidades = Modalidades::where('estado', 1)->get();
        // return view('cursos.agregar', compact('modalidades'));
        return view('cursos.agregar');
    }

    public function agregar_guardar(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);

        $curso = new cursos();
        $curso->nombre = $request->nombre;
        //$curso->id_modalidad = $request->id_modalidad;
        $curso->slug = str_slug($request->nombre, '-');
        $curso->save();
        session()->flash('message', 'Registro agregado correctamente.');
        return redirect()->to('/cursos/lista');
    }

    public function editar($id)
    {
        $datos = cursos::where('slug', $id)->get();
        if (count($datos) <= 0) {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->route('cursos.index')->with('error', 'El registro no existe.');
        } else {
            $datos = cursos::where('slug', $id)->first();
        }
        //$modalidades = Modalidades::where('estado', 1)->get();
        //return view('cursos.editar', compact('datos', 'modalidades'));

        return view('cursos.editar', compact('datos'));
    }

    public function actualizar(Request $request)
    {
        $nombre = $request->nombre;
        $id = $request->id;

        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);

        // $actualizar = DB::table('cursos')
        //     ->where('id', $id)
        //     ->update(['nombre' => $nombre, 'id_modalidad' => $request->id_modalidad, 'slug' => str_slug($nombre, '-')]);

        $actualizar = DB::table('cursos')
            ->where('id', $id)
            ->update(['nombre' => $nombre, 'slug' => str_slug($nombre, '-')]);

        session()->flash('message', 'Registro modificado correctamente.');
        return redirect()->to('/cursos/lista');
    }

    public function eliminar($id)
    {
        $datos = cursos::findOrFail($id);

        $deleted = DB::table('cursos')->where('id', $id)->delete();
        // $actualizar = DB::table('cursos')
        //     ->where('id', $id)
        //     ->update(['estado' => 0]);

        session()->flash('message', 'Registro eliminado correctamente.');
        return Redirect::back();
    }
}
