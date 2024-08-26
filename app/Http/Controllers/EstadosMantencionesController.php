<?php

namespace App\Http\Controllers;

use App\Models\EstadosMantenciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class EstadosMantencionesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public  function index()
    {
        $datos = EstadosMantenciones::get();

        return view('estados_mantenciones.lista', compact('datos'));
    }

    public function agregar(Request $request)
    {
        return view('estados_mantenciones.agregar');
    }

    public function agregar_guardar(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);

        $estado = new EstadosMantenciones();
        $estado->nombre = $request->nombre;
        $estado->slug = str_slug($request->nombre, '-');
        $estado->save();
        session()->flash('message', 'Registro agregado correctamente.');
        return redirect()->to('/estados_mantenciones/lista');
    }

    public function editar($id)
    {
        $datos = EstadosMantenciones::where('slug', $id)->get();
        if (count($datos) <= 0) {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->route('estados_mantenciones.index')->with('error', 'El registro no existe.');
        } else {
            $datos = EstadosMantenciones::where('slug', $id)->first();
        }

        return view('estados_mantenciones.editar', compact('datos'));
    }

    public function actualizar(Request $request)
    {
        $nombre = $request->nombre;
        $id = $request->id;

        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);

        $actualizar = DB::table('estados_mantenciones')
            ->where('id', $id)
            ->update(['nombre' => $nombre, 'slug' => str_slug($nombre, '-')]);

        session()->flash('message', 'Registro modificado correctamente.');
        return redirect()->to('/estados_mantenciones/lista');
    }

    public function eliminar($id)
    {
        $datos = EstadosMantenciones::findOrFail($id);

        $deleted = DB::table('estados_mantenciones')->where('id', $id)->delete();

        session()->flash('message', 'Registro eliminado correctamente.');
        return Redirect::back();
    }
}
