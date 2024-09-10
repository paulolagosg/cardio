<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use App\Models\Comunas;
use App\Models\Regiones;
use App\Models\Rubros;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $vencimientos = DB::table('vst_vencimientos')->first();
        $clientes_region = Regiones::leftJoin('clientes', 'regiones.id', 'clientes.id_region')
            ->groupBy('regiones.codigo', 'regiones.orden', 'regiones.nombre')
            ->orderBy('regiones.orden', 'asc')
            ->select(
                'regiones.codigo as codigo',
                DB::raw("initcap(regiones.nombre) as nombre_region"),
                'regiones.orden as orden_region',
                DB::raw("count(clientes.id) as total")
            )
            ->get();
        $mantenciones = DB::table('vst_mantenciones')->first();
        $arreglo_regiones = [];
        $arreglo_cantidades = [];
        $arreglo_colores = [];
        $arreglo_codigos = [];

        foreach ($clientes_region as $c) {
            $arreglo_regiones[] = $c->nombre_region;
            $arreglo_cantidades[] = $c->total;
            $arreglo_colores[] = '#' . $this->random_color();
            $arreglo_codigos[] = $c->codigo;
        }

        $clientes_rubro = Rubros::leftJoin('clientes', 'rubros.id', 'clientes.id_rubro')
            ->groupBy('rubros.nombre')
            ->orderBy('rubros.nombre', 'asc')
            ->select(
                'rubros.nombre as nombre',
                DB::raw("count(clientes.id) as total")
            )
            ->get();
        $cr_nombres = [];
        $cr_cantidades = [];
        $cr_colores = [];

        foreach ($clientes_rubro as $c) {
            $cr_nombres[] = $c->nombre;
            $cr_cantidades[] = $c->total;
            $cr_colores[] = '#' . $this->random_color();
        }
        $marcadores = [];
        $info_mapa = Clientes::get();

        foreach ($info_mapa as $i) {
            if ($i->latitud != "") {
                $marcadores[] = array('lat' => $i->latitud, 'long' => $i->longitud, 'title' => $i->nombre);
            }
        }

        $mapa = $marcadores;

        $clientes_regiones_tabla = DB::table('regiones')
            ->leftJoin('clientes', 'regiones.id', 'clientes.id_region')
            ->groupBy('regiones.nombre')
            ->groupBy('regiones.orden')
            ->groupBy('regiones.codigo')
            ->orderBy('regiones.orden', 'ASC')
            ->select(DB::raw("count(clientes.id) as total"), DB::raw("initcap(regiones.nombre) as nombre"), 'regiones.orden', 'regiones.codigo')
            ->get();

        $clientes_rubros_tabla = DB::table('rubros')
            ->leftJoin('clientes', 'rubros.id', 'clientes.id_rubro')
            ->groupBy('rubros.nombre')
            ->orderBy('rubros.nombre', 'ASC')
            ->select(DB::raw("count(clientes.id) as total"), 'rubros.nombre')
            ->get();

        return view('home', compact(
            'vencimientos',
            'mantenciones',
            'arreglo_regiones',
            'arreglo_cantidades',
            'arreglo_colores',
            'clientes_region',
            'cr_nombres',
            'cr_cantidades',
            'cr_colores',
            'mapa',
            'clientes_regiones_tabla',
            'clientes_rubros_tabla'
        ));
    }



    public function random_color_part()
    {
        return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
    }

    public function random_color()
    {
        return $this->random_color_part() . $this->random_color_part() . $this->random_color_part();
    }

    public function enviar_correo()
    {
        $tNota = "<br><b>NOTA:</b> Este mensaje fue generado por un sistema de correo automático.<br>Este mensaje y/o documentos adjuntos son confidenciales y están destinados a la(s) persona(s) a la que han sido enviados. Pueden contener información privada y confidencial, cuya difusión se encuentre legalmente prohibida. Si usted no es el destinatario, por favor notifique de inmediato al remitente y elimine el mensaje de sus carpetas y/o archivos.";

        /*** VENCIMIENTOS  ***/
        //1 mes de vencimiento
        $vencimientos_correo = DB::table('trazabilidad_productos')
            ->join('trazabilidades', 'trazabilidades.id', 'trazabilidad_productos.id_trazabilidad')
            ->join('clientes', 'clientes.id', 'trazabilidades.id_cliente')
            ->join('productos', 'productos.id', 'trazabilidad_productos.id_producto')
            ->join('tipo_productos', 'tipo_productos.id', 'productos.id_tipo_producto')
            ->join('marcas', 'marcas.id', 'productos.id_marca')
            ->join('modelos', 'modelos.id', 'productos.id_modelo')
            ->join('estados_vencimientos', 'estados_vencimientos.id', 'trazabilidad_productos.id_estado_vencimiento')
            ->whereRaw('(EXTRACT(YEAR FROM trazabilidad_productos.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
               (EXTRACT(MONTH FROM trazabilidad_productos.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) <= 1')
            ->where('trazabilidad_productos.id_estado_vencimiento', 1)
            ->where('trazabilidad_productos.correo_enviado', 0)
            ->select(
                'trazabilidad_productos.*',
                DB::raw("marcas.nombre as marca"),
                DB::raw("productos.nombre"),
                DB::raw("clientes.nombre as cliente"),
                DB::raw("clientes.correo"),
                DB::raw("modelos.nombre as modelo"),
                DB::raw("clientes.razon_social   as cliente")
            )->orderBy('trazabilidad_productos.vencimiento', 'DESC')->get();

        //enviar correo
        foreach ($vencimientos_correo as $v) {
            $tMensaje = "<h3>Estimado/a " . $v->cliente . "</h3><p>Mediante el presente le informamos que el suministro <b>" . $v->nombre . " - " . $v->marca . " - " . $v->modelo . "</b> está a 1 mes de vencer.</p><br/>Saludos<br/></p>";

            $tAsunto = "Alerta vencimiento";
            $fechaActual = date('Ymd_His');
            $emails = [$v->correo, 'paulo.lagos@tide.cl'];
            $data["email"] = $emails;
            $data["title"] = $tAsunto;
            $data["body"] = $tMensaje . $tNota;
            $data["fecha"] = $fechaActual;
            $data["id"] = 2;

            //dd($data);

            Mail::send('pdf.alerta', $data, function ($message) use ($data) {
                $message->to($data["email"], $data["email"])
                    ->subject($data["title"]);
            });
            $actualizar = DB::table('trazabilidad_productos')->where('id', $v->id)->update(['correo_enviado' => 1]);
        }

        //3 meses de vencimiento
        $vencimientos_correo = DB::table('trazabilidad_productos')
            ->join('trazabilidades', 'trazabilidades.id', 'trazabilidad_productos.id_trazabilidad')
            ->join('clientes', 'clientes.id', 'trazabilidades.id_cliente')
            ->join('productos', 'productos.id', 'trazabilidad_productos.id_producto')
            ->join('tipo_productos', 'tipo_productos.id', 'productos.id_tipo_producto')
            ->join('marcas', 'marcas.id', 'productos.id_marca')
            ->join('modelos', 'modelos.id', 'productos.id_modelo')
            ->join('estados_vencimientos', 'estados_vencimientos.id', 'trazabilidad_productos.id_estado_vencimiento')
            ->whereRaw('(EXTRACT(YEAR FROM trazabilidad_productos.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
               (EXTRACT(MONTH FROM trazabilidad_productos.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) between 2 and 3')
            ->where('trazabilidad_productos.id_estado_vencimiento', 1)
            ->where('trazabilidad_productos.correo_enviado', 0)
            ->select(
                'trazabilidad_productos.*',
                DB::raw("marcas.nombre as marca"),
                DB::raw("productos.nombre"),
                DB::raw("clientes.nombre as cliente"),
                DB::raw("clientes.correo"),
                DB::raw("modelos.nombre as modelo"),
                DB::raw("clientes.razon_social as cliente")
            )->orderBy('trazabilidad_productos.vencimiento', 'DESC')->get();

        //enviar correo
        foreach ($vencimientos_correo as $v) {
            $tMensaje = "<h3>Estimado/a " . $v->cliente . "</h3><p>Mediante el presente le informamos que el suministro <b>" . $v->nombre . " - " . $v->marca . " - " . $v->modelo . "</b> está a 3 mes de vencer.</p><br/>Saludos<br/></p>";

            $tAsunto = "Alerta vencimiento";
            $fechaActual = date('Ymd_His');
            $emails = [$v->correo, 'paulo.lagos@tide.cl'];
            $data["email"] = $emails;
            $data["title"] = $tAsunto;
            $data["body"] = $tMensaje . $tNota;
            $data["fecha"] = $fechaActual;
            $data["id"] = 2;

            //dd($data);

            Mail::send('pdf.alerta', $data, function ($message) use ($data) {
                $message->to($data["email"], $data["email"])
                    ->subject($data["title"]);
            });

            $actualizar = DB::table('trazabilidad_productos')->where('id', $v->id)->update(['correo_enviado' => 1]);
        }


        //6 meses de vencimiento
        $vencimientos_correo = DB::table('trazabilidad_productos')
            ->join('trazabilidades', 'trazabilidades.id', 'trazabilidad_productos.id_trazabilidad')
            ->join('clientes', 'clientes.id', 'trazabilidades.id_cliente')
            ->join('productos', 'productos.id', 'trazabilidad_productos.id_producto')
            ->join('tipo_productos', 'tipo_productos.id', 'productos.id_tipo_producto')
            ->join('marcas', 'marcas.id', 'productos.id_marca')
            ->join('modelos', 'modelos.id', 'productos.id_modelo')
            ->join('estados_vencimientos', 'estados_vencimientos.id', 'trazabilidad_productos.id_estado_vencimiento')
            ->whereRaw('(EXTRACT(YEAR FROM trazabilidad_productos.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
               (EXTRACT(MONTH FROM trazabilidad_productos.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) between 4 and 6')
            ->where('trazabilidad_productos.id_estado_vencimiento', 1)
            ->where('trazabilidad_productos.correo_enviado', 0)
            ->select(
                'trazabilidad_productos.*',
                DB::raw("marcas.nombre as marca"),
                DB::raw("productos.nombre"),
                DB::raw("clientes.nombre as cliente"),
                DB::raw("clientes.correo"),
                DB::raw("modelos.nombre as modelo"),
                DB::raw("clientes.razon_social as cliente")
            )->orderBy('trazabilidad_productos.vencimiento', 'DESC')->get();

        //enviar correo
        foreach ($vencimientos_correo as $v) {
            $tMensaje = "<h3>Estimado/a " . $v->cliente . "</h3><p>Mediante el presente le informamos que el suministro <b>" . $v->nombre . " - " . $v->marca . " - " . $v->modelo . "</b> está a 6 mes de vencer.</p><br/>Saludos<br/></p>";

            $tAsunto = "Alerta vencimiento";
            $fechaActual = date('Ymd_His');
            $emails = [$v->correo, 'paulo.lagos@tide.cl'];
            $data["email"] = $emails;
            $data["title"] = $tAsunto;
            $data["body"] = $tMensaje . $tNota;
            $data["fecha"] = $fechaActual;
            $data["id"] = 2;

            //dd($data);

            Mail::send('pdf.alerta', $data, function ($message) use ($data) {
                $message->to($data["email"])
                    ->subject($data["title"]);
            });

            $actualizar = DB::table('trazabilidad_productos')->where('id', $v->id)->update(['correo_enviado' => 1]);
        }

        /*** MANTENCIONES ***/
        //1 año de vencimiento
        $mantenciones_correo = DB::table('trazabilidad_mantenciones')
            ->join('trazabilidades', 'trazabilidades.id', 'trazabilidad_mantenciones.id_trazabilidad')
            ->join('clientes', 'clientes.id', 'trazabilidades.id_cliente')
            ->join('estados_mantenciones', 'estados_mantenciones.id', 'trazabilidad_mantenciones.id_estado_mantencion')
            ->whereRaw('(EXTRACT(YEAR FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
               (EXTRACT(MONTH FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) between 7 and 12')
            ->where('trazabilidad_mantenciones.id_estado_mantencion', 1)
            ->where('trazabilidad_mantenciones.correo_enviado', 0)
            ->select(
                'trazabilidad_mantenciones.*',
                DB::raw("clientes.nombre as cliente"),
                DB::raw("clientes.correo"),
                DB::raw("clientes.razon_social   as cliente")
            )->orderBy('trazabilidad_mantenciones.vencimiento', 'DESC')->get();

        //enviar correo
        foreach ($mantenciones_correo as $v) {
            $tMensaje = "<h3>Estimado/a " . $v->cliente . "</h3><p>Mediante el presente le informamos que realizaremos la mantención de sus equipos en <b>1 año</b>.</p><br/>Saludos<br/></p>";

            $tAsunto = "Alerta mantención preventiva";
            $fechaActual = date('Ymd_His');
            $emails = [$v->correo, 'paulo.lagos@tide.cl'];
            $data["email"] = $emails;
            $data["title"] = $tAsunto;
            $data["body"] = $tMensaje . $tNota;
            $data["fecha"] = $fechaActual;
            $data["id"] = 2;

            //dd($data);

            Mail::send('pdf.alerta', $data, function ($message) use ($data) {
                $message->to($data["email"], $data["email"])
                    ->subject($data["title"]);
            });
            $actualizar = DB::table('trazabilidad_mantenciones')->where('id', $v->id)->update(['correo_enviado' => 1]);
        }

        //6 meses de vencimiento
        $mantenciones_correo = DB::table('trazabilidad_mantenciones')
            ->join('trazabilidades', 'trazabilidades.id', 'trazabilidad_mantenciones.id_trazabilidad')
            ->join('clientes', 'clientes.id', 'trazabilidades.id_cliente')
            ->join('estados_mantenciones', 'estados_mantenciones.id', 'trazabilidad_mantenciones.id_estado_mantencion')
            ->whereRaw('(EXTRACT(YEAR FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
               (EXTRACT(MONTH FROM trazabilidad_mantenciones.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) <= 6')
            ->where('trazabilidad_mantenciones.id_estado_mantencion', 1)
            ->where('trazabilidad_mantenciones.correo_enviado', 0)
            ->select(
                'trazabilidad_mantenciones.*',
                DB::raw("clientes.nombre as cliente"),
                DB::raw("clientes.correo"),
                DB::raw("clientes.razon_social   as cliente")
            )->orderBy('trazabilidad_mantenciones.vencimiento', 'DESC')->get();

        //enviar correo
        foreach ($mantenciones_correo as $v) {
            $tMensaje = "<h3>Estimado/a " . $v->cliente . "</h3><p>Mediante el presente le informamos que realizaremos la mantención de sus equipos en <b>6 meses</b>.</p><br/>Saludos<br/></p>";

            $tAsunto = "Alerta mantención preventiva";
            $fechaActual = date('Ymd_His');
            $emails = [$v->correo, 'paulo.lagos@tide.cl'];
            $data["email"] = $emails;
            $data["title"] = $tAsunto;
            $data["body"] = $tMensaje . $tNota;
            $data["fecha"] = $fechaActual;
            $data["id"] = 2;

            //dd($data);

            Mail::send('pdf.alerta', $data, function ($message) use ($data) {
                $message->to($data["email"], $data["email"])
                    ->subject($data["title"]);
            });

            $actualizar = DB::table('trazabilidad_mantenciones')->where('id', $v->id)->update(['correo_enviado' => 1]);
        }
    }
}
