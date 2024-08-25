<?php

namespace App\Http\Controllers;

use App\Models\Marcas;
use App\Models\Productos;
use App\Models\TipoProductos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ProductosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public  function index()
    {
        $datos = Productos::join('marcas', 'marcas.id', 'productos.id_marca')
            ->join('modelos', 'modelos.id', 'productos.id_modelo')
            ->join('tipo_productos', 'tipo_productos.id', 'productos.id_tipo_producto')
            ->select(
                'productos.*',
                DB::raw("marcas.nombre as marca"),
                DB::raw("modelos.nombre as modelo"),
                DB::raw("tipo_productos.nombre as tipo_producto")
            )
            ->get();

        return view('productos.lista', compact('datos'));
    }

    public function agregar(Request $request)
    {
        $marcas = Marcas::where('estado', 1)->orderBy('nombre', 'asc')->get();
        $tipos = TipoProductos::where('estado', 1)->orderBy('nombre', 'asc')->get();

        return view('productos.agregar', compact('marcas', 'tipos'));
    }

    public function agregar_guardar(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:200',
            'id_marca' => 'required',
            'id_modelo' => 'required',
            'id_tipo' => 'required'
        ]);

        $productos = new productos();
        $productos->nombre = $request->nombre;
        $productos->id_marca = $request->id_marca;
        $productos->id_modelo = $request->id_modelo;
        $productos->id_tipo_producto = $request->id_tipo;
        $productos->slug = str_slug($request->nombre, '-');
        $productos->save();
        session()->flash('message', 'Registro agregado correctamente.');
        return redirect()->to('/productos/lista');
    }

    public function editar($id)
    {
        $datos = productos::where('slug', $id)->get();
        if (count($datos) <= 0) {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->route('productos.index')->with('error', 'El registro no existe.');
        } else {
            $datos = productos::where('slug', $id)->first();
        }
        $marcas = Marcas::where('estado', 1)->orderBy('nombre', 'asc')->get();
        $tipos = TipoProductos::where('estado', 1)->orderBy('nombre', 'asc')->get();


        return view('productos.editar', compact('datos', 'marcas', 'tipos'));
    }

    public function actualizar(Request $request)
    {
        $nombre = $request->nombre;
        $id = $request->id;
        $id_marca = $request->id_marca;
        $id_modelo = $request->id_modelo;
        $id_tipo = $request->id_tipo;

        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);

        $actualizar = DB::table('productos')
            ->where('id', $id)
            ->update([
                'nombre' => $nombre,
                'slug' => str_slug($nombre, '-'),
                'id_marca' => $id_marca,
                'id_modelo' => $id_modelo,
                'id_tipo_producto' => $id_tipo
            ]);

        session()->flash('message', 'Registro modificado correctamente.');
        return redirect()->to('/productos/lista');
    }

    public function eliminar($id)
    {
        $datos = productos::findOrFail($id);

        $deleted = DB::table('productos')->where('id', $id)->delete();

        session()->flash('message', 'Registro eliminado correctamente.');
        return Redirect::back();
    }
}
