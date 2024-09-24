<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use App\Models\Cursos;
use App\Models\Empresas;
use App\Models\Modalidades;
use App\Models\User;
use App\Models\Versiones;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class VersionesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public  function index()
    {
        $datos = Versiones::join('cursos', 'cursos.id', 'versiones.id_curso')
            ->join('modalidades', 'modalidades.id', 'versiones.id_modalidad')
            ->join('users as ui', 'ui.id', 'versiones.id_usuario_instructor')
            ->join('users as uf', 'uf.id', 'versiones.id_usuario_firmante')
            ->select(
                'versiones.*',
                DB::raw("cursos.nombre as curso"),
                DB::raw("modalidades.nombre as modalidad"),
                DB::raw("ui.name as instructor"),
                DB::raw("versiones.fecha_version as fecha"),
                DB::raw("uf.name as firmante"),
            )
            ->get();
        $modalidades = Modalidades::where('estado', 1)->orderBy('id', 'asc')->get();
        $usuarios = User::where('estado', 1)->orderBy('id', 'asc')->get();
        $clientes = Clientes::where('estado', 1)->orderBy('id', 'asc')->get();
        $cursos = Cursos::where('estado', 1)->orderBy('id', 'asc')->get();

        return view('versiones.lista', compact('datos', 'modalidades', 'usuarios', 'clientes', 'cursos'));
    }
    public  function agregar()
    {
        $cursos = Cursos::where('estado', 1)->get();
        $modalidades = Modalidades::where('estado', 1)->orderBy('id', 'asc')->get();
        $usuarios = User::where('estado', 1)->orderBy('id', 'asc')->get();
        $clientes = Clientes::where('estado', 1)->orderBy('nombre', 'asc')->get();
        $empresas = Empresas::orderBy('razon_social', 'asc')->get();
        return view('versiones.agregar', compact('cursos', 'modalidades', 'usuarios', 'clientes', 'empresas'));
    }
    public  function agregar_guardar(Request $request)
    {
        //dd($request);

        try {
            DB::beginTransaction();
            $aleatoreo = rand(100, 999);
            if ($request->file('firma') != null) {

                $rutaBase = '/app/public/';

                if (!file_exists($rutaBase . '/firmas/')) {
                    Storage::makeDirectory($rutaBase . '/firmas/');
                }
                $documento = $request->file('firma');
                $nombre_original = pathinfo($documento->getClientOriginalName(), PATHINFO_FILENAME);

                $path = $request->file('firma')->store('public/firmas');


                $url = Storage::url($path);
                if (Storage::exists($path)) {

                    $versiones = new Versiones();
                    $versiones->nombre = $request->nombre;
                    $versiones->id_cliente = $request->id_cliente;
                    $versiones->id_curso = $request->id_curso;
                    $versiones->id_modalidad = $request->id_modalidad;
                    $versiones->id_empresa = $request->id_empresa;
                    $versiones->fecha_version = $request->fecha_curso;
                    $versiones->id_usuario_instructor = $request->id_usuario_instructor;
                    $versiones->id_usuario_firmante = $request->id_usuario_firmante;
                    $versiones->horas = $request->horas;
                    $versiones->contraparte = $request->contraparte;
                    $versiones->rut = $request->rut;
                    $versiones->telefono = $request->telefono;
                    $versiones->correo_electronico = $request->correo_electronico;
                    $versiones->firma = $nombre_original;
                    $versiones->ruta = $url;
                    $versiones->ciudad = $request->ciudad;
                    $versiones->fecha_certificado = Carbon::now();
                    $versiones->slug = str_slug($request->nombre . "-" . $aleatoreo, '-');
                    $versiones->estado = 1;
                    $versiones->save();

                    DB::commit();

                    session()->flash('message', 'Registro agregado correctamente.');
                    return redirect()->to('/versiones/lista');
                } else {
                    session()->flash('error', 'Ocurrió un error al subir la firma, favor intente nuevamente.');
                    return redirect()->back();
                }
            } else {
                $versiones = new Versiones();
                $versiones->nombre = $request->nombre;
                $versiones->id_cliente = $request->id_cliente;
                $versiones->id_curso = $request->id_curso;
                $versiones->id_modalidad = $request->id_modalidad;
                $versiones->id_empresa = $request->id_empresa;
                $versiones->fecha_version = $request->fecha_curso;
                $versiones->id_usuario_instructor = $request->id_usuario_instructor;
                $versiones->id_usuario_firmante = $request->id_usuario_firmante;
                $versiones->horas = $request->horas;
                $versiones->contraparte = $request->contraparte;
                $versiones->rut = $request->rut;
                $versiones->telefono = $request->telefono;
                $versiones->correo_electronico = $request->correo_electronico;
                $versiones->ciudad = $request->ciudad;
                $versiones->fecha_certificado = Carbon::now();
                $versiones->firma = ' ';
                $versiones->ruta = ' ';
                $versiones->slug = str_slug($request->nombre . "-" . $aleatoreio, '-');
                $versiones->estado = 1;
                $versiones->save();

                DB::commit();
                session()->flash('message', 'Registro agregado correctamente.');
                return redirect()->to('/versiones/lista');
            }
        } catch (Exception $e) {
            db::rollBack();
            return redirect()->back()->with('error', 'Error al agregar => ' . $e)->withInput();
        }


        return view('versiones.agregar', compact('datos'));
    }

    public  function editar($id)
    {

        $datos = Versiones::where('slug', $id)->get();
        if (count($datos) <= 0) {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->route('productos.index')->with('error', 'El registro no existe.');
        } else {
            $datos = Versiones::where('slug', $id)->first();
        }
        $cursos = Cursos::where('estado', 1)->get();
        $modalidades = Modalidades::where('estado', 1)->get();
        $usuarios = User::where('estado', 1)->get();
        $clientes = Clientes::where('estado', 1)->get();
        $empresas = Empresas::orderBy('razon_social', 'asc')->get();

        return view('versiones.editar', compact('datos', 'cursos', 'modalidades', 'usuarios', 'clientes', 'empresas'));
    }
    public  function actualizar(Request $request)
    {
        //dd($request);
        $id = $request->id;
        try {
            DB::beginTransaction();
            if ($request->file('firma') != null) {

                $rutaBase = '/app/public/';

                if (!file_exists($rutaBase . '/firmas/')) {
                    Storage::makeDirectory($rutaBase . '/firmas/');
                }
                $documento = $request->file('firma');
                $nombre_original = pathinfo($documento->getClientOriginalName(), PATHINFO_FILENAME);

                $path = $request->file('firma')->store('public/firmas');


                $url = Storage::url($path);
                if (Storage::exists($path)) {

                    $actualizar = DB::table('versiones')
                        ->where('id', $id)
                        ->update([
                            'nombre' => $request->nombre,
                            'id_cliente' => $request->id_cliente,
                            'id_curso' => $request->id_curso,
                            'id_modalidad' => $request->id_modalidad,
                            'fecha_version' => $request->fecha_curso,
                            'id_empresa' => $request->id_empresa,
                            'id_usuario_instructor' => $request->id_usuario_instructor,
                            'id_usuario_firmante' => $request->id_usuario_firmante,
                            'horas' => $request->horas,
                            'rut' => $request->rut,
                            'contraparte' => $request->contraparte,
                            'telefono' => $request->telefono,
                            'ciudad' => $request->ciudad,
                            'correo_electronico' => $request->correo_electronico,
                            'firma' => $nombre_original,
                            'ruta' => $url,
                        ]);
                    DB::commit();
                    session()->flash('message', 'Registro modificado correctamente.');
                    return redirect()->to('/versiones/lista');
                } else {
                    session()->flash('error', 'Ocurrió un error al subir la foto del producto, favor intente nuevamente.');
                    return redirect()->back();
                }
            } else {
                $actualizar = DB::table('versiones')
                    ->where('id', $id)
                    ->update([
                        'nombre' => $request->nombre,
                        'id_cliente' => $request->id_cliente,
                        'id_curso' => $request->id_curso,
                        'id_modalidad' => $request->id_modalidad,
                        'fecha_version' => $request->fecha_curso,
                        'id_usuario_instructor' => $request->id_usuario_instructor,
                        'id_usuario_firmante' => $request->id_usuario_firmante,
                        'id_empresa' => $request->id_empresa,
                        'horas' => $request->horas,
                        'ciudad' => $request->ciudad,
                        'rut' => $request->rut,
                        'contraparte' => $request->contraparte,
                        'telefono' => $request->telefono,
                        'correo_electronico' => $request->correo_electronico,
                    ]);
                DB::commit();
                session()->flash('message', 'Registro modificado correctamente.');
                return redirect()->to('/versiones/lista');
            }
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Ocurrió un error al actualizar el registro. ' . $e);
            return redirect()->back()->withInput();
        }
    }

    public  function eliminar()
    {
        $datos = Versiones::get();

        return view('versiones.lista', compact('datos'));
    }
}
