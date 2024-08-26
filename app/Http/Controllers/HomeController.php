<?php

namespace App\Http\Controllers;

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
            ->groupBy('regiones.codigo', 'regiones.orden')
            ->orderBy('regiones.orden', 'asc')
            ->select(
                'regiones.codigo as nombre_region',
                'regiones.orden as orden_region',
                DB::raw("count(clientes.id) as total")
            )
            ->get();
        $mantenciones = DB::table('vst_mantenciones')->first();
        $arreglo_regiones = [];
        $arreglo_cantidades = [];
        $arreglo_colores = [];

        foreach ($clientes_region as $c) {
            $arreglo_regiones[] = $c->nombre_region;
            $arreglo_cantidades[] = $c->total;
            $arreglo_colores[] = '#' . $this->random_color();
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

        // //1 mes de vencimiento
        // $vencimientos_correo = DB::table('trazabilidad_productos')
        //     ->join('trazabilidades', 'trazabilidades.id', 'trazabilidad_productos.id_trazabilidad')
        //     ->join('clientes', 'clientes.id', 'trazabilidades.id_cliente')
        //     ->join('productos', 'productos.id', 'trazabilidad_productos.id_producto')
        //     ->join('tipo_productos', 'tipo_productos.id', 'productos.id_tipo_producto')
        //     ->join('marcas', 'marcas.id', 'productos.id_marca')
        //     ->join('modelos', 'modelos.id', 'productos.id_modelo')
        //     ->join('estados_vencimientos', 'estados_vencimientos.id', 'trazabilidad_productos.id_estado_vencimiento')
        //     ->whereRaw('(EXTRACT(YEAR FROM trazabilidad_productos.vencimiento) - EXTRACT(YEAR FROM CURRENT_DATE)) * 12 +
        //        (EXTRACT(MONTH FROM trazabilidad_productos.vencimiento) - EXTRACT(MONTH FROM CURRENT_DATE)) = 1')
        //     ->select(
        //         'trazabilidad_productos.*',
        //         'ubicacion',
        //         'trazabilidades.slug',
        //         DB::raw("marcas.nombre as marca"),
        //         DB::raw("productos.nombre"),
        //         DB::raw("clientes.nombre as cliente"),
        //         DB::raw("clientes.correo"),
        //         DB::raw("modelos.nombre as modelo"),
        //         DB::raw("estados_vencimientos.nombre as estado"),
        //         DB::raw("estados_vencimientos.id as id_estado"),
        //         DB::raw("clientes.razon_social||' ('||clientes.nombre||')'  as cliente"),
        //         DB::raw("tipo_productos.nombre as tipo_producto"),
        //         DB::raw("to_char(trazabilidad_productos.vencimiento,'DD/MM/YYYY') as fecha "),
        //     )->orderBy('trazabilidad_productos.vencimiento', 'DESC')->get();

        // //enviar correo
        // foreach ($vencimientos_correo as $v) {
        //     $tMensaje = "<h3>Estimado/a " . $v->cliente . "</h3><p>Mediante el presente le informamos que el suministro <b>" . $v->nombre . " - " . $v->marca . " - " . $v->modelo . "</b> está a 1 mes de vencer.</p><br/>Saludos<br/></p>";

        //     $tAsunto = "Alerta vencimiento";
        //     $fechaActual = date('Ymd_His');
        //     $data["email"] = $v->correo;
        //     $data["title"] = $tAsunto;
        //     $data["body"] = $tMensaje;
        //     $data["fecha"] = $fechaActual;
        //     $data["id"] = 2;

        //     //dd($data);

        //     Mail::send('pdf.test', $data, function ($message) use ($data) {
        //         $message->to($data["email"], $data["email"])
        //             ->subject($data["title"]);
        //     });
        // }



        return view('home', compact(
            'vencimientos',
            'mantenciones',
            'arreglo_regiones',
            'arreglo_cantidades',
            'arreglo_colores',
            'clientes_region',
            'cr_nombres',
            'cr_cantidades',
            'cr_colores'
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
}
