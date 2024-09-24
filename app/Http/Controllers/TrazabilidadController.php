<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use App\Models\Comunas;
use App\Models\Productos;
use App\Models\Regiones;
use App\Models\Rubros;
use App\Models\TipoProductos;
use App\Models\TiposMantenciones;
use App\Models\Trazabilidades;
use App\Models\TrazabilidadMantenciones;
use App\Models\TrazabilidadProductos;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Hamcrest\Type\IsNumeric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Mail;

class TrazabilidadController extends Controller
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
                DB::raw("regiones.nombre as region"),
                DB::raw("comunas.nombre as comuna"),
                DB::raw("rubros.nombre as rubro")
            )
            ->get();

        return view('clientes.lista', compact('datos'));
    }

    public function agregar(Request $request)
    {
        $clientes = Clientes::where('estado', 1)->orderBy('nombre', 'ASC')->get();
        $deas = Productos::join('marcas', 'marcas.id', 'productos.id_marca')
            ->join('modelos', 'modelos.id', 'productos.id_modelo')
            ->where('productos.estado', 1)
            ->where('id_tipo_producto', 1)
            ->orderBy('nombre', 'ASC')
            ->select('productos.id', DB::raw("productos.nombre||' - '||marcas.nombre|| ' - '||modelos.nombre as nombre"))
            ->get();
        $baterias =   Productos::where('estado', 1)->where('id_tipo_producto', 2)->orderBy('nombre', 'ASC')->get();
        $electrodo_adulto =   Productos::where('estado', 1)->where('id_tipo_producto', 3)->orderBy('nombre', 'ASC')->get();
        $electrodo_pediatrico =   Productos::where('estado', 1)->where('id_tipo_producto', 4)->orderBy('nombre', 'ASC')->get();

        return view('trazabilidad.agregar', compact('clientes', 'deas', 'baterias', 'electrodo_adulto', 'electrodo_pediatrico'));
    }

    public function agregar_guardar(Request $request)
    {


        $registro = new Trazabilidades();
        $registro->id_cliente = $request->id_cliente;
        $registro->id_producto = $request->id_producto;
        $registro->numero_serie = $request->numero_serie;
        $registro->ubicacion = $request->ubicacion;
        $registro->factura = $request->factura;
        $registro->slug = str_slug($request->numero_serie . $request->id_producto . $request->id_cliente, '-');
        $registro->estado = 1;
        $registro->save();
        session()->flash('message', 'Registro agregado correctamente.');
        return redirect()->to('/trazabilidad/editar/' . $registro->slug);
    }

    public function agregar_sc(Request $request)
    {
        $clientes = Clientes::where('estado', 1)->orderBy('nombre', 'ASC')->get();
        $deas = Productos::join('marcas', 'marcas.id', 'productos.id_marca')
            ->join('modelos', 'modelos.id', 'productos.id_modelo')
            ->where('productos.estado', 1)
            ->where('id_tipo_producto', 1)
            ->orderBy('nombre', 'ASC')
            ->select('productos.id', DB::raw("productos.nombre||' - '||marcas.nombre|| ' - '||modelos.nombre as nombre"))
            ->get();
        // $baterias =   Productos::where('estado', 1)->where('id_tipo_producto', 2)->orderBy('nombre', 'ASC')->get();
        // $electrodo_adulto =   Productos::where('estado', 1)->where('id_tipo_producto', 3)->orderBy('nombre', 'ASC')->get();
        // $electrodo_pediatrico =   Productos::where('estado', 1)->where('id_tipo_producto', 4)->orderBy('nombre', 'ASC')->get();

        $rubros = Rubros::where('estado', 1)->get();
        $regiones = Regiones::where('estado', 1)
            ->select(DB::raw("INITCAP(nombre) as nombre"), 'id')
            ->orderBy('orden', 'asc')
            ->get();
        $comunas = Comunas::where('estado', 1)->select(DB::raw("INITCAP(nombre) as nombre"), 'id')->get();


        return view('trazabilidad.agregar_sc', compact('clientes', 'deas', 'rubros', 'regiones', 'comunas'));
    }

    public function agregar_guardar_sc(Request $request)
    {
        //dd($request);
        try {
            DB::beginTransaction();
            $aleatoreo = rand(100, 999);
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
            $clientes->latitud = $request->latitud;
            $clientes->longitud = $request->longitud;
            $clientes->correo = $request->correo;
            $clientes->sitio_web = $request->sitio_web;
            $clientes->slug = str_slug($request->nombre . "-" . $aleatoreo, '-');
            $clientes->save();

            $registro = new Trazabilidades();
            $registro->id_cliente = $clientes->id;
            $registro->id_producto = $request->id_producto;
            $registro->numero_serie = $request->numero_serie;
            $registro->ubicacion = $request->ubicacion;
            $registro->factura = $request->factura;
            $registro->slug = str_slug($request->numero_serie . $request->id_producto . $request->id_cliente, '-');
            $registro->estado = 1;
            $registro->save();
            DB::commit();
            session()->flash('message', 'Registro agregado correctamente.');
            return redirect()->to('/trazabilidad/editar/' . $registro->slug);
        } catch (Exception $e) {
            DB::rollBack();
            return "error" . $e->getMessage();
        }
    }

    public function editar($id)
    {
        $datos = Trazabilidades::where('slug', $id)->get();
        if (count($datos) <= 0) {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->route('trazabilidad.vencimientos')->with('error', 'El registro no existe.');
        } else {
            $datos = Trazabilidades::where('slug', $id)->first();
        }
        $clientes = Clientes::where('estado', 1)->orderBy('nombre', 'ASC')->get();
        $deas = Productos::join('marcas', 'marcas.id', 'productos.id_marca')
            ->join('modelos', 'modelos.id', 'productos.id_modelo')
            ->where('productos.estado', 1)
            ->where('id_tipo_producto', 1)
            ->orderBy('nombre', 'ASC')
            ->select('productos.id', DB::raw("productos.nombre||' - '||marcas.nombre|| ' - '||modelos.nombre as nombre"))
            ->get();
        $baterias =   Productos::where('estado', 1)->where('id_tipo_producto', 2)->orderBy('nombre', 'ASC')->get();
        $electrodo_adulto =   Productos::where('estado', 1)->where('id_tipo_producto', 3)->orderBy('nombre', 'ASC')->get();
        $electrodo_pediatrico =   Productos::where('estado', 1)->where('id_tipo_producto', 4)->orderBy('nombre', 'ASC')->get();
        $tipos_productos = TipoProductos::where('estado', 1)->where('id', '<>', 1)->orderBy('nombre', 'ASC')->get();
        $tipos_mantenciones = TiposMantenciones::where('estado', 1)->orderBy('nombre', 'ASC')->get();

        $dispositivos = DB::table('trazabilidad_productos')
            ->join('trazabilidades', 'trazabilidades.id', 'trazabilidad_productos.id_trazabilidad')
            ->join('productos', 'productos.id', 'trazabilidad_productos.id_producto')
            ->join('tipo_productos', 'tipo_productos.id', 'productos.id_tipo_producto')
            ->join('marcas', 'marcas.id', 'productos.id_marca')
            ->join('modelos', 'modelos.id', 'productos.id_modelo')
            ->join('estados_vencimientos', 'estados_vencimientos.id', 'trazabilidad_productos.id_estado_vencimiento')
            ->select(
                'trazabilidad_productos.*',
                DB::raw("marcas.nombre as marca"),
                DB::raw("productos.nombre"),
                DB::raw("modelos.nombre as modelo"),
                DB::raw("estados_vencimientos.nombre as estado"),
                DB::raw("estados_vencimientos.id as id_estado"),
                DB::raw("tipo_productos.nombre as tipo_producto"),
                DB::raw("to_char(trazabilidad_productos.vencimiento,'DD/MM/YYYY') as fecha "),
                DB::raw("case when  (EXTRACT(YEAR FROM trazabilidad_productos.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
       (EXTRACT(MONTH FROM trazabilidad_productos.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) between 4 and 6 then 'yellow'
                        when  (EXTRACT(YEAR FROM trazabilidad_productos.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
       (EXTRACT(MONTH FROM trazabilidad_productos.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) between 2 and 3 then 'orange'
                        when  (EXTRACT(YEAR FROM trazabilidad_productos.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
       (EXTRACT(MONTH FROM trazabilidad_productos.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) between 0 and 1 then  'red;color:#fff' 
                        else 'green;color:#fff'
                    end as color")
            )
            ->where('trazabilidad_productos.id_trazabilidad', $datos->id)
            ->get();
        DB::enableQueryLog();
        $mantenciones = DB::table('trazabilidad_mantenciones')
            ->join('trazabilidades', 'trazabilidades.id', 'trazabilidad_mantenciones.id_trazabilidad')
            ->join('tipos_mantenciones', 'tipos_mantenciones.id', 'trazabilidad_mantenciones.id_tipo_mantencion')
            ->join('estados_mantenciones', 'estados_mantenciones.id', 'trazabilidad_mantenciones.id_estado_mantencion')
            ->select(
                'trazabilidad_mantenciones.*',
                DB::raw("tipos_mantenciones.nombre as tipo"),
                DB::raw("estados_mantenciones.nombre as estado"),
                DB::raw("estados_mantenciones.id as id_estado"),
                DB::raw("to_char(trazabilidad_mantenciones.vencimiento,'DD/MM/YYYY') as fecha "),
                DB::raw("case when  (EXTRACT(YEAR FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
       (EXTRACT(MONTH FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) between 7 and 12 then 'orange'
                        when  (EXTRACT(YEAR FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
       (EXTRACT(MONTH FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) <= 6 then  'red;color:#fff' 
                        else 'green;color:#fff'
                    end as color")
            )
            ->where('trazabilidad_mantenciones.id_trazabilidad', $datos->id)
            ->get();

        return view('trazabilidad.editar', compact('datos', 'clientes', 'deas', 'baterias', 'electrodo_adulto', 'electrodo_pediatrico', 'dispositivos', 'tipos_productos', 'tipos_mantenciones', 'mantenciones'));
    }

    public function actualizar(Request $request)
    {
        //dd($request);
        $ubicacion = $request->ubicacion;
        $id = $request->id;


        $actualizar = DB::table('trazabilidades')
            ->where('id', $id)
            ->update([
                'ubicacion' => $ubicacion,
                'factura' => $request->factura,

            ]);

        $datos = Trazabilidades::where('id', $id)->get();
        if (count($datos) <= 0) {
            session()->flash('error', 'El registro no existe.');
            return redirect()->route('trazabilidad.vencimientos')->with('error', 'El registro no existe.');
        } else {
            $datos = Trazabilidades::where('id', $id)->first();
        }
        // $clientes = Clientes::where('estado', 1)->orderBy('nombre', 'ASC')->get();
        // $deas = Productos::join('marcas', 'marcas.id', 'productos.id_marca')
        //     ->join('modelos', 'modelos.id', 'productos.id_modelo')
        //     ->where('productos.estado', 1)
        //     ->where('id_tipo_producto', 1)
        //     ->orderBy('nombre', 'ASC')
        //     ->select('productos.id', DB::raw("productos.nombre||' - '||marcas.nombre|| ' - '||modelos.nombre as nombre"))
        //     ->get();
        // $baterias =   Productos::where('estado', 1)->where('id_tipo_producto', 2)->orderBy('nombre', 'ASC')->get();
        // $electrodo_adulto =   Productos::where('estado', 1)->where('id_tipo_producto', 3)->orderBy('nombre', 'ASC')->get();
        // $electrodo_pediatrico =   Productos::where('estado', 1)->where('id_tipo_producto', 4)->orderBy('nombre', 'ASC')->get();
        // $tipos_productos = TipoProductos::where('estado', 1)->where('id', '<>', 1)->orderBy('nombre', 'ASC')->get();
        // $tipos_mantenciones = TiposMantenciones::where('estado', 1)->orderBy('nombre', 'ASC')->get();

        // $dispositivos = DB::table('trazabilidad_productos')
        //     ->join('trazabilidades', 'trazabilidades.id', 'trazabilidad_productos.id_trazabilidad')
        //     ->join('productos', 'productos.id', 'trazabilidad_productos.id_producto')
        //     ->join('tipo_productos', 'tipo_productos.id', 'productos.id_tipo_producto')
        //     ->join('marcas', 'marcas.id', 'productos.id_marca')
        //     ->join('modelos', 'modelos.id', 'productos.id_modelo')
        //     ->join('estados_vencimientos', 'estados_vencimientos.id', 'trazabilidad_productos.id_estado_vencimiento')
        //     ->select(
        //         'trazabilidad_productos.*',
        //         DB::raw("marcas.nombre as marca"),
        //         DB::raw("productos.nombre"),
        //         DB::raw("modelos.nombre as modelo"),
        //         DB::raw("estados_vencimientos.nombre as estado"),
        //         DB::raw("estados_vencimientos.id as id_estado"),
        //         DB::raw("tipo_productos.nombre as tipo_producto"),
        //         DB::raw("to_char(trazabilidad_productos.vencimiento,'DD/MM/YYYY') as fecha "),
        //         DB::raw("case when  (EXTRACT(YEAR FROM trazabilidad_productos.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
        //    (EXTRACT(MONTH FROM trazabilidad_productos.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) between 4 and 6 then 'yellow'
        //                     when  (EXTRACT(YEAR FROM trazabilidad_productos.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
        //    (EXTRACT(MONTH FROM trazabilidad_productos.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) between 2 and 3 then 'orange'
        //                     when  (EXTRACT(YEAR FROM trazabilidad_productos.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
        //    (EXTRACT(MONTH FROM trazabilidad_productos.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) between 0 and 1 then  'red;color:#fff' 
        //                     else 'green;color:#fff'
        //                 end as color")
        //     )
        //     ->where('trazabilidad_productos.id_trazabilidad', $datos->id)
        //     ->get();
        // DB::enableQueryLog();
        // $mantenciones = DB::table('trazabilidad_mantenciones')
        //     ->join('trazabilidades', 'trazabilidades.id', 'trazabilidad_mantenciones.id_trazabilidad')
        //     ->join('tipos_mantenciones', 'tipos_mantenciones.id', 'trazabilidad_mantenciones.id_tipo_mantencion')
        //     ->join('estados_mantenciones', 'estados_mantenciones.id', 'trazabilidad_mantenciones.id_estado_mantencion')
        //     ->select(
        //         'trazabilidad_mantenciones.*',
        //         DB::raw("tipos_mantenciones.nombre as tipo"),
        //         DB::raw("estados_mantenciones.nombre as estado"),
        //         DB::raw("estados_mantenciones.id as id_estado"),
        //         DB::raw("to_char(trazabilidad_mantenciones.vencimiento,'DD/MM/YYYY') as fecha "),
        //         DB::raw("case when  (EXTRACT(YEAR FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
        //    (EXTRACT(MONTH FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) between 7 and 12 then 'orange'
        //                     when  (EXTRACT(YEAR FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
        //    (EXTRACT(MONTH FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) <= 6 then  'red;color:#fff' 
        //                     else 'green;color:#fff'
        //                 end as color")
        //     )
        //     ->where('trazabilidad_mantenciones.id_trazabilidad', $datos->id)
        //     ->get();

        session()->flash('message', 'Registro modificado correctamente.');

        return redirect()->to('/trazabilidad/editar/' . $datos->slug);
    }

    public function eliminar($id)
    {

        $deleted = DB::table('trazabilidad_productos')->where('id', $id)->delete();

        session()->flash('message', 'Registro eliminado correctamente.');
        return Redirect::back();
    }
    public function eliminar_mantencion($id)
    {

        $deleted = DB::table('trazabilidad_mantenciones')->where('id', $id)->delete();

        session()->flash('message', 'Registro eliminado correctamente.');
        return Redirect::back();
    }



    public function obtener_dispositivos($nID)
    {
        $dispositivos = DB::table('trazabilidad_productos')
            ->join('trazabilidades', 'trazabilidades.id', 'trazabilidad_productos.id_trazabilidad')
            ->join('productos', 'productos.id', 'trazabilidad_productos.id_producto')
            ->join('tipo_productos', 'tipo_productos.id', 'productos.id_tipo_producto')
            ->join('marcas', 'marcas.id', 'productos.id_marca')
            ->join('modelos', 'modelos.id', 'productos.id_modelo')
            ->select(
                //'trazabilidad_productos.*',
                DB::raw("marcas.nombre as marca"),
                DB::raw("modelos.nombre as modelo"),
                DB::raw("tipo_productos.nombre as tipo_producto")
            )
            ->where('trazabilidad_productos.id_trazabilidad', $nID)
            ->get();
        //return datatables($dispositivos)->toJson();
    }

    public function guardar_dispositivo(Request $request)
    {
        try {
            DB::beginTransaction();
            $dispositivo = new TrazabilidadProductos();
            $dispositivo->id_trazabilidad = $request->id_trazabilidad;
            $dispositivo->id_producto = $request->id_producto;
            $dispositivo->lote = $request->lote;
            $dispositivo->vencimiento = $request->vencimiento;
            $dispositivo->guia_despacho = $request->guia;
            $dispositivo->factura = $request->factura;
            $dispositivo->id_estado_vencimiento = 1;
            $dispositivo->save();

            DB::commit();

            return "OKDispositivo agregado correctamente";
        } catch (Exception $e) {
            DB::rollBack();
            //return response()->json(['error' => $e->getMessage()], 500);
            return "error" . $e->getMessage();
        }
    }

    public function guardar_vencimiento(Request $request)
    {

        try {
            DB::beginTransaction();
            //DB::enableQueryLog();
            $dispositivo = DB::table('trazabilidad_productos')
                ->where('id', $request->id_trazabilidad_producto)
                ->update([
                    'id_producto' => $request->id_producto,
                    'lote' => $request->lote,
                    'vencimiento' => $request->vencimiento,
                    'guia_despacho' => $request->guia,
                    'factura' => $request->factura,
                ]);
            //dd(DB::getQueryLog());

            DB::commit();

            return "OKVencimiento  actualizado correctamente";
        } catch (Exception $e) {
            DB::rollBack();
            //return response()->json(['error' => $e->getMessage()], 500);
            return "error" . $e->getMessage();
        }
    }

    public function guardar_mantencion(Request $request)
    {

        try {
            DB::beginTransaction();
            $mantencion = new TrazabilidadMantenciones();
            $mantencion->id_trazabilidad = $request->id_trazabilidad;
            $mantencion->id_tipo_mantencion = $request->id_tipo_mantencion;
            $mantencion->vencimiento = $request->fecha_mantencion;
            $mantencion->guia_despacho = $request->guia_despacho;
            $mantencion->factura = $request->factura;
            $mantencion->id_estado_mantencion = 1;
            $mantencion->save();

            DB::commit();

            return "OKMantencion agregada correctamente";
        } catch (Exception $e) {
            DB::rollBack();
            //return response()->json(['error' => $e->getMessage()], 500);
            return "error" . $e->getMessage();
        }
    }

    public function guardar_mantencion_editar(Request $request)
    {
        try {
            DB::beginTransaction();
            DB::enableQueryLog();
            $dispositivo = DB::table('trazabilidad_mantenciones')
                ->where('id', $request->id_trazabilidad_mantencion)
                ->update([
                    'id_tipo_mantencion' => $request->id_tipo_mantencion,
                    'vencimiento' => $request->fecha_mantencion,
                    'guia_despacho' => $request->guia_despacho,
                    'factura' => $request->factura,
                ]);
            //dd(DB::getQueryLog());

            DB::commit();

            return "OKMantención  actualizada correctamente";
        } catch (Exception $e) {
            DB::rollBack();
            //return response()->json(['error' => $e->getMessage()], 500);
            return "error" . $e->getMessage();
        }
    }

    public function vencimientos($id = null)
    {
        $rango = "";
        $rm = "";
        $tab = "vencimientos";
        if (isset($id)) {
            if (is_numeric($id)) {
                if ($id == 1) {
                    $rango = '(0, 1)';
                }
                if ($id == 3) {
                    $rango = '(2, 3)';
                }
                if ($id == 6) {
                    $rango = '(4, 5, 6)';
                }
            } else {
                if ($id == 'a') {
                    $rm = '7 and 12';
                }
                if ($id == 's') {
                    $rm = '0 and 6';
                }
                $tab = "mantenciones";
            }
        }

        //dd($rango);
        DB::enableQueryLog();
        $vencimientos = DB::table('trazabilidad_productos')
            ->join('trazabilidades', 'trazabilidades.id', 'trazabilidad_productos.id_trazabilidad')
            ->join('clientes', 'clientes.id', 'trazabilidades.id_cliente')
            ->join('productos', 'productos.id', 'trazabilidad_productos.id_producto')
            ->join('productos as p2', 'p2.id', 'trazabilidades.id_producto')
            ->join('marcas as m2', 'm2.id', 'p2.id_marca')
            ->join('modelos as m', 'm.id', 'p2.id_modelo')
            ->join('tipo_productos', 'tipo_productos.id', 'productos.id_tipo_producto')
            ->join('marcas', 'marcas.id', 'productos.id_marca')
            ->join('modelos', 'modelos.id', 'productos.id_modelo')
            ->join('estados_vencimientos', 'estados_vencimientos.id', 'trazabilidad_productos.id_estado_vencimiento')
            ->when($rango, function ($query, string $rango) {
                $query->whereRaw('(EXTRACT(YEAR FROM trazabilidad_productos.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
               (EXTRACT(MONTH FROM trazabilidad_productos.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) in ' . $rango . '');
            })
            ->select(
                'trazabilidad_productos.*',
                'ubicacion',
                'trazabilidades.slug',
                DB::raw("marcas.nombre as marca"),
                DB::raw("productos.nombre"),
                DB::raw("p2.nombre||' - '||m2.nombre ||' - '||m.nombre  as dea"),
                DB::raw("modelos.nombre as modelo"),
                DB::raw("estados_vencimientos.nombre as estado"),
                DB::raw("estados_vencimientos.id as id_estado"),
                DB::raw("clientes.razon_social||' ('||clientes.nombre||') Equipo: ' ||p2.nombre||' - '||m2.nombre ||' - '||m.nombre  as cliente"),
                DB::raw("tipo_productos.nombre as tipo_producto"),
                DB::raw("to_char(trazabilidad_productos.vencimiento,'DD/MM/YYYY') as fecha "),
                DB::raw("case when  (EXTRACT(YEAR FROM trazabilidad_productos.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
       (EXTRACT(MONTH FROM trazabilidad_productos.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) between 4 and 6 then 'yellow'
                        when  (EXTRACT(YEAR FROM trazabilidad_productos.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
       (EXTRACT(MONTH FROM trazabilidad_productos.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) between 2 and 3 then 'orange'
                        when  (EXTRACT(YEAR FROM trazabilidad_productos.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
       (EXTRACT(MONTH FROM trazabilidad_productos.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) <= 1 then 'red;color:#fff' 
                        else 'green;color:#fff'
                    end as color")
            )->orderBy('trazabilidad_productos.vencimiento', 'DESC')->get();


        //dd(DB::getQueryLog());

        $mantenciones = DB::table('trazabilidad_mantenciones')
            ->join('trazabilidades', 'trazabilidades.id', 'trazabilidad_mantenciones.id_trazabilidad')
            ->join('clientes', 'clientes.id', 'trazabilidades.id_cliente')
            ->join('tipos_mantenciones', 'tipos_mantenciones.id', 'trazabilidad_mantenciones.id_tipo_mantencion')
            ->join('estados_mantenciones', 'estados_mantenciones.id', 'trazabilidad_mantenciones.id_estado_mantencion')
            ->when($rm, function ($query, string $rm) {
                $query->whereRaw('(EXTRACT(year FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(year FROM CURRENT_DATE)) * 12::numeric + (EXTRACT(month FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(month FROM CURRENT_DATE)) between ' . $rm . '');
            })
            ->select(
                'trazabilidad_mantenciones.*',
                'trazabilidades.slug',
                DB::raw("clientes.razon_social||' ('||clientes.nombre||')'  as cliente"),
                DB::raw("tipos_mantenciones.nombre as tipo"),
                DB::raw("estados_mantenciones.nombre as estado"),
                DB::raw("estados_mantenciones.id as id_estado"),
                DB::raw("to_char(trazabilidad_mantenciones.vencimiento,'DD/MM/YYYY') as fecha "),
                DB::raw("case when  (EXTRACT(YEAR FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
       (EXTRACT(MONTH FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) between 7 and 12 then 'orange'
                        when  (EXTRACT(YEAR FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
       (EXTRACT(MONTH FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) <= 6 then 'red;color:#fff' 
                        else 'green;color:#fff'
                    end as color")
            )->orderBy('trazabilidad_mantenciones.vencimiento', 'DESC')->get();
        $deas = Productos::join('marcas', 'marcas.id', 'productos.id_marca')
            ->join('modelos', 'modelos.id', 'productos.id_modelo')
            ->where('productos.estado', 1)
            ->where('id_tipo_producto', 1)
            ->orderBy('nombre', 'ASC')
            ->select('productos.id', DB::raw("productos.nombre||' - '||marcas.nombre|| ' - '||modelos.nombre as nombre"))
            ->get();
        $baterias =   Productos::where('estado', 1)->where('id_tipo_producto', 2)->orderBy('nombre', 'ASC')->get();
        $electrodo_adulto =   Productos::where('estado', 1)->where('id_tipo_producto', 3)->orderBy('nombre', 'ASC')->get();
        $electrodo_pediatrico =   Productos::where('estado', 1)->where('id_tipo_producto', 4)->orderBy('nombre', 'ASC')->get();
        $tipos_productos = TipoProductos::where('estado', 1)->where('id', '<>', 1)->orderBy('nombre', 'ASC')->get();
        $tipos_mantenciones = TiposMantenciones::where('estado', 1)->orderBy('nombre', 'ASC')->get();

        return view('trazabilidad.vencimientos', compact('vencimientos', 'mantenciones', 'tab', 'deas', 'baterias', 'electrodo_adulto', 'electrodo_pediatrico', 'tipos_productos', 'tipos_mantenciones'));
    }

    public function vencimientos_panel($id = null)
    {
        $rango = "";
        $rm = "";
        $tab = "vencimientos";
        if (isset($id)) {
            if (is_numeric($id)) {
                if ($id == 1) {
                    $rango = '(0, 1)';
                }
                if ($id == 3) {
                    $rango = '(2, 3)';
                }
                if ($id == 6) {
                    $rango = '(4, 5, 6)';
                }
            } else {
                if ($id == 'a') {
                    $rm = '7 and 12';
                }
                if ($id == 's') {
                    $rm = '0 and 6';
                }
                $tab = "mantenciones";
            }
        }

        //dd($rango);
        DB::enableQueryLog();
        $vencimientos = DB::table('trazabilidad_productos')
            ->join('trazabilidades', 'trazabilidades.id', 'trazabilidad_productos.id_trazabilidad')
            ->join('clientes', 'clientes.id', 'trazabilidades.id_cliente')
            ->join('productos', 'productos.id', 'trazabilidad_productos.id_producto')
            ->join('tipo_productos', 'tipo_productos.id', 'productos.id_tipo_producto')
            ->join('marcas', 'marcas.id', 'productos.id_marca')
            ->join('modelos', 'modelos.id', 'productos.id_modelo')
            ->join('estados_vencimientos', 'estados_vencimientos.id', 'trazabilidad_productos.id_estado_vencimiento')
            ->when($rango, function ($query, string $rango) {
                $query->whereRaw('(EXTRACT(YEAR FROM trazabilidad_productos.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
               (EXTRACT(MONTH FROM trazabilidad_productos.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) in ' . $rango . '');
            })
            ->select(
                'trazabilidad_productos.*',
                'ubicacion',
                'trazabilidades.slug',
                DB::raw("marcas.nombre as marca"),
                DB::raw("productos.nombre"),
                DB::raw("modelos.nombre as modelo"),
                DB::raw("estados_vencimientos.nombre as estado"),
                DB::raw("estados_vencimientos.id as id_estado"),
                DB::raw("clientes.razon_social||' ('||clientes.nombre||')'  as cliente"),
                DB::raw("tipo_productos.nombre as tipo_producto"),
                DB::raw("to_char(trazabilidad_productos.vencimiento,'DD/MM/YYYY') as fecha "),
                DB::raw("case when  (EXTRACT(YEAR FROM trazabilidad_productos.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
       (EXTRACT(MONTH FROM trazabilidad_productos.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) between 4 and 6 then 'yellow'
                        when  (EXTRACT(YEAR FROM trazabilidad_productos.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
       (EXTRACT(MONTH FROM trazabilidad_productos.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) between 2 and 3 then 'orange'
                        when  (EXTRACT(YEAR FROM trazabilidad_productos.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
       (EXTRACT(MONTH FROM trazabilidad_productos.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) <= 1 then 'red;color:#fff' 
                        else 'green;color:#fff'
                    end as color")
            )->orderBy('trazabilidad_productos.vencimiento', 'DESC')->get();


        //dd(DB::getQueryLog());

        $deas = Productos::join('marcas', 'marcas.id', 'productos.id_marca')
            ->join('modelos', 'modelos.id', 'productos.id_modelo')
            ->where('productos.estado', 1)
            ->where('id_tipo_producto', 1)
            ->orderBy('nombre', 'ASC')
            ->select('productos.id', DB::raw("productos.nombre||' - '||marcas.nombre|| ' - '||modelos.nombre as nombre"))
            ->get();
        $baterias =   Productos::where('estado', 1)->where('id_tipo_producto', 2)->orderBy('nombre', 'ASC')->get();
        $electrodo_adulto =   Productos::where('estado', 1)->where('id_tipo_producto', 3)->orderBy('nombre', 'ASC')->get();
        $electrodo_pediatrico =   Productos::where('estado', 1)->where('id_tipo_producto', 4)->orderBy('nombre', 'ASC')->get();
        $tipos_productos = TipoProductos::where('estado', 1)->where('id', '<>', 1)->orderBy('nombre', 'ASC')->get();
        $tipos_mantenciones = TiposMantenciones::where('estado', 1)->orderBy('nombre', 'ASC')->get();

        return view('trazabilidad.vencimientos_panel', compact('vencimientos', 'tab', 'deas', 'baterias', 'electrodo_adulto', 'electrodo_pediatrico', 'tipos_productos', 'tipos_mantenciones'));
    }
    public function mantenciones($id = null)
    {
        $rango = "";
        $rm = "";
        $tab = "vencimientos";
        if (isset($id)) {
            if (is_numeric($id)) {
                if ($id == 1) {
                    $rango = '(0, 1)';
                }
                if ($id == 3) {
                    $rango = '(2, 3)';
                }
                if ($id == 6) {
                    $rango = '(4, 5, 6)';
                }
            } else {
                if ($id == 'a') {
                    $rm = '7 and 12';
                }
                if ($id == 's') {
                    $rm = '0 and 6';
                }
                $tab = "mantenciones";
            }
        }

        //dd($rango);
        DB::enableQueryLog();

        $mantenciones = DB::table('trazabilidad_mantenciones')
            ->join('trazabilidades', 'trazabilidades.id', 'trazabilidad_mantenciones.id_trazabilidad')
            ->join('clientes', 'clientes.id', 'trazabilidades.id_cliente')
            ->join('tipos_mantenciones', 'tipos_mantenciones.id', 'trazabilidad_mantenciones.id_tipo_mantencion')
            ->join('estados_mantenciones', 'estados_mantenciones.id', 'trazabilidad_mantenciones.id_estado_mantencion')
            ->when($rm, function ($query, string $rm) {
                $query->whereRaw('(EXTRACT(year FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(year FROM CURRENT_DATE)) * 12::numeric + (EXTRACT(month FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(month FROM CURRENT_DATE)) between ' . $rm . '');
            })
            ->select(
                'trazabilidad_mantenciones.*',
                'trazabilidades.slug',
                DB::raw("clientes.razon_social||' ('||clientes.nombre||')'  as cliente"),
                DB::raw("tipos_mantenciones.nombre as tipo"),
                DB::raw("estados_mantenciones.nombre as estado"),
                DB::raw("estados_mantenciones.id as id_estado"),
                DB::raw("to_char(trazabilidad_mantenciones.vencimiento,'DD/MM/YYYY') as fecha "),
                DB::raw("case when  (EXTRACT(YEAR FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
       (EXTRACT(MONTH FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) between 7 and 12 then 'orange'
                        when  (EXTRACT(YEAR FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
       (EXTRACT(MONTH FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) <= 6 then 'red;color:#fff' 
                        else 'green;color:#fff'
                    end as color")
            )->orderBy('trazabilidad_mantenciones.vencimiento', 'DESC')->get();
        $deas = Productos::join('marcas', 'marcas.id', 'productos.id_marca')
            ->join('modelos', 'modelos.id', 'productos.id_modelo')
            ->where('productos.estado', 1)
            ->where('id_tipo_producto', 1)
            ->orderBy('nombre', 'ASC')
            ->select('productos.id', DB::raw("productos.nombre||' - '||marcas.nombre|| ' - '||modelos.nombre as nombre"))
            ->get();
        $baterias =   Productos::where('estado', 1)->where('id_tipo_producto', 2)->orderBy('nombre', 'ASC')->get();
        $electrodo_adulto =   Productos::where('estado', 1)->where('id_tipo_producto', 3)->orderBy('nombre', 'ASC')->get();
        $electrodo_pediatrico =   Productos::where('estado', 1)->where('id_tipo_producto', 4)->orderBy('nombre', 'ASC')->get();
        $tipos_productos = TipoProductos::where('estado', 1)->where('id', '<>', 1)->orderBy('nombre', 'ASC')->get();
        $tipos_mantenciones = TiposMantenciones::where('estado', 1)->orderBy('nombre', 'ASC')->get();

        return view('trazabilidad.mantenciones', compact('mantenciones', 'tab', 'deas', 'baterias', 'electrodo_adulto', 'electrodo_pediatrico', 'tipos_productos', 'tipos_mantenciones'));
    }
    public function clientes($slug = null)
    {

        $vencimientos = DB::table('trazabilidad_productos')
            ->join('trazabilidades', 'trazabilidades.id', 'trazabilidad_productos.id_trazabilidad')
            ->join('clientes', 'clientes.id', 'trazabilidades.id_cliente')
            ->join('productos', 'productos.id', 'trazabilidad_productos.id_producto')
            ->join('tipo_productos', 'tipo_productos.id', 'productos.id_tipo_producto')
            ->join('marcas', 'marcas.id', 'productos.id_marca')
            ->join('modelos', 'modelos.id', 'productos.id_modelo')
            ->join('estados_vencimientos', 'estados_vencimientos.id', 'trazabilidad_productos.id_estado_vencimiento')
            ->where('clientes.slug', $slug)
            ->select(
                'trazabilidad_productos.*',
                'ubicacion',
                'trazabilidades.slug',
                DB::raw("marcas.nombre as marca"),
                DB::raw("productos.nombre"),
                DB::raw("modelos.nombre as modelo"),
                DB::raw("estados_vencimientos.nombre as estado"),
                DB::raw("estados_vencimientos.id as id_estado"),
                DB::raw("clientes.razon_social||' ('||clientes.nombre||')'  as cliente"),
                DB::raw("tipo_productos.nombre as tipo_producto"),
                DB::raw("to_char(trazabilidad_productos.vencimiento,'DD/MM/YYYY') as fecha "),
                DB::raw("case when  (EXTRACT(YEAR FROM trazabilidad_productos.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
       (EXTRACT(MONTH FROM trazabilidad_productos.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) between 4 and 6 then 'yellow'
                        when  (EXTRACT(YEAR FROM trazabilidad_productos.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
       (EXTRACT(MONTH FROM trazabilidad_productos.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) between 2 and 3 then 'orange'
                        when  (EXTRACT(YEAR FROM trazabilidad_productos.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
       (EXTRACT(MONTH FROM trazabilidad_productos.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) <= 1 then 'red;color:#fff' 
                        else 'green;color:#fff'
                    end as color")
            )->orderBy('trazabilidad_productos.vencimiento', 'DESC')->get();

        //dd(DB::getQueryLog());

        $mantenciones = DB::table('trazabilidad_mantenciones')
            ->join('trazabilidades', 'trazabilidades.id', 'trazabilidad_mantenciones.id_trazabilidad')
            ->join('clientes', 'clientes.id', 'trazabilidades.id_cliente')
            ->join('tipos_mantenciones', 'tipos_mantenciones.id', 'trazabilidad_mantenciones.id_tipo_mantencion')
            ->join('estados_mantenciones', 'estados_mantenciones.id', 'trazabilidad_mantenciones.id_estado_mantencion')
            ->where('clientes.slug', $slug)
            ->select(
                'trazabilidad_mantenciones.*',
                'trazabilidades.slug',
                DB::raw("clientes.razon_social||' ('||clientes.nombre||')'  as cliente"),
                DB::raw("tipos_mantenciones.nombre as tipo"),
                DB::raw("estados_mantenciones.nombre as estado"),
                DB::raw("estados_mantenciones.id as id_estado"),
                DB::raw("to_char(trazabilidad_mantenciones.vencimiento,'DD/MM/YYYY') as fecha "),
                DB::raw("case when  (EXTRACT(YEAR FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
       (EXTRACT(MONTH FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) between 7 and 12 then 'orange'
                        when  (EXTRACT(YEAR FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
       (EXTRACT(MONTH FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) <= 6 then 'red;color:#fff' 
                        else 'green;color:#fff'
                    end as color")
            )->orderBy('trazabilidad_mantenciones.vencimiento', 'DESC')->get();


        return view('trazabilidad.vencimientos_cliente', compact('vencimientos', 'mantenciones'));
    }

    public function cambiar_estado($id, $estado)
    {
        $datos = DB::table('trazabilidad_productos')->where('id', $id)->get();
        if (count($datos) <= 0) {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->route('trazabilidad.vencimientos')->with('error', 'El registro no existe.');
        } else {
            $datos = DB::table('trazabilidad_productos')->where('id', $id)->get();
        }

        $actualizar = DB::table('trazabilidad_productos')->where('id', $id)->update(['id_estado_vencimiento' => $estado]);
        session()->flash('message', 'Registro modificado correctamente.');
        return redirect()->back();
    }

    public function cambiar_estado_mantencion($id, $estado)
    {
        $datos = DB::table('trazabilidad_mantenciones')->where('id', $id)->get();
        if (count($datos) <= 0) {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->route('trazabilidad_mantenciones.vencimientos')->with('error', 'El registro no existe.');
        } else {
            $datos = DB::table('trazabilidad_mantenciones')->where('id', $id)->get();
        }

        $actualizar = DB::table('trazabilidad_mantenciones')->where('id', $id)->update(['id_estado_mantencion' => $estado]);
        session()->flash('message', 'Registro modificado correctamente.');
        return redirect()->back();
    }

    public function obtener_vencimiento($id)
    {
        $dispositivos = DB::table('trazabilidad_productos')
            ->join('trazabilidades', 'trazabilidades.id', 'trazabilidad_productos.id_trazabilidad')
            ->join('productos', 'productos.id', 'trazabilidad_productos.id_producto')
            ->join('tipo_productos', 'tipo_productos.id', 'productos.id_tipo_producto')
            ->join('marcas', 'marcas.id', 'productos.id_marca')
            ->join('modelos', 'modelos.id', 'productos.id_modelo')
            ->select(
                'trazabilidad_productos.*',
                'productos.id_tipo_producto',
                DB::raw("marcas.nombre as marca"),
                DB::raw("modelos.nombre as modelo"),
                DB::raw("tipo_productos.nombre as tipo_producto")
            )
            ->where('trazabilidad_productos.id', $id)
            ->get();

        return $dispositivos;
    }

    public function obtener_mantencion($id)
    {
        $mantenciones = DB::table('trazabilidad_mantenciones')
            ->join('trazabilidades', 'trazabilidades.id', 'trazabilidad_mantenciones.id_trazabilidad')
            ->join('tipos_mantenciones', 'tipos_mantenciones.id', 'trazabilidad_mantenciones.id_tipo_mantencion')
            ->join('estados_mantenciones', 'estados_mantenciones.id', 'trazabilidad_mantenciones.id_estado_mantencion')
            ->select(
                'trazabilidad_mantenciones.*',
                DB::raw("tipos_mantenciones.nombre as tipo"),
                DB::raw("to_char(trazabilidad_mantenciones.vencimiento,'DD/MM/YYYY') as fecha ")
            )
            ->where('trazabilidad_mantenciones.id', $id)
            ->get();

        return $mantenciones;
    }


    public function pdf()
    {

        $productos = Productos::join('marcas', 'marcas.id', 'productos.id_marca')
            ->join('modelos', 'modelos.id', 'productos.id_modelo')
            ->join('tipo_productos', 'tipo_productos.id', 'productos.id_tipo_producto')
            ->select(
                'productos.*',
                DB::raw("marcas.nombre as marca"),
                DB::raw("modelos.nombre as modelo"),
                DB::raw("tipo_productos.nombre as tipo_producto"),
                DB::raw("'<img src=\"' || productos.ruta || '\" style=\"width:100px;\" />' as imagen")
            )
            ->get();

        $tMensaje = "<h3>Estimado/a </h3><p>Adjunto encontrará</p><br/>Saludos<br/></p>";

        $tAsunto = "Correo de prueba";
        $fechaActual = date('Ymd_His');
        $data["email"] = "paulolg@gmail.com";
        $data["title"] = $tAsunto;
        $data["body"] = $tMensaje;
        $data["fecha"] = $fechaActual;
        $data["id"] = 2;
        $data["productos"] = $productos;


        //dd($data);

        // Mail::send('pdf.test', $data, function ($message) use ($data, $pdf) {
        //     $message->to($data["email"], $data["email"])
        //         ->subject($data["title"])
        //         ->attachData($pdf->output(), 'docto_' . $data["fecha"] . '_' . $data["id"] . '.pdf');
        // });

        //return view('pdf.test', $data);
        $pdf = Pdf::loadView('pdf.test', $data);
        $pdf->setPaper('A4', 'portrait');

        // Renderiza el PDF para obtener el número total de páginas
        $pdf->render();

        // Ahora actualiza los números de página
        $canvas = $pdf->getDomPDF()->getCanvas();
        $canvas->page_text(520, 820, "Página {PAGE_NUM}/{PAGE_COUNT}", null, 10, array(0, 0, 0));
        return $pdf->stream();
    }
}
