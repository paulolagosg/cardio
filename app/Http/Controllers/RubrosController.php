<?php

namespace App\Http\Controllers;

use App\Models\Rubros;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class RubrosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public  function index()
    {
        $datos = Rubros::get();

        return view('rubros.lista', compact('datos'));
    }

    public function agregar(Request $request)
    {
        return view('rubros.agregar');
    }

    public function agregar_guardar(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);

        $estado = new Rubros();
        $estado->nombre = $request->nombre;
        $estado->slug = str_slug($request->nombre, '-');
        $estado->save();
        session()->flash('message', 'Registro agregado correctamente.');
        return redirect()->to('/rubros/lista');
    }

    public function editar($id)
    {
        $datos = Rubros::where('slug', $id)->get();
        if (count($datos) <= 0) {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->route('rubros.index')->with('error', 'El registro no existe.');
        } else {
            $datos = Rubros::where('slug', $id)->first();
        }

        return view('rubros.editar', compact('datos'));
    }

    public function actualizar(Request $request)
    {
        $nombre = $request->nombre;
        $id = $request->id;

        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);

        $actualizar = DB::table('rubros')
            ->where('id', $id)
            ->update(['nombre' => $nombre, 'slug' => str_slug($nombre, '-')]);

        session()->flash('message', 'Registro modificado correctamente.');
        return redirect()->to('/rubros/lista');
    }

    public function eliminar($id)
    {
        $datos = Rubros::findOrFail($id);

        $deleted = DB::table('rubros')->where('id', $id)->delete();

        session()->flash('message', 'Registro eliminado correctamente.');
        return Redirect::back();
    }
}
