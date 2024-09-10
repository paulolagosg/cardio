<?php

namespace App\Http\Controllers;

use App\Models\EstadosCotizaciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class EstadosCotizacionesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public  function index()
    {
        $datos = EstadosCotizaciones::get();

        return view('estados_cotizaciones.lista', compact('datos'));
    }

    public function agregar(Request $request)
    {
        return view('estados_cotizaciones.agregar');
    }

    public function agregar_guardar(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);

        $estado = new EstadosCotizaciones();
        $estado->nombre = $request->nombre;
        $estado->slug = str_slug($request->nombre, '-');
        $estado->save();
        session()->flash('message', 'Registro agregado correctamente.');
        return redirect()->to('/estados_cotizaciones/lista');
    }

    public function editar($id)
    {
        $datos = EstadosCotizaciones::where('slug', $id)->get();
        if (count($datos) <= 0) {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->route('estados_cotizaciones.index')->with('error', 'El registro no existe.');
        } else {
            $datos = EstadosCotizaciones::where('slug', $id)->first();
        }

        return view('estados_cotizaciones.editar', compact('datos'));
    }

    public function actualizar(Request $request)
    {
        $nombre = $request->nombre;
        $id = $request->id;

        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);

        $actualizar = DB::table('estados_cotizaciones')
            ->where('id', $id)
            ->update(['nombre' => $nombre, 'slug' => str_slug($nombre, '-')]);

        session()->flash('message', 'Registro modificado correctamente.');
        return redirect()->to('/estados_cotizaciones/lista');
    }

    public function eliminar($id)
    {
        $datos = EstadosCotizaciones::findOrFail($id);

        $deleted = DB::table('estados_cotizaciones')->where('id', $id)->delete();

        session()->flash('message', 'Registro eliminado correctamente.');
        return Redirect::back();
    }
}
