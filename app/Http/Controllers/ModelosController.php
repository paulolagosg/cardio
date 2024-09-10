<?php

namespace App\Http\Controllers;

use App\Models\Marcas;
use App\Models\Modelos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ModelosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public  function index()
    {
        $datos = Modelos::join('marcas', 'marcas.id', 'modelos.id_marca')
            ->select('modelos.id', 'modelos.nombre', 'modelos.slug', DB::raw("marcas.nombre as marca"))
            ->get();

        return view('modelos.lista', compact('datos'));
    }

    public function agregar(Request $request)
    {
        $marcas = Marcas::where('estado', 1)->get();
        return view('modelos.agregar', compact('marcas'));
    }

    public function agregar_guardar(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:200',
            'id_marca' => 'required'
        ]);

        $modelos = new modelos();
        $modelos->nombre = $request->nombre;
        $modelos->id_marca = $request->id_marca;
        $modelos->slug = str_slug($request->nombre, '-');
        $modelos->save();
        session()->flash('message', 'Registro agregado correctamente.');
        return redirect()->to('/modelos/lista');
    }

    public function editar($id)
    {
        $datos = modelos::where('slug', $id)->get();
        if (count($datos) <= 0) {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->route('modelos.index')->with('error', 'El registro no existe.');
        } else {
            $datos = modelos::where('slug', $id)->first();
        }
        $marcas = Marcas::where('estado', 1)->get();
        return view('modelos.editar', compact('datos', 'marcas'));
    }

    public function actualizar(Request $request)
    {
        $nombre = $request->nombre;
        $id = $request->id;

        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);

        $actualizar = DB::table('modelos')
            ->where('id', $id)
            ->update(['nombre' => $nombre, 'slug' => str_slug($nombre, '-')]);

        session()->flash('message', 'Registro modificado correctamente.');
        return redirect()->to('/modelos/lista');
    }

    public function eliminar($id)
    {
        $datos = modelos::findOrFail($id);

        //$deleted = DB::table('modelos')->where('id', $id)->delete();
        $actualizar = DB::table('modelos')
            ->where('id', $id)
            ->update(['estado' => 0]);

        session()->flash('message', 'Registro eliminado correctamente.');
        return Redirect::back();
    }

    public function obtener_modelos($nID)
    {
        $modelos = Modelos::where('id_marca', $nID)->where('estado', 1)->get();
        return $modelos;
    }
}
