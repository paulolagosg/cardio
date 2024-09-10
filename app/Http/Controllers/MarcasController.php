<?php

namespace App\Http\Controllers;

use App\Models\Marcas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class MarcasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public  function index()
    {
        $datos = Marcas::get();

        return view('marcas.lista', compact('datos'));
    }

    public function agregar(Request $request)
    {
        return view('marcas.agregar');
    }

    public function agregar_guardar(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);

        $estado = new Marcas();
        $estado->nombre = $request->nombre;
        $estado->slug = str_slug($request->nombre, '-');
        $estado->save();
        session()->flash('message', 'Registro agregado correctamente.');
        return redirect()->to('/marcas/lista');
    }

    public function editar($id)
    {
        $datos = marcas::where('slug', $id)->get();
        if (count($datos) <= 0) {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->route('marcas.index')->with('error', 'El registro no existe.');
        } else {
            $datos = marcas::where('slug', $id)->first();
        }

        return view('marcas.editar', compact('datos'));
    }

    public function actualizar(Request $request)
    {
        $nombre = $request->nombre;
        $id = $request->id;

        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);

        $actualizar = DB::table('marcas')
            ->where('id', $id)
            ->update(['nombre' => $nombre, 'slug' => str_slug($nombre, '-')]);

        session()->flash('message', 'Registro modificado correctamente.');
        return redirect()->to('/marcas/lista');
    }

    public function eliminar($id)
    {
        $datos = marcas::findOrFail($id);

        //$deleted = DB::table('marcas')->where('id', $id)->delete();
        $actualizar = DB::table('marcas')
            ->where('id', $id)
            ->update(['estado' => 0]);

        session()->flash('message', 'Registro eliminado correctamente.');
        return Redirect::back();
    }
}
