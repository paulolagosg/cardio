<?php

namespace App\Http\Controllers;

use App\Models\Bancos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class BancosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public  function index()
    {
        $datos = Bancos::where('estado',1)->get();

        return view('bancos.lista', compact('datos'));
    }

    public function agregar(Request $request)
    {
        return view('bancos.agregar');
    }

    public function agregar_guardar(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);

        $estado = new Bancos();
        $estado->nombre = $request->nombre;
        $estado->slug = str_slug($request->nombre, '-');
        $estado->save();
        session()->flash('message', 'Registro agregado correctamente.');
        return redirect()->to('/bancos/lista');
    }

    public function editar($id)
    {
        $datos = Bancos::where('slug', $id)->get();
        if (count($datos) <= 0) {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->route('bancos.index')->with('error', 'El registro no existe.');
        } else {
            $datos = Bancos::where('slug', $id)->first();
        }

        return view('bancos.editar', compact('datos'));
    }

    public function actualizar(Request $request)
    {
        $nombre = $request->nombre;
        $id = $request->id;

        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);

        $actualizar = DB::table('bancos')
            ->where('id', $id)
            ->update(['nombre' => $nombre, 'slug' => str_slug($nombre, '-')]);

        session()->flash('message', 'Registro modificado correctamente.');
        return redirect()->to('/bancos/lista');
    }

    public function eliminar($id)
    {
        $datos = Bancos::findOrFail($id);

        //$deleted = DB::table('bancos')->where('id', $id)->delete();
        $actualizar = DB::table('bancos')
            ->where('id', $id)
            ->update(['estado' => 0]);

        session()->flash('message', 'Registro eliminado correctamente.');
        return Redirect::back();
    }
}
