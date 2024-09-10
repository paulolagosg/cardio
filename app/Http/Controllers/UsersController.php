<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    public function index()
    {
        $datos = User::where('estado', 1)->orderBy('name')->get();
        return view('usuarios.lista', compact('datos'));
    }
    public function agregar()
    {
        return view('usuarios.agregar');
    }

    public function agregar_guardar(Request $request)
    {
        $nombre = $request->name;
        $rut = $request->rut;
        $telefono = $request->telefono;
        $correo = $request->email;
        $password = Hash::make($request->password);

        $rutaBase = '/app/public/';

        if (!file_exists($rutaBase . '/perfiles/')) {
            Storage::makeDirectory($rutaBase . '/perfiles/');
        }
        if ($request->file('foto_perfil') !== null) {
            $documento = $request->file('foto_perfil');
            $nombre_original = pathinfo($documento->getClientOriginalName(), PATHINFO_FILENAME);

            $path = $request->file('foto_perfil')->store('public/perfiles');

            // Obtener la URL pública del archivo
            $url = Storage::url($path);
            if (Storage::exists($path)) {
                $usuario = new User();
                $usuario->name = $nombre;
                $usuario->rut = $rut;
                $usuario->email = $correo;
                $usuario->password = $password;
                $usuario->telefono = $telefono;
                $usuario->estado = 1;
                $usuario->foto_perfil = $nombre_original;
                $usuario->ruta = $url;
                $usuario->save();

                session()->flash('message', 'Registro agregado correctamente.');
                return redirect()->to('/usuarios/lista');
            } else {
                session()->flash('error', 'Ocurrió un error al subir la foto perfil, favor intente nuevamente.');
                return redirect()->back();
            }
        } else {
            $usuario = new User();
            $usuario->name = $nombre;
            $usuario->rut = $rut;
            $usuario->email = $correo;
            $usuario->telefono = $telefono;
            $usuario->password = $password;
            $usuario->estado = 1;
            $usuario->save();

            session()->flash('message', 'Registro agregado correctamente.');
            return redirect()->to('/usuarios/lista');
        }
    }

    public function editar($id)
    {
        $datos = User::where('id', $id)->get();
        if (count($datos) <= 0) {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->route('productos.index')->with('error', 'El registro no existe.');
        } else {
            $datos = User::where('id', $id)->first();
        }
        return view('usuarios.editar', compact('datos'));
    }

    public function actualizar(Request $request)
    {
        //dd($request);
        $nombre = $request->name;
        $id = $request->id;
        $rut = $request->rut;
        $telefono = $request->telefono;
        $correo = $request->email;

        if ($request->file('foto_perfil') !== null) {
            $documento = $request->file('foto_perfil');
            $nombre_original = pathinfo($documento->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $documento->getClientOriginalExtension();

            if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
                session()->flash('error', 'Formato de archivo no permitido.');
                return redirect()->back();
            }

            $rutaBase = '/app/public/';

            if (!file_exists($rutaBase . '/perfiles/')) {
                Storage::makeDirectory($rutaBase . '/perfiles/');
            }

            $path = $request->file('foto_perfil')->store('public/perfiles');

            // Obtener la URL pública del archivo
            $url = Storage::url($path);
            if (Storage::exists($path)) {
                $actualizar = DB::table('users')
                    ->where('id', $id)
                    ->update([
                        'name' => $nombre,
                        'email' => $correo,
                        'rut' => $rut,
                        'telefono' => $telefono,
                        'foto_perfil' => $nombre_original,
                        'ruta' => $url
                    ]);
                session()->flash('message', 'Registro modificado correctamente.');
                return redirect()->to('/usuarios/lista');
            } else {
                session()->flash('error', 'Ocurrió un error al subir la foto del producto, favor intente nuevamente.');
                return redirect()->back();
            }
        } else {
            $actualizar = DB::table('users')
                ->where('id', $id)
                ->update([
                    'name' => $nombre,
                    'email' => $correo,
                    'rut' => $rut,
                    'telefono' => $telefono
                ]);
            session()->flash('message', 'Registro modificado correctamente.');
            return redirect()->to('/usuarios/lista');
        }
    }

    public function eliminar($id)
    {
        $datos = User::findOrFail($id);

        $actualizar = DB::table('users')
            ->where('id', $id)
            ->update([
                'estado' => 0
            ]);

        session()->flash('message', 'Registro eliminado correctamente.');
        return Redirect::back();
    }
}
