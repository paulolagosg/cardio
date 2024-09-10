<?php

namespace App\Http\Controllers;

use App\Models\Cotizaciones;
use App\Models\CotizacionesProductos;
use App\Models\Empresas;
use App\Models\PlazosPagos;
use App\Models\Productos;
use App\Models\Regiones;
use App\Models\TiemposEntregas;
use App\Models\TiposPagos;
use App\Models\TiposTransportes;
use App\Models\User;
use App\Models\VencimientosCotizaciones;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Mail;


use Illuminate\Support\Facades\Storage;

class CotizacionesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public  function index()
    {
        $datos = Cotizaciones::join('comunas', 'comunas.id', 'cotizaciones.id_comuna')
            ->join('users', 'users.id', 'cotizaciones.id_usuario')
            ->join('estados_cotizaciones', 'estados_cotizaciones.id', 'cotizaciones.id_estado')
            ->select(
                'cotizaciones.*',
                DB::raw("to_char(cotizaciones.fecha,'DD/MM/YYYY hh24:mi:ss') as fecha"),
                DB::raw("(comunas.nombre) as comuna"),
                DB::raw("(users.name) as ejecutivo"),
                DB::raw("(estados_cotizaciones.nombre) as estado"),
            )
            ->get();

        return view('cotizaciones.lista', compact('datos'));
    }

    public function agregar(Request $request)
    {
        $regiones = Regiones::orderBy('orden', 'asc')->get();
        $vencimientos = VencimientosCotizaciones::orderBy('nombre', 'asc')->get();
        $tipos_transporte = TiposTransportes::orderBy('nombre', 'asc')->get();
        $formas_pago = TiposPagos::orderBy('nombre', 'asc')->get();
        $plazos_pago = PlazosPagos::orderBy('nombre', 'asc')->get();
        $tiempos_entrega = TiemposEntregas::orderBy('nombre', 'asc')->get();
        $usuarios = User::where('estado', 1)->orderBy('name', 'asc')->get();
        $empresas = Empresas::orderBy('razon_social', 'asc')->get();
        $productos = Productos::join('marcas', 'marcas.id', 'productos.id_marca')
            ->join('modelos', 'modelos.id', 'productos.id_modelo')
            ->where('productos.estado', 1)
            ->orderBy('nombre', 'ASC')
            ->select('productos.id', DB::raw("productos.nombre||' - '||marcas.nombre|| ' - '||modelos.nombre as nombre"))
            ->get();



        return view('cotizaciones.agregar', compact('regiones', 'vencimientos', 'tipos_transporte', 'formas_pago', 'plazos_pago', 'tiempos_entrega', 'usuarios', 'empresas', 'productos'));
    }

    public function agregar_guardar(Request $request)
    {

        $ejecutivo = $request->id_usuario;
        $empresa = $request->id_empresa;
        $solicitante = $request->solicitante;
        $correo_electronico = $request->correo_electronico;
        $razon_social = $request->razon_social;
        $rut = $request->rut;
        $giro = $request->giro;
        $telefono = $request->telefono;
        $direccion = $request->direccion;
        $id_region = $request->id_region;
        $id_comuna = $request->id_comuna;
        $id_vencimiento = $request->id_vencimiento;
        $id_tipo_transporte = $request->id_tipo_transporte;
        $id_tipo_pago = $request->id_tipo_pago;
        $id_plazo_pago = $request->id_plazo_pago;
        $id_tiempo_entrega = $request->id_tiempo_entrega;
        $observaciones = $request->observaciones;
        $costo_envio = $request->costo_envio;

        try {
            DB::beginTransaction();
            $cotizacion = new Cotizaciones();
            $cotizacion->id_usuario = $ejecutivo;
            $cotizacion->id_empresa = $empresa;
            $cotizacion->solicitante = $solicitante;
            $cotizacion->correo_electronico = $correo_electronico;
            $cotizacion->razon_social = $razon_social;
            $cotizacion->rut = $rut;
            $cotizacion->giro = $giro;
            $cotizacion->telefono = $telefono;
            $cotizacion->direccion = $direccion;
            $cotizacion->id_region = $id_region;
            $cotizacion->id_comuna = $id_comuna;
            $cotizacion->id_vencimiento = $id_vencimiento;
            $cotizacion->id_tipo_transporte = $id_tipo_transporte;
            $cotizacion->id_tipo_pago = $id_tipo_pago;
            $cotizacion->id_plazo_pago = $id_plazo_pago;
            $cotizacion->id_tiempo_entrega = $id_tiempo_entrega;
            $cotizacion->observaciones = $observaciones;
            $cotizacion->fecha = Carbon::now();
            $cotizacion->costo_envio = $costo_envio;
            $cotizacion->save();

            $productos = count($request->id_producto);
            for ($i = 0; $i < $productos; $i++) {
                $producto = new CotizacionesProductos();
                $producto->id_cotizacion = $cotizacion->id;
                $producto->id_producto = $request->id_producto[$i];
                $producto->precio = round($request->precio[$i]);
                $producto->cantidad = $request->cantidad[$i];
                $producto->descuento = $request->descuento[$i];
                $producto->descuento_pesos = round($request->descuento_pesos[$i]);
                $producto->unitario = $request->unitario[$i];
                $producto->subtotal = $request->subtotal[$i];
                $producto->save();
            }
            DB::commit();

            session()->flash('message', 'Registro agregado correctamente.');
            return redirect()->to('/cotizaciones/lista');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al agregar el registro. ' . $e);
            return redirect()->to('/cotizaciones/agregar')->withInput();
        }
    }

    public function editar($id)
    {
        $datos = Cotizaciones::where('id', $id)->get();
        if (count($datos) <= 0) {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->route('trazabilidad.vencimientos')->with('error', 'El registro no existe.');
        } else {
            $datos = Cotizaciones::where('id', $id)->first();
        }

        $regiones = Regiones::orderBy('orden', 'asc')->get();
        $vencimientos = VencimientosCotizaciones::orderBy('nombre', 'asc')->get();
        $tipos_transporte = TiposTransportes::orderBy('nombre', 'asc')->get();
        $formas_pago = TiposPagos::orderBy('nombre', 'asc')->get();
        $plazos_pago = PlazosPagos::orderBy('nombre', 'asc')->get();
        $tiempos_entrega = TiemposEntregas::orderBy('nombre', 'asc')->get();
        $usuarios = User::where('estado', 1)->orderBy('name', 'asc')->get();
        $empresas = Empresas::orderBy('razon_social', 'asc')->get();
        $productos = Productos::join('marcas', 'marcas.id', 'productos.id_marca')
            ->join('modelos', 'modelos.id', 'productos.id_modelo')
            ->where('productos.estado', 1)
            ->orderBy('nombre', 'ASC')
            ->select('productos.id', DB::raw("productos.nombre||' - '||marcas.nombre|| ' - '||modelos.nombre as nombre"))
            ->get();
        $productos_cotizacion = CotizacionesProductos::join('productos', 'productos.id', 'cotizaciones_productos.id_producto')
            ->join('marcas', 'marcas.id', 'productos.id_marca')
            ->join('modelos', 'modelos.id', 'productos.id_modelo')
            ->join('tipo_productos', 'tipo_productos.id', 'productos.id_tipo_producto')
            ->select(
                'cotizaciones_productos.*',
                'productos.*',
                DB::raw("marcas.nombre as marca"),
                DB::raw("modelos.nombre as modelo"),
                DB::raw("tipo_productos.nombre as tipo_producto"),
            )
            ->where('id_cotizacion', $id)
            ->get();



        return view('cotizaciones.editar', compact('datos', 'regiones', 'vencimientos', 'tipos_transporte', 'formas_pago', 'plazos_pago', 'tiempos_entrega', 'usuarios', 'empresas', 'productos', 'productos_cotizacion'));
    }

    public function actualizar(Request $request)
    {
        $id = $request->id;
        $ejecutivo = $request->id_usuario;
        $empresa = $request->id_empresa;
        $solicitante = $request->solicitante;
        $correo_electronico = $request->correo_electronico;
        $razon_social = $request->razon_social;
        $rut = $request->rut;
        $giro = $request->giro;
        $telefono = $request->telefono;
        $direccion = $request->direccion;
        $id_region = $request->id_region;
        $id_comuna = $request->id_comuna;
        $id_vencimiento = $request->id_vencimiento;
        $id_tipo_transporte = $request->id_tipo_transporte;
        $costo_envio = $request->costo_envio;
        if (!isset($request->costo_envio)) {
            $costo_envio = 0;
        }
        $id_tipo_pago = $request->id_tipo_pago;
        $id_plazo_pago = $request->id_plazo_pago;
        $id_tiempo_entrega = $request->id_tiempo_entrega;
        $observaciones = $request->observaciones;

        try {
            DB::beginTransaction();
            $cotizacion = DB::table('cotizaciones')
                ->where('id', $id)
                ->update([
                    'id_usuario' => $ejecutivo,
                    'id_empresa' => $empresa,
                    'solicitante' => $solicitante,
                    'correo_electronico' => $correo_electronico,
                    'razon_social' => $razon_social,
                    'rut' => $rut,
                    'telefono' => $telefono,
                    'direccion' => $direccion,
                    'giro' => $giro,
                    'id_region' => $id_region,
                    'id_comuna' => $id_comuna,
                    'id_vencimiento' => $id_vencimiento,
                    'id_tipo_transporte' => $id_tipo_transporte,
                    'id_tipo_pago' => $id_tipo_pago,
                    'id_plazo_pago' => $id_plazo_pago,
                    'id_tiempo_entrega' => $id_tiempo_entrega,
                    'observaciones' => $observaciones,
                    'fecha' => Carbon::now(),
                    'costo_envio' => $costo_envio
                ]);

            //elimino los productos
            $deleted = DB::table('cotizaciones_productos')->where('id_cotizacion', $id)->delete();

            $productos = count($request->id_producto);
            for ($i = 0; $i < $productos; $i++) {
                $producto = new CotizacionesProductos();
                $producto->id_cotizacion = $id;
                $producto->id_producto = $request->id_producto[$i];
                $producto->precio = round($request->precio[$i]);
                $producto->cantidad = $request->cantidad[$i];
                $producto->descuento = $request->descuento[$i];
                $producto->descuento_pesos = round($request->descuento_pesos[$i]);
                $producto->unitario = $request->unitario[$i];
                $producto->subtotal = $request->subtotal[$i];
                $producto->save();
            }
            DB::commit();

            session()->flash('message', 'Registro modificado correctamente.');
            return redirect()->to('/cotizaciones/lista');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al modificar el registro. ' . $e);
            return redirect()->to('/cotizaciones/editar/' . $id)->withInput();
        }
    }

    public function cotizacion_pdf($id)
    {
        $cotizacion = Cotizaciones::where('id', $id)->get();
        if (count($cotizacion) <= 0) {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->route('trazabilidad.vencimientos')->with('error', 'El registro no existe.');
        } else {
            $cotizacion = Cotizaciones::join('users', 'users.id', 'cotizaciones.id_usuario')
                ->join('empresas', 'empresas.id', 'cotizaciones.id_empresa')
                ->join('bancos', 'bancos.id', 'empresas.id_banco')
                ->join('tipos_cuentas', 'tipos_cuentas.id', 'empresas.id_tipo_cuenta')
                ->join('regiones', 'regiones.id', 'cotizaciones.id_region')
                ->join('comunas', 'comunas.id', 'cotizaciones.id_comuna')
                ->join('vencimientos_cotizaciones', 'vencimientos_cotizaciones.id', 'cotizaciones.id_vencimiento')
                ->join('tipos_transportes', 'tipos_transportes.id', 'cotizaciones.id_tipo_transporte')
                ->join('tipos_pagos', 'tipos_pagos.id', 'cotizaciones.id_tipo_pago')
                ->join('plazos_pagos', 'plazos_pagos.id', 'cotizaciones.id_plazo_pago')
                ->join('tiempos_entregas', 'tiempos_entregas.id', 'cotizaciones.id_tiempo_entrega')
                ->where('cotizaciones.id', $id)
                ->select(
                    'cotizaciones.*',
                    DB::raw("to_char(cotizaciones.fecha, 'dd/mm/yyyy hh24:mi:ss') as fecha"),
                    DB::raw("empresas.rut as empresa_rut"),
                    DB::raw("empresas.razon_social as empresa_rz"),
                    DB::raw("empresas.giro as empresa_giro"),
                    DB::raw("empresas.correo_electronico as empresa_correo"),
                    DB::raw("empresas.direccion as empresa_direccion"),
                    DB::raw("empresas.telefono as empresa_telefono"),
                    DB::raw("empresas.sitio_web as empresa_sitio_web"),
                    DB::raw("empresas.numero_cuenta as empresa_numero_cuenta"),
                    DB::raw("empresas.ruta as empresa_logo"),
                    DB::raw("bancos.nombre as empresa_banco"),
                    DB::raw("tipos_cuentas.nombre as empresa_tipo_cuenta"),
                    DB::raw("users.name as ejecutivo"),
                    DB::raw("users.email as ejecutivo_correo"),
                    DB::raw("users.rut as ejecutivo_rut"),
                    DB::raw("users.telefono as ejecutivo_telefono"),
                    DB::raw("users.ruta as ejecutivo_foto"),
                    DB::raw("empresas.razon_social as empresa"),
                    DB::raw("regiones.nombre as region"),
                    DB::raw("comunas.nombre as comuna"),
                    DB::raw("vencimientos_cotizaciones.nombre as vencimiento"),
                    DB::raw("tipos_transportes.nombre as transporte"),
                    DB::raw("tipos_pagos.nombre as tipo_pago"),
                    DB::raw("plazos_pagos.nombre as plazo_pago"),
                    DB::raw("tiempos_entregas.nombre as tiempo"),
                )
                ->first();
        }
        $productos = CotizacionesProductos::join('productos', 'productos.id', 'cotizaciones_productos.id_producto')
            ->join('marcas', 'marcas.id', 'productos.id_marca')
            ->join('modelos', 'modelos.id', 'productos.id_modelo')
            ->join('tipo_productos', 'tipo_productos.id', 'productos.id_tipo_producto')
            ->select(
                'cotizaciones_productos.*',
                'productos.*',
                DB::raw("marcas.nombre as marca"),
                DB::raw("modelos.nombre as modelo"),
                DB::raw("tipo_productos.nombre as tipo_producto"),
            )
            ->where('id_cotizacion', $id)
            ->get();


        $data["cotizacion"] = $cotizacion;
        $data["productos"] = $productos;

        $pdf = Pdf::loadView('pdf.cotizacion', $data);
        $pdf->setPaper('A4', 'portrait');

        $pdf->render();

        $canvas = $pdf->getDomPDF()->getCanvas();
        $canvas->page_text(520, 820, "Página {PAGE_NUM}/{PAGE_COUNT}", null, 10, array(0, 0, 0));
        return $pdf->stream();
    }

    public function cotizacion_enviar_pdf($id)
    {
        $cotizacion = Cotizaciones::where('id', $id)->get();
        if (count($cotizacion) <= 0) {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->route('trazabilidad.vencimientos')->with('error', 'El registro no existe.');
        }

        $cotizacion = Cotizaciones::join('users', 'users.id', 'cotizaciones.id_usuario')
            ->join('empresas', 'empresas.id', 'cotizaciones.id_empresa')
            ->join('bancos', 'bancos.id', 'empresas.id_banco')
            ->join('tipos_cuentas', 'tipos_cuentas.id', 'empresas.id_tipo_cuenta')
            ->join('regiones', 'regiones.id', 'cotizaciones.id_region')
            ->join('comunas', 'comunas.id', 'cotizaciones.id_comuna')
            ->join('vencimientos_cotizaciones', 'vencimientos_cotizaciones.id', 'cotizaciones.id_vencimiento')
            ->join('tipos_transportes', 'tipos_transportes.id', 'cotizaciones.id_tipo_transporte')
            ->join('tipos_pagos', 'tipos_pagos.id', 'cotizaciones.id_tipo_pago')
            ->join('plazos_pagos', 'plazos_pagos.id', 'cotizaciones.id_plazo_pago')
            ->join('tiempos_entregas', 'tiempos_entregas.id', 'cotizaciones.id_tiempo_entrega')
            ->where('cotizaciones.id', $id)
            ->select(
                'cotizaciones.*',
                DB::raw("cotizaciones.correo_electronico as correo_destino"),
                DB::raw("to_char(cotizaciones.fecha, 'dd/mm/yyyy hh24:mi:ss') as fecha"),
                DB::raw("empresas.rut as empresa_rut"),
                DB::raw("empresas.razon_social as empresa_rz"),
                DB::raw("empresas.giro as empresa_giro"),
                DB::raw("empresas.correo_electronico as empresa_correo"),
                DB::raw("empresas.direccion as empresa_direccion"),
                DB::raw("empresas.telefono as empresa_telefono"),
                DB::raw("empresas.sitio_web as empresa_sitio_web"),
                DB::raw("empresas.numero_cuenta as empresa_numero_cuenta"),
                DB::raw("empresas.ruta as empresa_logo"),
                DB::raw("bancos.nombre as empresa_banco"),
                DB::raw("tipos_cuentas.nombre as empresa_tipo_cuenta"),
                DB::raw("users.name as ejecutivo"),
                DB::raw("users.email as ejecutivo_correo"),
                DB::raw("users.rut as ejecutivo_rut"),
                DB::raw("users.telefono as ejecutivo_telefono"),
                DB::raw("users.ruta as ejecutivo_foto"),
                DB::raw("empresas.razon_social as empresa"),
                DB::raw("regiones.nombre as region"),
                DB::raw("comunas.nombre as comuna"),
                DB::raw("vencimientos_cotizaciones.nombre as vencimiento"),
                DB::raw("tipos_transportes.nombre as transporte"),
                DB::raw("tipos_pagos.nombre as tipo_pago"),
                DB::raw("plazos_pagos.nombre as plazo_pago"),
                DB::raw("tiempos_entregas.nombre as tiempo"),
            )
            ->first();

        $productos = CotizacionesProductos::join('productos', 'productos.id', 'cotizaciones_productos.id_producto')
            ->join('marcas', 'marcas.id', 'productos.id_marca')
            ->join('modelos', 'modelos.id', 'productos.id_modelo')
            ->join('tipo_productos', 'tipo_productos.id', 'productos.id_tipo_producto')
            ->select(
                'cotizaciones_productos.*',
                'productos.*',
                DB::raw("marcas.nombre as marca"),
                DB::raw("modelos.nombre as modelo"),
                DB::raw("tipo_productos.nombre as tipo_producto"),
            )
            ->where('id_cotizacion', $id)
            ->get();


        $data["cotizacion"] = $cotizacion;
        $data["productos"] = $productos;

        $tMensaje = "<h3>Estimado/a <b>" . $cotizacion->solicitante . "</b></h3><p>Adjunto encontrará cotización " . $cotizacion->id . " solcicitada.</p><p>Quedamos a la espera de cualquier duda o información requerida</p><br/>Saludos<br/>" . $cotizacion->empresa_rz . "</p>";
        $tNota = "<br><b>NOTA:</b> Este mensaje fue generado por un sistema de correo automático.<br>Este mensaje y/o documentos adjuntos son confidenciales y están destinados a la(s) persona(s) a la que han sido enviados. Pueden contener información privada y confidencial, cuya difusión se encuentre legalmente prohibida. Si usted no es el destinatario, por favor notifique de inmediato al remitente y elimine el mensaje de sus carpetas y/o archivos.";

        $tAsunto = "Envío Cotización";
        $fechaActual = date('Ymd_His');
        $data["email"] = $cotizacion->correo_destino;
        $data["title"] = $tAsunto;
        $data["body"] = $tMensaje . $tNota;
        $data["fecha"] = $fechaActual;

        //dd($data);

        $pdf = Pdf::loadView('pdf.cotizacion', $data);
        $pdf->setPaper('A4', 'portrait');

        $pdf->render();

        $canvas = $pdf->getDomPDF()->getCanvas();
        $canvas->page_text(520, 820, "Página {PAGE_NUM}/{PAGE_COUNT}", null, 10, array(0, 0, 0));

        Mail::send('pdf.enviar_cotizacion', $data, function ($message) use ($data, $pdf) {
            $message->to($data["email"], $data["email"])
                ->subject($data["title"])
                ->attachData($pdf->output(), 'cotizacion_' . $data["fecha"]  . '.pdf');
        });

        $cotizacion = DB::table('cotizaciones')
            ->where('id', $cotizacion->id)
            ->update([
                'id_estado' => 2
            ]);

        session()->flash('message', 'Cotización enviada correctamente.');
        return redirect()->to('/cotizaciones/lista');
    }

    public function cambiar_estado($id, $estado)
    {
        $datos = DB::table('cotizaciones')->where('id', $id)->get();
        if (count($datos) <= 0) {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->route('cotizaciones.index')->with('error', 'El registro no existe.');
        } else {
            $datos = DB::table('cotizaciones')->where('id', $id)->get();
        }

        $actualizar = DB::table('cotizaciones')->where('id', $id)->update(['id_estado' => $estado]);
        session()->flash('message', 'Registro modificado correctamente.');
        return redirect()->back();
    }
}
