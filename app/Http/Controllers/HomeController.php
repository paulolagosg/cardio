<?php

namespace App\Http\Controllers;

use App\Models\Regiones;
use App\Models\Rubros;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
