<?php

namespace App\Http\Controllers;

use App\Models\TiposPagos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class TiposPagosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public  function index()
    {
        $datos = TiposPagos::get();

        return view('formas_pago.lista', compact('datos'));
    }

    public function agregar(Request $request)
    {
        return view('formas_pago.agregar');
    }

    public function agregar_guardar(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);

        $estado = new TiposPagos();
        $estado->nombre = $request->nombre;
        $estado->slug = str_slug($request->nombre, '-');
        $estado->save();
        session()->flash('message', 'Registro agregado correctamente.');
        return redirect()->to('/formas_pago/lista');
    }

    public function editar($id)
    {
        $datos = TiposPagos::where('slug', $id)->get();
        if (count($datos) <= 0) {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->route('formas_pago.index')->with('error', 'El registro no existe.');
        } else {
            $datos = TiposPagos::where('slug', $id)->first();
        }

        return view('formas_pago.editar', compact('datos'));
    }

    public function actualizar(Request $request)
    {
        $nombre = $request->nombre;
        $id = $request->id;

        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);

        $actualizar = DB::table('tipos_pagos')
            ->where('id', $id)
            ->update(['nombre' => $nombre, 'slug' => str_slug($nombre, '-')]);

        session()->flash('message', 'Registro modificado correctamente.');
        return redirect()->to('/formas_pago/lista');
    }

    public function eliminar($id)
    {
        $datos = TiposPagos::findOrFail($id);

        //$deleted = DB::table('tipos_pagos')->where('id', $id)->delete();
        $actualizar = DB::table('tipos_pagos')
            ->where('id', $id)
            ->update(['estado' => 0]);

        session()->flash('message', 'Registro eliminado correctamente.');
        return Redirect::back();
    }
}
