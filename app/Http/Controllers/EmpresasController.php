<?php

namespace App\Http\Controllers;

use App\Models\Bancos;
use App\Models\Empresas;
use App\Models\TiposCuentas;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class EmpresasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public  function index()
    {
        $datos = Empresas::join('bancos', 'bancos.id', 'empresas.id_banco')
            ->join('tipos_cuentas', 'tipos_cuentas.id', 'empresas.id_tipo_cuenta')
            ->select(
                'empresas.*',
                DB::raw("(bancos.nombre) as banco"),
                DB::raw("(tipos_cuentas.nombre) as cuenta"),
            )
            ->get();

        return view('empresas.lista', compact('datos'));
    }

    public function agregar(Request $request)
    {
        $bancos = Bancos::all();
        $tipos_cuenta = TiposCuentas::all();
        return view('empresas.agregar', compact('bancos', 'tipos_cuenta'));
    }

    public function agregar_guardar(Request $request)
    {
        //dd($request);

        $nExisteRut = Empresas::where('rut', $request->rut)->count();
        if ($nExisteRut > 0) {
            session()->flash('error', 'El RUT de la empresa ya existe.');
            return redirect()->to('/empresas/agregar')->withInput($request->input());
        }
        try {
            $rutaBase = '/app/public/';

            if (!file_exists($rutaBase . '/empresas/')) {
                Storage::makeDirectory($rutaBase . '/empresas/');
            }
            if ($request->file('logo') !== null) {
                DB::beginTransaction();
                $documento = $request->file('logo');
                $nombre_original = pathinfo($documento->getClientOriginalName(), PATHINFO_FILENAME);

                $path = $request->file('logo')->store('public/empresas');

                // Obtener la URL pública del archivo
                $url = Storage::url($path);
                if (Storage::exists($path)) {


                    $Empresas = new Empresas();
                    $Empresas->rut = $request->rut;
                    $Empresas->razon_social = $request->razon_social;
                    $Empresas->giro = $request->giro;
                    $Empresas->correo_electronico = $request->correo_electronico;
                    $Empresas->direccion = $request->direccion;
                    $Empresas->telefono = $request->telefono;
                    $Empresas->sitio_web = $request->sitio_web;
                    $Empresas->id_banco = $request->id_banco;
                    $Empresas->id_tipo_cuenta = $request->id_tipo_cuenta;
                    $Empresas->numero_cuenta = $request->numero_cuenta;
                    $Empresas->logo = $nombre_original;
                    $Empresas->ruta = $url;
                    $Empresas->save();


                    DB::commit();

                    session()->flash('message', 'Registro agregado correctamente.');
                    return redirect()->to('/empresas/lista/');
                }
            } else {
                $Empresas = new Empresas();
                $Empresas->rut = $request->rut;
                $Empresas->razon_social = $request->razon_social;
                $Empresas->giro = $request->giro;
                $Empresas->correo_electronico = $request->correo_electronico;
                $Empresas->direccion = $request->direccion;
                $Empresas->telefono = $request->telefono;
                $Empresas->sitio_web = $request->sitio_web;
                $Empresas->id_banco = $request->id_banco;
                $Empresas->id_tipo_cuenta = $request->id_tipo_cuenta;
                $Empresas->numero_cuenta = $request->numero_cuenta;
                $Empresas->save();


                DB::commit();

                session()->flash('message', 'Registro agregado correctamente.');
                return redirect()->to('/empresas/lista');
            }
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al agregar el registro.');
            return redirect()->to('/empresas/agregar');
        }
    }

    public function editar($id)
    {
        $datos = Empresas::where('id', $id)->get();
        if (count($datos) <= 0) {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->route('empresas.index')->with('error', 'El registro no existe.');
        } else {
            $datos = Empresas::where('id', $id)->first();
        }
        $bancos = Bancos::all();
        $tipos_cuenta = TiposCuentas::all();

        return view('empresas.editar', compact('datos', 'bancos', 'tipos_cuenta'));
    }

    public function actualizar(Request $request)
    {
        try {
            DB::beginTransaction();
            DB::enableQueryLog();
            $rutaBase = '/app/public/';
            $id = $request->id;
            if (!file_exists($rutaBase . '/empresas/')) {
                Storage::makeDirectory($rutaBase . '/empresas/');
            }
            if ($request->file('logo') !== null) {
                $documento = $request->file('logo');
                $nombre_original = pathinfo($documento->getClientOriginalName(), PATHINFO_FILENAME);

                $path = $request->file('logo')->store('public/empresas');

                // Obtener la URL pública del archivo
                $url = Storage::url($path);

                if (Storage::exists($path)) {
                    $actualizar = DB::table('empresas')
                        ->where('id', $request->id)
                        ->update([
                            'rut' => $request->rut,
                            'razon_social' => $request->razon_social,
                            'giro' => $request->giro,
                            'correo_electronico' => $request->correo_electronico,
                            'direccion' => $request->direccion,
                            'telefono' => $request->telefono,
                            'sitio_web' => $request->sitio_web,
                            'logo' => $nombre_original,
                            'id_banco' => $request->id_banco,
                            'id_tipo_cuenta' => $request->id_tipo_cuenta,
                            'numero_cuenta' => $request->numero_cuenta,
                            'ruta' => $url
                        ]);
                    DB::commit();

                    session()->flash('message', 'Registro modificado correctamente.');
                    return redirect()->to('/empresas/lista');
                } else {
                    dd("else");

                    DB::rollBack();
                    session()->flash('error', 'Error al cargar la imagen.');
                    return redirect()->back();
                }
                //dd(DB::getQueryLog());

            } else {
                $actualizar = DB::table('empresas')
                    ->where('id', $request->id)
                    ->update([
                        'rut' => $request->rut,
                        'razon_social' => $request->razon_social,
                        'giro' => $request->giro,
                        'correo_electronico' => $request->correo_electronico,
                        'direccion' => $request->direccion,
                        'telefono' => $request->telefono,
                        'sitio_web' => $request->sitio_web,
                        'id_banco' => $request->id_banco,
                        'id_tipo_cuenta' => $request->id_tipo_cuenta,
                        'numero_cuenta' => $request->numero_cuenta,
                    ]);
                //dd(DB::getQueryLog());
                DB::commit();

                session()->flash('message', 'Registro modificado correctamente.');
                return redirect()->to('/empresas/lista');
            }
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al modificar el registro.' . $e);
            return redirect()->back();
        }
    }



    public function eliminar($id)
    {
        $datos = Empresas::findOrFail($id);

        //$deleted = DB::table('Empresas')->where('id', $id)->delete();
        $actualizar = DB::table('empresas')
            ->where('id', $id)
            ->update([
                'estado' => 0
            ]);

        session()->flash('message', 'Registro eliminado correctamente.');
        return Redirect::back();
    }
}
