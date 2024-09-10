<?php

namespace App\Http\Controllers;

use App\Models\TiposMantenciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class TiposMantencionesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public  function index()
    {
        $datos = TiposMantenciones::get();

        return view('tipos_mantenciones.lista', compact('datos'));
    }

    public function agregar(Request $request)
    {
        return view('tipos_mantenciones.agregar');
    }

    public function agregar_guardar(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);

        $estado = new TiposMantenciones();
        $estado->nombre = $request->nombre;
        $estado->slug = str_slug($request->nombre, '-');
        $estado->save();
        session()->flash('message', 'Registro agregado correctamente.');
        return redirect()->to('/tipos_mantenciones/lista');
    }

    public function editar($id)
    {
        $datos = TiposMantenciones::where('slug', $id)->get();
        if (count($datos) <= 0) {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->route('tipos_mantenciones.index')->with('error', 'El registro no existe.');
        } else {
            $datos = TiposMantenciones::where('slug', $id)->first();
        }

        return view('tipos_mantenciones.editar', compact('datos'));
    }

    public function actualizar(Request $request)
    {
        $nombre = $request->nombre;
        $id = $request->id;

        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);

        $actualizar = DB::table('tipos_mantenciones')
            ->where('id', $id)
            ->update(['nombre' => $nombre, 'slug' => str_slug($nombre, '-')]);

        session()->flash('message', 'Registro modificado correctamente.');
        return redirect()->to('/tipos_mantenciones/lista');
    }

    public function eliminar($id)
    {
        $datos = TiposMantenciones::findOrFail($id);

        //$deleted = DB::table('tipos_mantenciones')->where('id', $id)->delete();

        $actualizar = DB::table('tipos_mantenciones')
            ->where('id', $id)
            ->update(['estado' => 0]);

        session()->flash('message', 'Registro eliminado correctamente.');
        return Redirect::back();
    }
}
