<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use App\Models\Comunas;
use App\Models\Contactos;
use App\Models\Regiones;
use App\Models\Rubros;
use App\Models\Trazabilidades;
use Exception;
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
                DB::raw("rubros.nombre as rubro"),
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

        $nExisteRut = Clientes::where('rut', $request->rut)->count();
        if ($nExisteRut > 0) {
            session()->flash('error', 'El RUT del cliente ya existe.');
            return redirect()->to('/clientes/agregar')->withInput($request->input());
        }
        try {
            DB::beginTransaction();
            DB::enableQueryLog();
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

            $id_cliente = $clientes->id;

            $contactos = new Contactos();
            $contactos->id_cliente = $id_cliente;
            $contactos->id_tipo_contacto = 1;
            $contactos->nombre = $request->nombre_principal;
            $contactos->telefono = $request->telefono_principal;
            $contactos->correo_electronico = $request->correo_principal;
            $contactos->estado = 1;
            $contactos->slug = str_slug($request->nombre_principal, '-');
            $contactos->save();

            $slug = Clientes::where('id', $id_cliente)->first();

            DB::commit();

            session()->flash('message', 'Registro agregado correctamente.');
            return redirect()->to('/clientes/editar/' . $slug->slug);
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al agregar el registro.');
            return redirect()->to('/clientes/agregar');
        }
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
        $contacto_principal = Contactos::where('id_cliente', $datos->id)->where('id_tipo_contacto', 1)->first();
        $contactos_secundarios = Contactos::where('id_cliente', $datos->id)->where('id_tipo_contacto', 2)->get();

        return view('clientes.editar', compact('datos', 'rubros', 'regiones', 'comunas', 'contacto_principal', 'contactos_secundarios'));
    }

    public function actualizar(Request $request)
    {
        $nombre = $request->nombre;
        $id = $request->id;
        $id_cp = $request->id_cp;

        $validated = $request->validate([
            'nombre' => 'required|max:200',
        ]);
        try {
            DB::beginTransaction();
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
                    'latitud' => $request->latitud,
                    'longitud' => $request->longitud,
                    'correo' => $request->correo,
                    'sitio_web' => $request->sitio_web
                ]);
            if (isset($id_cp)) {
                $actualizar_contacto = DB::table('contactos')
                    ->where('id', $id_cp)
                    ->update([
                        'nombre' => $request->nombre_principal,
                        'telefono' => $request->telefono_principal,
                        'correo_electronico' => $request->correo_principal
                    ]);
            } else {
                $contactos = new Contactos();
                $contactos->id_cliente = $id;
                $contactos->id_tipo_contacto = 1;
                $contactos->nombre = $request->nombre_principal;
                $contactos->telefono = $request->telefono_principal;
                $contactos->correo_electronico = $request->correo_principal;
                $contactos->estado = 1;
                $contactos->slug = str_slug($request->nombre_principal, '-');
                $contactos->save();
            }

            DB::commit();

            session()->flash('message', 'Registro modificado correctamente.');
            return redirect()->to('/clientes/lista');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al modificar el registro.');
            return redirect()->to('/clientes/agregar');
        }
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
        $contacto_principal = Contactos::where('id_cliente', $datos->id)->where('id_tipo_contacto', 1)->first();
        $contactos_secundarios = Contactos::where('id_cliente', $datos->id)->where('id_tipo_contacto', 2)->get();
        $codigo_region = Regiones::where('id', $datos->id_region)->first();

        return view('clientes.ver', compact('datos', 'rubros', 'regiones', 'comunas', 'codigo_region', 'contacto_principal', 'contactos_secundarios'));
    }
    public function eliminar($id)
    {
        $datos = clientes::findOrFail($id);

        //$deleted = DB::table('clientes')->where('id', $id)->delete();

        $actualizar_contacto = DB::table('clientes')
            ->where('id', $id)
            ->update([
                'estado' => 0
            ]);

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
            ->where('regiones.nombre', $codigo)
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

    public function clientes_comunas($region)
    {
        $clientes_comunas = Comunas::join('regiones', 'regiones.id', 'comunas.id_region')
            ->leftJoin('clientes', 'comunas.id', 'clientes.id_comuna')
            ->groupBy('comunas.nombre')
            ->groupBy('regiones.nombre')
            ->groupBy('comunas.slug')
            ->orderBy('comunas.nombre')
            ->whereRaw("initcap(regiones.nombre) ='" . $region . "'")
            ->select(DB::raw("count(clientes.id) as total"), DB::raw("comunas.nombre  as comuna"), 'regiones.nombre', 'comunas.slug')
            ->get();

        $total_comunas = $clientes_comunas->count();

        return view('clientes.grupo_comunas', compact('clientes_comunas'));
    }
    public function clientes_comunas_detalle($comuna)
    {
        $datos = Clientes::join('rubros', 'rubros.id', 'clientes.id_rubro')
            ->join('regiones', 'regiones.id', 'clientes.id_region')
            ->join('comunas', 'comunas.id', 'clientes.id_comuna')
            ->where('comunas.slug', $comuna)
            ->select(
                'clientes.*',
                DB::raw("INITCAP(regiones.nombre) as region"),
                DB::raw("INITCAP(comunas.nombre) as comuna"),
                DB::raw("rubros.nombre as rubro")
            )
            ->get();

        return view('clientes.lista_comuna', compact('datos'));
    }

    public function guardar_secundario(Request $request)
    {
        try {
            DB::beginTransaction();
            if (isset($request->id_contacto)) {
                $actualizar_contacto = DB::table('contactos')
                    ->where('id', $request->id_contacto)
                    ->update([
                        'nombre' => $request->nombre_secundario,
                        'telefono' => $request->telefono_secundario,
                        'correo_electronico' => $request->correo_secundario
                    ]);
                DB::commit();

                return "OKContacto modificado correctamente";
            } else {
                $contactos = new Contactos();
                $contactos->id_cliente = $request->id_cliente;
                $contactos->id_tipo_contacto = 2;
                $contactos->nombre = $request->nombre_secundario;
                $contactos->telefono = $request->telefono_secundario;
                $contactos->correo_electronico = $request->correo_secundario;
                $contactos->estado = 1;
                $contactos->slug = str_slug($request->nombre_secundario, '-');
                $contactos->save();
                DB::commit();

                return "OKContacto agregado correctamente";
            }
        } catch (Exception $e) {
            DB::rollBack();
            //return response()->json(['error' => $e->getMessage()], 500);
            return "error" . $e->getMessage();
        }
    }

    public function eliminar_contacto($id)
    {
        $eliminar = Contactos::where('id', $id)->delete();
        session()->flash('message', 'Contancto eliminado correctamente.');
        return redirect()->back();
    }
}
