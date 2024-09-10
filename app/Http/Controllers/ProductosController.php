<?php

namespace App\Http\Controllers;

use App\Models\Marcas;
use App\Models\Productos;
use App\Models\TipoProductos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

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

        $rutaBase = '/app/public/';

        if (!file_exists($rutaBase . '/productos/')) {
            Storage::makeDirectory($rutaBase . '/productos/');
        }
        if ($request->file('foto') !== null) {
            $documento = $request->file('foto');
            $nombre_original = pathinfo($documento->getClientOriginalName(), PATHINFO_FILENAME);

            $path = $request->file('foto')->store('public/productos');

            // Obtener la URL pública del archivo
            $url = Storage::url($path);
            if (Storage::exists($path)) {
                $productos = new productos();
                $productos->nombre = $request->nombre;
                $productos->id_marca = $request->id_marca;
                $productos->id_modelo = $request->id_modelo;
                $productos->id_tipo_producto = $request->id_tipo;
                $productos->slug = str_slug($request->nombre, '-');
                $productos->precio = $request->precio;
                $productos->nombre_archivo = $nombre_original;
                $productos->ruta = $url;
                $productos->sku = $request->sku;
                $productos->descripcion = $request->descripcion;
                $productos->save();
                session()->flash('message', 'Registro agregado correctamente.');
                return redirect()->to('/productos/lista');
            } else {
                session()->flash('error', 'Ocurrió un error al subir la foto del producto, favor intente nuevamente.');
                return redirect()->back();
            }
        } else {
            $productos = new productos();
            $productos->nombre = $request->nombre;
            $productos->id_marca = $request->id_marca;
            $productos->id_modelo = $request->id_modelo;
            $productos->id_tipo_producto = $request->id_tipo;
            $productos->slug = str_slug($request->nombre, '-');
            $productos->precio = $request->precio;
            $productos->sku = $request->sku;
            $productos->descripcion = $request->descripcion;
            $productos->save();
            session()->flash('message', 'Registro agregado correctamente.');
            return redirect()->to('/productos/lista');
        }
    }

    function clean($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\_\-]/', '', $string); // Removes special chars.
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
        if ($request->file('foto') !== null) {
            $documento = $request->file('foto');
            $nombre_original = pathinfo($documento->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $documento->getClientOriginalExtension();

            if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
                session()->flash('error', 'Formato de archivo no permitido.');
                return redirect()->back();
            }

            $path = $request->file('foto')->store('public/productos');

            // Obtener la URL pública del archivo
            $url = Storage::url($path);
            if (Storage::exists($path)) {

                $actualizar = DB::table('productos')
                    ->where('id', $id)
                    ->update([
                        'nombre' => $nombre,
                        'slug' => str_slug($nombre, '-'),
                        'id_marca' => $id_marca,
                        'id_modelo' => $id_modelo,
                        'id_tipo_producto' => $id_tipo,
                        'ruta' => $url,
                        'sku' => $request->sku,
                        'descripcion' => $request->descripcion,
                        'nombre_archivo' => $nombre_original,
                        'precio' => $request->precio
                    ]);
                session()->flash('message', 'Registro modificado correctamente.');
                return redirect()->to('/productos/lista');
            } else {
                session()->flash('error', 'Ocurrió un error al subir la foto del producto, favor intente nuevamente.');
                return redirect()->back();
            }
        } else {
            $actualizar = DB::table('productos')
                ->where('id', $id)
                ->update([
                    'nombre' => $nombre,
                    'slug' => str_slug($nombre, '-'),
                    'id_marca' => $id_marca,
                    'id_modelo' => $id_modelo,
                    'id_tipo_producto' => $id_tipo,
                    'sku' => $request->sku,
                    'descripcion' => $request->descripcion,
                    'precio' => $request->precio
                ]);
            session()->flash('message', 'Registro modificado correctamente.');
            return redirect()->to('/productos/lista');
        }
    }

    public function eliminar($id)
    {
        $datos = productos::findOrFail($id);

        //$deleted = DB::table('productos')->where('id', $id)->delete();
        $actualizar = DB::table('productos')
            ->where('id', $id)
            ->update([
                'estado' => 0
            ]);

        session()->flash('message', 'Registro eliminado correctamente.');
        return Redirect::back();
    }
    public function obtener_precio($id)
    {
        $datos = Productos::where('id', $id)->first();
        $precio = $datos->precio;
        if (!isset($precio)) {
            $precio = 0;
        }
        return $precio;
    }
}
