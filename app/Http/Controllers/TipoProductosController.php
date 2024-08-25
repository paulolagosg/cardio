<?php

namespace App\Http\Controllers;

use App\Models\Productos;
use App\Models\TipoProductos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class TipoProductosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public  function index()
    {
        $datos = TipoProductos::get();

        return view('tipos_productos.lista', compact('datos'));
    }

    public function agregar(Request $request)
    {
        return view('tipos_productos.agregar');
    }

    public function agregar_guardar(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);

        $estado = new TipoProductos();
        $estado->nombre = $request->nombre;
        $estado->slug = str_slug($request->nombre, '-');
        $estado->save();
        session()->flash('message', 'Registro agregado correctamente.');
        return redirect()->to('/tipos_productos/lista');
    }

    public function editar($id)
    {
        $datos = TipoProductos::where('slug', $id)->get();
        if (count($datos) <= 0) {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->route('tipos_productos.index')->with('error', 'El registro no existe.');
        } else {
            $datos = TipoProductos::where('slug', $id)->first();
        }

        return view('tipos_productos.editar', compact('datos'));
    }

    public function actualizar(Request $request)
    {
        $nombre = $request->nombre;
        $id = $request->id;

        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);

        $actualizar = DB::table('tipo_productos')
            ->where('id', $id)
            ->update(['nombre' => $nombre, 'slug' => str_slug($nombre, '-')]);

        session()->flash('message', 'Registro modificado correctamente.');
        return redirect()->to('/tipos_productos/lista');
    }

    public function eliminar($id)
    {
        $datos = TipoProductos::findOrFail($id);

        $deleted = DB::table('tipo_productos')->where('id', $id)->delete();

        session()->flash('message', 'Registro eliminado correctamente.');
        return Redirect::back();
    }

    public function obtener_productos($nID)
    {
        $productos = Productos::join('marcas', 'marcas.id', 'productos.id_marca')
            ->join('modelos', 'modelos.id', 'productos.id_modelo')
            ->where('productos.id_tipo_producto', $nID)
            ->where('productos.estado', 1)
            ->select('productos.id', DB::raw("productos.nombre||' - '||marcas.nombre||' - '||modelos.nombre as nombre"))
            ->orderBy('nombre', 'ASC')->get();
        return $productos;
    }
}
