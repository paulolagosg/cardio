<?php

namespace App\Http\Controllers;

use App\Models\TiposTransportes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class TiposTransportesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public  function index()
    {
        $datos = TiposTransportes::get();

        return view('tipos_transportes.lista', compact('datos'));
    }

    public function agregar(Request $request)
    {
        return view('tipos_transportes.agregar');
    }

    public function agregar_guardar(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);

        $estado = new TiposTransportes();
        $estado->nombre = $request->nombre;
        $estado->slug = str_slug($request->nombre, '-');
        $estado->save();
        session()->flash('message', 'Registro agregado correctamente.');
        return redirect()->to('/tipos_transportes/lista');
    }

    public function editar($id)
    {
        $datos = TiposTransportes::where('slug', $id)->get();
        if (count($datos) <= 0) {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->route('tipos_transportes.index')->with('error', 'El registro no existe.');
        } else {
            $datos = TiposTransportes::where('slug', $id)->first();
        }

        return view('tipos_transportes.editar', compact('datos'));
    }

    public function actualizar(Request $request)
    {
        $nombre = $request->nombre;
        $id = $request->id;

        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);

        $actualizar = DB::table('tipos_transportes')
            ->where('id', $id)
            ->update(['nombre' => $nombre, 'slug' => str_slug($nombre, '-')]);

        session()->flash('message', 'Registro modificado correctamente.');
        return redirect()->to('/tipos_transportes/lista');
    }

    public function eliminar($id)
    {
        $datos = TiposTransportes::findOrFail($id);

        //$deleted = DB::table('tipos_transportes')->where('id', $id)->delete();
        $actualizar = DB::table('tipos_transportes')
            ->where('id', $id)
            ->update(['estado' => 0]);

        session()->flash('message', 'Registro eliminado correctamente.');
        return Redirect::back();
    }
}
