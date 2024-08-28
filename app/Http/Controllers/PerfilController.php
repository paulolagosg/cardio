<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public  function index($id = null)
    {
        if (!isset($id)) {
            $id = auth()->user()->id;
        }
        $datos = User::where('id', $id)->first();

        return view('perfil.index', compact('datos'));
    }

    public function editar(Request $request)
    {

        $tNombre = $request->name;
        $tCorreo = $request->email;
        $tClaveActual = $request->password_actual;
        $tClaveNueva = $request->password;
        $nCambiar = $request->btnCambiar;
        $nUsuario = $request->id;
        $datosActuales =  User::where('id', $nUsuario)->first();
        $tClaveActualHashiada = Hash::make($tClaveActual);

        try {
            DB::beginTransaction();

            if (isset($nCambiar)) {
                //echo $tClaveActual . "<br>" . $datosActuales->password . "<br>" . $tClaveActualHashiada;
                // if ($datosActuales->password != $tClaveActualHashiada) {
                //     //dd("if");
                //     session()->flash('error', 'La clave actual ingresada no coincide con la guardada.');
                //     return redirect()->back();
                // }
                $actualizar = DB::table('users')->where('id', $nUsuario)
                    ->update(['name' => $tNombre, 'email' => $tCorreo, 'password' => Hash::make($tClaveNueva)]);
            } else {
                $actualizar = DB::table('users')->where('id', $nUsuario)
                    ->update(['name' => $tNombre, 'email' => $tCorreo]);
            }
            DB::commit();
            session()->flash('message', 'Registro modificado correctamente.');
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('errors', 'Error al modificar el registro. ' . $e);
            return redirect()->back();
        }
    }
}
