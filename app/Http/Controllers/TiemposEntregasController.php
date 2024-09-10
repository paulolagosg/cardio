<?php

namespace App\Http\Controllers;

use App\Models\TiemposEntregas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class TiemposEntregasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public  function index()
    {
        $datos = TiemposEntregas::get();

        return view('tiempos_entregas.lista', compact('datos'));
    }

    public function agregar(Request $request)
    {
        return view('tiempos_entregas.agregar');
    }

    public function agregar_guardar(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);

        $estado = new TiemposEntregas();
        $estado->nombre = $request->nombre;
        $estado->slug = str_slug($request->nombre, '-');
        $estado->save();
        session()->flash('message', 'Registro agregado correctamente.');
        return redirect()->to('/tiempos_entregas/lista');
    }

    public function editar($id)
    {
        $datos = TiemposEntregas::where('slug', $id)->get();
        if (count($datos) <= 0) {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->route('tiempos_entregas.index')->with('error', 'El registro no existe.');
        } else {
            $datos = TiemposEntregas::where('slug', $id)->first();
        }

        return view('tiempos_entregas.editar', compact('datos'));
    }

    public function actualizar(Request $request)
    {
        $nombre = $request->nombre;
        $id = $request->id;

        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);

        $actualizar = DB::table('tiempos_entregas')
            ->where('id', $id)
            ->update(['nombre' => $nombre, 'slug' => str_slug($nombre, '-')]);

        session()->flash('message', 'Registro modificado correctamente.');
        return redirect()->to('/tiempos_entregas/lista');
    }

    public function eliminar($id)
    {
        $datos = TiemposEntregas::findOrFail($id);

        //$deleted = DB::table('tiempos_entregas')->where('id', $id)->delete();
        $actualizar = DB::table('tiempos_entregas')
            ->where('id', $id)
            ->update(['estado' => 0]);

        session()->flash('message', 'Registro eliminado correctamente.');
        return Redirect::back();
    }
}
