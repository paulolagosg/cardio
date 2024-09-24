<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use App\Models\Comunas;
use App\Models\Regiones;
use App\Models\Rubros;
use Exception;
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
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

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
        try {
            DB::beginTransaction();
            $imagenPie = public_path('logo-interior.png');
            $imagePath = public_path('cabecera_correo.jpg');
            $tMensajeVencimientoBase = 'Estimado (a) <b>{nombre_cliente}</b>:<br>Junto con saludar, deseando que se encuentre muy bien, le informo que de acuerdo a registros de nuestro sistema interno de "Trazabilidad" usted posee:<br><br><b>{equipo_fecha}</b><br><br>Agradecemos confirmar si desea reposición de estos suministros a nuestro correo <a href="mailto:ventas@cardioprotegido.cl">ventas@cardioprotegido.cl</a> o bien nos puede contactar a nuestro call center <b>452 311 110</b> en donde uno de nuestros ejecutivos le atenderá de la mejor manera para cumplir con sus necesidades.<br><br><b>¡Siempre estaremos dispuestos a ayudar!</b>';
            $tPie = 'Cardioprotegido<br>Somos especialistas en Desfibriladores de acceso público.<br>www.cardioprotegido.cl | Call Center: 45 2 311 110 | clientes@cardioprotegido.cl<br>¡La oportunidad de salvar una vida está más cerca de lo que crees!';
            $tNota = '<span style="font-size:10px"><b>NOTA:</b> Si usted recibió este correo por error, favor informe al remitente, borre el correo y documentación asociada | Antes de imprimir este correo electrónico, piense bien si es necesario  hacerlo. <span style="color:red;">CARDIOPROTEGIDO</span> comprometido con el medio ambiente.</span><br><br>';
            $enviados = 0;
            /*** VENCIMIENTOS  ***/
            //1 mes de vencimiento
            DB::enableQueryLog();
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
            //dd(DB::getQueryLog());
            //enviar correo
            foreach ($vencimientos_correo as $v) {
                $tMensajeVencimiento = $tMensajeVencimientoBase;
                $tMensajeVencimiento = str_replace('{nombre_cliente}', $v->cliente, $tMensajeVencimiento);
                $tMensajeVencimiento = str_replace('{equipo_fecha}', $v->nombre . " - " . $v->marca . " - " . $v->modelo . " por vencer (" . $v->vencimiento . ")", $tMensajeVencimiento);
                //$datosDocumentos['folio']
                $tAsunto = "<img src='https://fonts.gstatic.com/s/e/notoemoji/15.1/1f389/72.png' /> Alerta de vencimiento suministros DEA <img src='https://fonts.gstatic.com/s/e/notoemoji/15.1/1f389/72.png' /> Vencimiento a 1 mes";
                $fechaActual = date('Ymd_His');
                $emails = [$v->correo];
                $data["email"] = $emails;
                $data["title"] = $tAsunto;
                $data["body"] = $tMensajeVencimiento;
                $data["fecha"] = $fechaActual;
                $data["nota"] = $tNota;
                $data["pie"] = $tPie;
                $data["imagePath"] = $imagePath;
                $data["imagenPie"] = $imagenPie;

                //dd($data);

                Mail::send('pdf.alerta', $data, function ($message) use ($data) {
                    $message->to($data["email"], $data["email"])
                        ->subject($data["title"]);
                });
                $actualizar = DB::table('trazabilidad_productos')->where('id', $v->id)->update(['correo_enviado' => 1]);
                $enviados++;
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
                $tMensajeVencimiento = $tMensajeVencimientoBase;
                $tMensajeVencimiento = str_replace('{nombre_cliente}', $v->cliente, $tMensajeVencimiento);
                $tMensajeVencimiento = str_replace('{equipo_fecha}', $v->nombre . " - " . $v->marca . " - " . $v->modelo . " por vencer (" . $v->vencimiento . ")", $tMensajeVencimiento);
                $tAsunto = "<img src='https://fonts.gstatic.com/s/e/notoemoji/15.1/1f389/72.png' /> Alerta de vencimiento suministros DEA <img src='https://fonts.gstatic.com/s/e/notoemoji/15.1/1f389/72.png' /> Vencimiento a 3 meses";
                $fechaActual = date('Ymd_His');
                $emails = [$v->correo];
                $data["email"] = $emails;
                $data["title"] = $tAsunto;
                $data["body"] = $tMensajeVencimiento;
                $data["fecha"] = $fechaActual;
                $data["nota"] = $tNota;
                $data["pie"] = $tPie;
                $data["imagePath"] = $imagePath;
                $data["imagenPie"] = $imagenPie;

                //dd($data);

                Mail::send('pdf.alerta', $data, function ($message) use ($data) {
                    $message->to($data["email"], $data["email"])
                        ->subject($data["title"]);
                });

                $actualizar = DB::table('trazabilidad_productos')->where('id', $v->id)->update(['correo_enviado' => 1]);
                $enviados++;
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
                $tMensajeVencimiento = $tMensajeVencimientoBase;
                $tMensajeVencimiento = str_replace('{nombre_cliente}', $v->cliente, $tMensajeVencimiento);
                $tMensajeVencimiento = str_replace('{equipo_fecha}', $v->nombre . " - " . $v->marca . " - " . $v->modelo . " por vencer (" . $v->vencimiento . ")", $tMensajeVencimiento);
                $tAsunto = "<img src='https://fonts.gstatic.com/s/e/notoemoji/15.1/1f389/72.png' /> Alerta de vencimiento suministros DEA <img src='https://fonts.gstatic.com/s/e/notoemoji/15.1/1f389/72.png' /> Vencimiento a 6 meses";
                $fechaActual = date('Ymd_His');
                $emails = [$v->correo];
                $data["email"] = $emails;
                $data["title"] = $tAsunto;
                $data["body"] = $tMensajeVencimiento;
                $data["fecha"] = $fechaActual;
                $data["nota"] = $tNota;
                $data["pie"] = $tPie;
                $data["imagePath"] = $imagePath;
                $data["imagenPie"] = $imagenPie;

                //dd($data);

                Mail::send('pdf.alerta', $data, function ($message) use ($data) {
                    $message->to($data["email"])
                        ->subject($data["title"]);
                });

                $actualizar = DB::table('trazabilidad_productos')->where('id', $v->id)->update(['correo_enviado' => 1]);
                $enviados++;
            }

            /*** MANTENCIONES ***/
            $tMensajeMantencionBase = 'Estimado (a) <b>{nombre_cliente}</b>:<br>Junto con saludar y deseando se encuentre muy bien. Tenemos el agrado de enviar a usted información importante a vuestro próximo servicio preventivo de "Inspección DEA":<br><br><b>Fecha de inspección: {equipo_fecha}</b><br><br>Si desea comunicarse con nuestro departamento técnico favor contactar a: <ol><li><a href="mailto:enrique.acevedo@cardioprotegido.cl">enrique.acevedo@cardioprotegido.cl</a></li><li>Call center <b>452 311 110</b></li></ol>.<br><br><b>¡Siempre estaremos dispuestos a ayudar!</b>';
            $tNota = '<br><br><span style="font-size:10px"><b>NOTA:</b> Si usted recibió este correo por error, favor informe al remitente, borre el correo y documentación asociada | Antes de imprimir este correo electrónico, piense bien si es necesario  hacerlo. <span style="color:red;">CARDIOPROTEGIDO</span> comprometido con el medio ambiente.</span>';

            //1 año de vencimiento
            DB::enableQueryLog();
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
            //dd(DB::getQueryLog());
            //enviar correo
            foreach ($mantenciones_correo as $v) {
                $tMensajeMantencion = $tMensajeMantencionBase;
                $tMensajeMantencion = str_replace('{nombre_cliente}', $v->cliente, $tMensajeMantencion);
                $tMensajeMantencion = str_replace('{equipo_fecha}', $v->vencimiento, $tMensajeMantencion);


                $tAsunto = "Próximo servicio de Inspección a su Desfibrilador";
                $fechaActual = date('Ymd_His');
                $emails = [$v->correo];
                $data["email"] = $emails;
                $data["title"] = $tAsunto;
                $data["body"] = $tMensajeMantencion;
                $data["fecha"] = $fechaActual;
                $data["pie"] = $tPie;
                $data["nota"] = $tNota;
                $data["imagePath"] = $imagePath;
                $data["imagenPie"] = $imagenPie;

                //dd($data);

                Mail::send('pdf.alerta', $data, function ($message) use ($data) {
                    $message->to($data["email"], $data["email"])
                        ->subject($data["title"]);
                });
                $actualizar = DB::table('trazabilidad_mantenciones')->where('id', $v->id)->update(['correo_enviado' => 1]);
                $enviados++;
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
                    DB::raw("to_char(trazabilidad_mantenciones.vencimiento,'DD/MM/YYYY') as fecha "),
                    DB::raw("clientes.nombre as cliente"),
                    DB::raw("clientes.correo"),
                    DB::raw("clientes.razon_social   as cliente")
                )->orderBy('trazabilidad_mantenciones.vencimiento', 'DESC')->get();

            //enviar correo
            foreach ($mantenciones_correo as $v) {

                $tMensajeMantencion = $tMensajeMantencionBase;
                $tMensajeMantencion = str_replace('{nombre_cliente}', $v->cliente, $tMensajeMantencion);
                $tMensajeMantencion = str_replace('{equipo_fecha}', $v->vencimiento, $tMensajeMantencion);


                $tAsunto = "Próximo servicio de Inspección a su Desfibrilador";
                $fechaActual = date('Ymd_His');
                $emails = [$v->correo];
                $data["email"] = $emails;
                $data["title"] = $tAsunto;
                $data["body"] = $tMensajeMantencion;
                $data["fecha"] = $fechaActual;
                $data["pie"] = $tPie;
                $data["nota"] = $tNota;
                $data["imagePath"] = $imagePath;
                $data["imagenPie"] = $imagenPie;

                //dd($data);

                Mail::send('pdf.alerta', $data, function ($message) use ($data) {
                    $message->to($data["email"], $data["email"])
                        ->subject($data["title"]);
                });

                $actualizar = DB::table('trazabilidad_mantenciones')->where('id', $v->id)->update(['correo_enviado' => 1]);
                $enviados++;
            }
            DB::commit();
            echo "Proceso terminado. Enviados: " . $enviados;
        } catch (Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
        }
    }
}
