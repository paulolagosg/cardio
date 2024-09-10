<?php

namespace App\Http\Controllers;

use App\Models\PlazosPagos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PlazosPagosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public  function index()
    {
        $datos = PlazosPagos::get();

        return view('plazos_pagos.lista', compact('datos'));
    }

    public function agregar(Request $request)
    {
        return view('plazos_pagos.agregar');
    }

    public function agregar_guardar(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);

        $estado = new PlazosPagos();
        $estado->nombre = $request->nombre;
        $estado->slug = str_slug($request->nombre, '-');
        $estado->save();
        session()->flash('message', 'Registro agregado correctamente.');
        return redirect()->to('/plazos_pagos/lista');
    }

    public function editar($id)
    {
        $datos = PlazosPagos::where('slug', $id)->get();
        if (count($datos) <= 0) {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->route('plazos_pagos.index')->with('error', 'El registro no existe.');
        } else {
            $datos = PlazosPagos::where('slug', $id)->first();
        }

        return view('plazos_pagos.editar', compact('datos'));
    }

    public function actualizar(Request $request)
    {
        $nombre = $request->nombre;
        $id = $request->id;

        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);

        $actualizar = DB::table('plazos_pagos')
            ->where('id', $id)
            ->update(['nombre' => $nombre, 'slug' => str_slug($nombre, '-')]);

        session()->flash('message', 'Registro modificado correctamente.');
        return redirect()->to('/plazos_pagos/lista');
    }

    public function eliminar($id)
    {
        $datos = PlazosPagos::findOrFail($id);

        //$deleted = DB::table('plazos_pagos')->where('id', $id)->delete();
        $actualizar = DB::table('plazos_pagos')
            ->where('id', $id)
            ->update(['estado' => 0]);

        session()->flash('message', 'Registro eliminado correctamente.');
        return Redirect::back();
    }
}
