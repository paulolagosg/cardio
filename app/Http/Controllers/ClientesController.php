<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use App\Models\Comunas;
use App\Models\Regiones;
use App\Models\Rubros;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ClientesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public  function index()
    {
        $datos = Clientes::join('rubros', 'rubros.id', 'clientes.id_rubro')
            ->join('regiones', 'regiones.id', 'clientes.id_region')
            ->join('comunas', 'comunas.id', 'clientes.id_comuna')
            ->select(
                'clientes.*',
                DB::raw("INITCAP(regiones.nombre) as region"),
                DB::raw("INITCAP(comunas.nombre) as comuna"),
                DB::raw("rubros.nombre as rubro")
            )
            ->get();

        return view('clientes.lista', compact('datos'));
    }

    public function agregar(Request $request)
    {
        $rubros = Rubros::where('estado', 1)->get();
        $regiones = Regiones::where('estado', 1)
            ->select(DB::raw("INITCAP(nombre) as nombre"), 'id')
            ->orderBy('orden', 'asc')
            ->get();
        $comunas = Comunas::where('estado', 1)->select(DB::raw("INITCAP(nombre) as nombre"), 'id')->get();

        return view('clientes.agregar', compact('rubros', 'regiones', 'comunas'));
    }

    public function agregar_guardar(Request $request)
    {
        //dd($request);
        $validated = $request->validate([
            'nombre' => 'required|max:200',
            'id_region' => 'required'
        ]);

        $clientes = new clientes();
        $clientes->nombre = $request->nombre;
        $clientes->rut = $request->rut;
        $clientes->id_rubro = $request->id_rubro;
        $clientes->razon_social = $request->razon_social;
        $clientes->giro = $request->giro;
        $clientes->direccion = $request->direccion;
        $clientes->id_region = $request->id_region;
        $clientes->id_comuna = $request->id_comuna;
        $clientes->telefono = $request->telefono;
        $clientes->correo = $request->correo;
        $clientes->sitio_web = $request->sitio_web;
        $clientes->slug = str_slug($request->nombre, '-');
        $clientes->save();
        session()->flash('message', 'Registro agregado correctamente.');
        return redirect()->to('/clientes/lista');
    }

    public function editar($id)
    {
        $datos = clientes::where('slug', $id)->get();
        if (count($datos) <= 0) {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->route('clientes.index')->with('error', 'El registro no existe.');
        } else {
            $datos = clientes::where('slug', $id)->first();
        }
        $rubros = Rubros::where('estado', 1)->get();
        $regiones = Regiones::where('estado', 1)
            ->select(DB::raw("INITCAP(nombre) as nombre"), 'id')
            ->orderBy('orden', 'asc')
            ->get();
        $comunas = Comunas::where('estado', 1)->select(DB::raw("INITCAP(nombre) as nombre"), 'id')->get();

        return view('clientes.editar', compact('datos', 'rubros', 'regiones', 'comunas'));
    }

    public function actualizar(Request $request)
    {
        $nombre = $request->nombre;
        $id = $request->id;

        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);

        $actualizar = DB::table('clientes')
            ->where('id', $id)
            ->update([
                'nombre' => $nombre,
                'slug' => str_slug($nombre, '-'),
                'id_rubro' => $request->id_rubro,
                'razon_social' => $request->razon_social,
                'giro' => $request->giro,
                'direccion' => $request->direccion,
                'id_region' => $request->id_region,
                'id_comuna' => $request->id_comuna,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'sitio_web' => $request->sitio_web
            ]);

        session()->flash('message', 'Registro modificado correctamente.');
        return redirect()->to('/clientes/lista');
    }

    public function ver($id)
    {
        $datos = clientes::where('slug', $id)->get();
        if (count($datos) <= 0) {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->route('clientes.index')->with('error', 'El registro no existe.');
        } else {
            $datos = clientes::where('slug', $id)->first();
        }
        $rubros = Rubros::where('estado', 1)->get();
        $regiones = Regiones::where('estado', 1)
            ->select(DB::raw("INITCAP(nombre) as nombre"), 'id')
            ->orderBy('orden', 'asc')
            ->get();
        $comunas = Comunas::where('estado', 1)->select(DB::raw("INITCAP(nombre) as nombre"), 'id')->get();
        $codigo_region = Regiones::where('id', $datos->id_region)->first();
        return view('clientes.ver', compact('datos', 'rubros', 'regiones', 'comunas', 'codigo_region'));
    }
    public function eliminar($id)
    {
        $datos = clientes::findOrFail($id);

        $deleted = DB::table('clientes')->where('id', $id)->delete();

        session()->flash('message', 'Registro eliminado correctamente.');
        return Redirect::back();
    }

    public function obtener_comunas($nID)
    {
        $comunas = Comunas::where('id_region', $nID)
            ->where('estado', 1)
            ->orderBy('nombre', 'ASC')
            ->select(DB::raw("INITCAP(nombre) as nombre"), 'id')
            ->get();
        return $comunas;
    }


    public function clientes_region($codigo)
    {
        $datos = Clientes::join('rubros', 'rubros.id', 'clientes.id_rubro')
            ->join('regiones', 'regiones.id', 'clientes.id_region')
            ->join('comunas', 'comunas.id', 'clientes.id_comuna')
            ->where('regiones.codigo', $codigo)
            ->select(
                'clientes.*',
                DB::raw("INITCAP(regiones.nombre) as region"),
                DB::raw("INITCAP(comunas.nombre) as comuna"),
                DB::raw("rubros.nombre as rubro")
            )
            ->get();

        return view('clientes.lista_region', compact('datos'));
    }

    public function clientes_rubro($codigo)
    {
        $datos = Clientes::join('rubros', 'rubros.id', 'clientes.id_rubro')
            ->join('regiones', 'regiones.id', 'clientes.id_region')
            ->join('comunas', 'comunas.id', 'clientes.id_comuna')
            ->whereRaw("lower(rubros.nombre) = lower('" . $codigo . "')")
            ->select(
                'clientes.*',
                DB::raw("INITCAP(regiones.nombre) as region"),
                DB::raw("INITCAP(comunas.nombre) as comuna"),
                DB::raw("INITCAP(rubros.nombre) as rubro")
            )
            ->get();

        return view('clientes.lista_rubro', compact('datos'));
    }
}
