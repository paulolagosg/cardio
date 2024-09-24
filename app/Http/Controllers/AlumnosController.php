<?php

namespace App\Http\Controllers;

use App\Models\Alumnos;
use App\Models\Certificados;
use App\Models\Versiones;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\F;
use ZipArchive;
use Mail;

class AlumnosController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public  function index()
    {
        DB::enableQueryLog();
        $datos = Alumnos::join('versiones', 'versiones.id', 'alumnos.id_version')
            ->join('clientes', 'clientes.id', 'versiones.id_cliente')
            ->leftJoin('certificados', 'certificados.id_alumno', 'alumnos.id')
            ->select(
                DB::raw("versiones.id||'-'||versiones.nombre||'-'||coalesce(versiones.nombre_zip,' ') as curso"),
                DB::raw("versiones.id as id_curso"),
                DB::raw("clientes.nombre as empresa"),
                'alumnos.*',
                DB::raw("certificados.ruta as certificado"),
                'certificados.codigo',
            )
            ->get();
        //dd(DB::getQueryLog());

        $versiones = Versiones::where('estado', 1)->orderBy('id', 'asc')->get();
        // v.nombre ,a.id,a.rut,a.nombre ,c.nombre ,a.nota ,a.asistencia

        return view('alumnos.lista', compact('datos', 'versiones'));
    }

    public  function agregar()
    {
        $versiones = Versiones::where('estado', 1)->orderBy('nombre', 'asc')->get();

        return view('alumnos.agregar', compact('versiones'));
    }

    public function agregar_guardar(Request $request)
    {

        $alumno = new Alumnos();
        $alumno->id_version = $request->id_version;
        $alumno->rut = $request->rut;
        $alumno->nombre = $request->nombre;
        $alumno->correo_electronico = $request->correo_electronico;
        $alumno->nota = $request->nota;
        $alumno->asistencia = $request->asistencia;
        $alumno->save();

        session()->flash('message', 'Registro agregado correctamente.');
        return redirect()->to('/alumnos/lista');
    }

    public function editar($id)
    {
        $datos = Alumnos::where('id', $id)->get();
        if (count($datos) <= 0) {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->route('bancos.index')->with('error', 'El registro no existe.');
        } else {
            $datos = Alumnos::where('id', $id)->first();
        }

        $versiones = Versiones::where('estado', 1)->orderBy('nombre', 'asc')->get();

        return view('alumnos.editar', compact('datos', 'versiones'));
    }

    public function actualizar(Request $request)
    {
        //dd($request);

        $actualizar = DB::table('alumnos')
            ->where('id', $request->id)
            ->update([
                'nombre' => $request->nombre,
                'rut' => $request->rut,
                'id_version' => $request->id_version,
                'correo_electronico' => $request->correo_electronico,
                'nota' => $request->nota,
                'asistencia' => $request->asistencia,
            ]);

        session()->flash('message', 'Registro modificado correctamente.');
        return redirect()->to('/alumnos/lista');
    }

    public function eliminar($id)
    {
        $datos = Alumnos::findOrFail($id);

        $deleted = DB::table('alumnos')->where('id', $id)->delete();

        session()->flash('message', 'Registro eliminado correctamente.');
        return Redirect::back();
    }

    function clean($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\_\-]/', '', $string); // Removes special chars.
    }
    public function certificado($id)
    {
        date_default_timezone_set('America/Santiago');

        $alumno = Alumnos::join('versiones', 'versiones.id', 'alumnos.id_version')
            ->join('cursos', 'cursos.id', 'versiones.id_curso')
            ->join('clientes', 'clientes.id', 'versiones.id_cliente')
            ->join('modalidades', 'modalidades.id', 'versiones.id_modalidad')
            ->join('users', 'users.id', 'versiones.id_usuario_firmante')
            ->leftJoin('empresas', 'empresas.id', 'versiones.id_empresa')
            ->where('alumnos.id', $id)
            ->select(
                'alumnos.*',
                'versiones.horas',
                'versiones.ruta',
                'versiones.ciudad',
                DB::raw("versiones.nombre as version"),
                DB::raw("cursos.nombre as curso"),
                DB::raw("versiones.fecha_version as fecha"),
                DB::raw("clientes.nombre as cliente"),
                DB::raw("modalidades.nombre as modalidad"),
                DB::raw("empresas.razon_social as empresa"),
                DB::raw("users.name as usuario")
            )
            ->first();
        $dFechaCreacion = date('Y-m-d');
        $sparamHash = $dFechaCreacion . $alumno->rut . $alumno->version;
        $sHash = hash('sha256', $sparamHash, false);
        $url = env('APP_URL') . '/alumnos/validar_certificado/' . $sHash;
        $codigoQR = 'https://quickchart.io/qr?text=' . $url . '&size=100&.png';
        $firma = public_path($alumno->ruta);
        $certia = public_path('logo_certia.svg');
        $html = 'Para validar el documento haga click <a href="' . $url . '">aqui</a>, o escanee el codigo QR.';

        $data["alumno"] = $alumno;
        $data["qr"] = $codigoQR;
        $data["firma"] = $firma;
        $data["certia"] = $certia;
        $data["texto_valdacion"] = $html;
        $nombre_archivo = $sHash . ".pdf";

        $pdf = Pdf::loadView('alumnos.certificado', $data);
        $pdf->setPaper('A4', 'landscape');

        $pdf->render();
        $canvas = $pdf->getDomPDF()->getCanvas();
        $canvas->page_text(520, 820, "Página {PAGE_NUM}/{PAGE_COUNT}", null, 10, array(0, 0, 0));
        if (!file_exists('public/certificados/')) {
            Storage::makeDirectory('public/certificados/');
        }

        if (Storage::put('public/certificados/' . $nombre_archivo, $pdf->output())) {
            $certificado = new Certificados();
            $certificado->id_alumno = $id;
            $certificado->ruta = 'storage/certificados/' . $nombre_archivo;
            $certificado->codigo =  $sHash;
            $certificado->fecha = Carbon::now();
            $certificado->save();

            session()->flash('message', 'Certificado generado correctamente.');
            return redirect()->to('/alumnos/lista');
        } else {
            session()->flash('error_message', 'El registro no existe.');
            return redirect()->back()->with('error', 'No fue posible generar el certificado, favor intentar nuevamente.');
        }
    }

    public function validar_certificado($id)
    {
        $certificado = Alumnos::join('certificados', 'certificados.id_alumno', 'alumnos.id')
            ->where('certificados.codigo',  $id)
            ->select('certificados.id', 'certificados.codigo', 'certificados.fecha', 'certificados.ruta')
            ->first();

        return view('alumnos.validar_certificado', compact('certificado'));
    }
    public function comprimir_certificados($id)
    {
        // $zip = new ZipArchive;
        date_default_timezone_set('America/Santiago');


        DB::enableQueryLog();
        $alumnos = Alumnos::join('versiones', 'versiones.id', 'alumnos.id_version')
            ->join('cursos', 'cursos.id', 'versiones.id_curso')
            ->join('clientes', 'clientes.id', 'versiones.id_cliente')
            ->join('modalidades', 'modalidades.id', 'versiones.id_modalidad')
            ->join('users', 'users.id', 'versiones.id_usuario_firmante')
            ->leftJoin('empresas', 'empresas.id', 'versiones.id_empresa')
            ->where('versiones.id', $id)
            ->select(
                'alumnos.*',
                'versiones.horas',
                'versiones.ruta',
                'versiones.ciudad',
                'versiones.firma',
                'versiones.id_empresa',
                DB::raw("versiones.nombre as version"),
                DB::raw("cursos.nombre as curso"),
                DB::raw("versiones.fecha_version as fecha"),
                DB::raw("clientes.nombre as cliente"),
                DB::raw("modalidades.nombre as modalidad"),
                DB::raw("empresas.razon_social as empresa"),
                DB::raw("users.name as usuario"),
                DB::raw("alumnos.id as id_alumno")
            )
            ->get();
        //dd(DB::getQueryLog());


        $dFechaCreacion = date('Y-m-d H:i:s');
        foreach ($alumnos as $alumno) {
            if ($alumno->firma == "" || $alumno->firma == " " || $alumno->ciudad == "" || $alumno->empresa == "") {
                echo 'No se ha definido la firma o ciudad o empresa en la versión del curso.';
                exit;
            }

            if (!file_exists('public/certificados/' . $this->clean($alumno->version))) {
                Storage::makeDirectory('public/certificados/' . $this->clean($alumno->version));
            }
            $sparamHash = $dFechaCreacion . $alumno->rut . $alumno->version;
            $sHash = hash('sha256', $sparamHash, false);
            $url = env('APP_URL') . '/alumnos/validar_certificado/' . $sHash;
            $codigoQR = 'https://quickchart.io/qr?text=' . $url . '&size=100&.png';
            $firma = public_path($alumno->ruta);
            $certia = public_path('logo_certia.svg');
            $html = 'Para validar el documento haga click <a href="' . $url . '">aqui</a>, o escanee el codigo QR.';

            $data["alumno"] = $alumno;
            $data["qr"] = $codigoQR;
            $data["firma"] = $firma;
            $data["certia"] = $certia;
            $data["texto_valdacion"] = $html;
            $nombre_archivo = $sHash . ".pdf";

            $pdf = Pdf::loadView('alumnos.certificado', $data);
            $pdf->setPaper('A4', 'landscape');

            $pdf->render();
            $canvas = $pdf->getDomPDF()->getCanvas();
            $canvas->page_text(520, 820, "Página {PAGE_NUM}/{PAGE_COUNT}", null, 10, array(0, 0, 0));
            $archivosOK = true;
            if (Storage::put('public/certificados/' . $this->clean($alumno->version) . '/' . $nombre_archivo, $pdf->output())) {
                $existe = Certificados::where('id_alumno', $alumno->id_alumno)->count();
                if ($existe == 0) {
                    $certificado = new Certificados();
                    $certificado->id_alumno = $alumno->id_alumno;
                    $certificado->ruta = 'storage/certificados/' . $this->clean($alumno->version) . '/'  . $nombre_archivo;
                    $certificado->codigo =  $sHash;
                    $certificado->fecha = $dFechaCreacion; //Carbon::now();
                    $archivosOK = $archivosOK  && $certificado->save();
                }
            }
        }
        if ($archivosOK) {
            $zipFileName = public_path('storage/certificados/' . $this->clean($alumno->version) . '/' . $this->clean($alumno->version) . '.zip');

            $actualizar = DB::table('versiones')
                ->where('id', $id)
                ->update([
                    'ruta_zip' => 'storage/certificados/' . $this->clean($alumno->version) . '/' . $this->clean($alumno->version) . '.zip',
                    'nombre_zip' => 'version' . $this->clean($alumno->version) . '.zip'
                ]);

            $zip = new ZipArchive;

            if ($zip->open($zipFileName, ZipArchive::CREATE) !== TRUE) {
                exit("No se puede abrir <$zipFileName>\n");
            } else {
                $dir = opendir(public_path('storage/certificados/' . $this->clean($alumno->version)));
                while ($elemento = readdir($dir)) {
                    if ($elemento != "." && $elemento != "..") {
                        if (!is_dir(public_path('storage/certificados/' . $this->clean($alumno->version) . '/' . $elemento))) {
                            $ext = pathinfo($elemento, PATHINFO_EXTENSION);
                            if ($ext == 'pdf') {
                                $zip->addFile(public_path('storage/certificados/' . $this->clean($alumno->version) . '/' . $elemento), $elemento);
                            }
                        }
                    }
                }
                $zip->close();
                echo 'ok';
            }
        }
    }

    public function enviar_certificados($id)
    {
        // DB::enableQueryLog();
        $imagePath = public_path('cabecera_correo.jpg');

        $alumnos = Alumnos::join('versiones', 'versiones.id', 'alumnos.id_version')
            ->join('cursos', 'cursos.id', 'versiones.id_curso')
            ->join('clientes', 'clientes.id', 'versiones.id_cliente')
            ->join('modalidades', 'modalidades.id', 'versiones.id_modalidad')
            ->join('users', 'users.id', 'versiones.id_usuario_firmante')
            ->leftJoin('empresas', 'empresas.id', 'versiones.id_empresa')
            ->where('versiones.id', $id)
            ->select(
                'alumnos.*',
                'versiones.horas',
                'versiones.ruta',
                'versiones.ciudad',
                'versiones.firma',
                'versiones.id_empresa',
                'versiones.contraparte',
                'versiones.correo_electronico',
                'versiones.ruta_zip',
                DB::raw("versiones.nombre as version"),
                DB::raw("cursos.nombre as curso"),
                DB::raw("versiones.fecha_version as fecha"),
                DB::raw("clientes.nombre as cliente"),
                DB::raw("modalidades.nombre as modalidad"),
                DB::raw("empresas.razon_social as empresa"),
                DB::raw("users.name as usuario"),
                DB::raw("alumnos.id as id_alumno")
            )
            ->first();

        $imagenPie = public_path('logo-interior.png');
        $imagePath = public_path('cabecera_correo.jpg');
        $tMensajeCertificadosBase = 'Estimado (a) <b>{nombre_cliente}</b>:<br>Junto con saludar y deseando que se encuentren muy bien. Agradecemos y reconocemos la excelente coordinación y apoyo técnico de parte de ustedes, quienes fueron de gran ayuda para cumplir los objetivos de nuestra jornada de Capacitación<br><br><b>Curso {curso}<br>Modalidad: {modalidad}</b><br><br><br>Adjuntamos vuestros respectivos certificados. <br><br>Para cualquier consulta o modificación puede escribirnos a nuestro correo <a href="mailto:cursos@cardioprotegido.cl">cursos@cardioprotegido.cl</a> o bien llamarnos a nuestro Call Center <b>45 2 311 110</b> en donde le atenderemos de la mejor manera para cumplir con sus necesidades.<br><br>Le deseamos un excelente día.';
        $tPie = 'Cardioprotegido<br>Somos especialistas en Desfibriladores de acceso público.<br>www.cardioprotegido.cl | Call Center: 45 2 311 110 | clientes@cardioprotegido.cl<br>¡La oportunidad de salvar una vida está más cerca de lo que crees!';
        $tNota = '<span style="font-size:10px"><b>NOTA:</b> Si usted recibió este correo por error, favor informe al remitente, borre el correo y documentación asociada | Antes de imprimir este correo electrónico, piense bien si es necesario  hacerlo. <span style="color:red;">CARDIOPROTEGIDO</span> comprometido con el medio ambiente.</span><br><br>';

        $tMensajeCertificados = $tMensajeCertificadosBase;
        $tMensajeCertificados = str_replace('{nombre_cliente}', $alumnos->cliente, $tMensajeCertificados);
        $tMensajeCertificados = str_replace('{curso}', $alumnos->curso, $tMensajeCertificados);
        $tMensajeCertificados = str_replace('{modalidad}', $alumnos->modalidad, $tMensajeCertificados);

        $tAsunto = "Certificado Aprobación. Empresa " . $alumnos->cliente;
        $fechaActual = date('Ymd_His');
        $emails = [$alumnos->correo_electronico, 'trazabilidad.temuco@gmail.com'];
        //$emails = [$alumnos->correo_electronico];
        $data["email"] = $emails;
        $data["title"] = $tAsunto;
        $data["body"] = $tMensajeCertificados;
        $data["fecha"] = $fechaActual;
        $data["nota"] = $tNota;
        $data["pie"] = $tPie;
        $data["imagePath"] = $imagePath;
        $data["imagenPie"] = $imagenPie;
        $data["ruta"] = $alumnos->ruta_zip;

        //dd($data);

        $pdf = Pdf::loadView('alumnos.enviar_certificado', $data);
        $pdf->setPaper('A4', 'portrait');

        $pdf->render();

        $canvas = $pdf->getDomPDF()->getCanvas();
        $canvas->page_text(520, 820, "Página {PAGE_NUM}/{PAGE_COUNT}", null, 10, array(0, 0, 0));
        //trazabiliad.temuco@gmail.com

        Mail::send('pdf.alerta', $data, function ($message) use ($data) {
            $message->to($data["email"], $data["email"])
                ->subject($data["title"])
                ->attach($data["ruta"]);
        });

        session()->flash('message', 'Certificados enviados correctamente.');
        return redirect()->back();
    }

    public function enviar_certificado_alumno($id)
    {
        // DB::enableQueryLog();
        $imagePath = public_path('cabecera_correo.jpg');

        $alumnos = Alumnos::join('versiones', 'versiones.id', 'alumnos.id_version')
            ->join('cursos', 'cursos.id', 'versiones.id_curso')
            ->join('clientes', 'clientes.id', 'versiones.id_cliente')
            ->join('modalidades', 'modalidades.id', 'versiones.id_modalidad')
            ->join('users', 'users.id', 'versiones.id_usuario_firmante')
            ->join('certificados', 'certificados.id_alumno', 'alumnos.id')
            ->leftJoin('empresas', 'empresas.id', 'versiones.id_empresa')
            ->where('alumnos.id', $id)
            ->select(
                'alumnos.*',
                'versiones.horas',
                'versiones.ruta',
                'versiones.ciudad',
                'versiones.firma',
                'versiones.id_empresa',
                'alumnos.correo_electronico',
                DB::raw("certificados.ruta as ruta_certificado"),
                DB::raw("alumnos.nombre as nombre_alumno"),
                DB::raw("versiones.nombre as version"),
                DB::raw("cursos.nombre as curso"),
                DB::raw("versiones.fecha_version as fecha"),
                DB::raw("clientes.nombre as cliente"),
                DB::raw("modalidades.nombre as modalidad"),
                DB::raw("empresas.razon_social as empresa"),
                DB::raw("users.name as usuario"),
                DB::raw("alumnos.id as id_alumno")
            )
            ->first();

        $imagenPie = public_path('logo-interior.png');
        $imagePath = public_path('cabecera_correo.jpg');
        $tMensajeCertificadosBase = 'Estimado (a) <b>{nombre_cliente}</b>:<br>Junto con saludar y deseando que se encuentren muy bien. Agradecemos y reconocemos la excelente coordinación y apoyo técnico de parte de ustedes, quienes fueron de gran ayuda para cumplir los objetivos de nuestra jornada de Capacitación<br><br><b>Curso {curso}<br>Modalidad: {modalidad}</b><br><br><br>Adjuntamos vuestros respectivos certificados. <br><br>Para cualquier consulta o modificación puede escribirnos a nuestro correo <a href="mailto:cursos@cardioprotegido.cl">cursos@cardioprotegido.cl</a> o bien llamarnos a nuestro Call Center <b>45 2 311 110</b> en donde le atenderemos de la mejor manera para cumplir con sus necesidades.<br><br>Le deseamos un excelente día.';
        $tPie = 'Cardioprotegido<br>Somos especialistas en Desfibriladores de acceso público.<br>www.cardioprotegido.cl | Call Center: 45 2 311 110 | clientes@cardioprotegido.cl<br>¡La oportunidad de salvar una vida está más cerca de lo que crees!';
        $tNota = '<span style="font-size:10px"><b>NOTA:</b> Si usted recibió este correo por error, favor informe al remitente, borre el correo y documentación asociada | Antes de imprimir este correo electrónico, piense bien si es necesario  hacerlo. <span style="color:red;">CARDIOPROTEGIDO</span> comprometido con el medio ambiente.</span><br><br>';

        $tMensajeCertificados = $tMensajeCertificadosBase;
        $tMensajeCertificados = str_replace('{nombre_cliente}', $alumnos->nombre_alumno, $tMensajeCertificados);
        $tMensajeCertificados = str_replace('{curso}', $alumnos->curso, $tMensajeCertificados);
        $tMensajeCertificados = str_replace('{modalidad}', $alumnos->modalidad, $tMensajeCertificados);

        $tAsunto = "Certificado Aprobación.";
        $fechaActual = date('Ymd_His');
        //$emails = [$alumnos->correo_electronico, 'trazabiliad.temuco@gmail.com'];
        $emails = [$alumnos->correo_electronico];
        $data["email"] = $emails;
        $data["title"] = $tAsunto;
        $data["body"] = $tMensajeCertificados;
        $data["fecha"] = $fechaActual;
        $data["nota"] = $tNota;
        $data["pie"] = $tPie;
        $data["imagePath"] = $imagePath;
        $data["imagenPie"] = $imagenPie;
        $data["ruta"] = $alumnos->ruta_certificado;

        //dd($data);

        $pdf = Pdf::loadView('alumnos.enviar_certificado', $data);
        $pdf->setPaper('A4', 'portrait');

        $pdf->render();

        $canvas = $pdf->getDomPDF()->getCanvas();
        $canvas->page_text(520, 820, "Página {PAGE_NUM}/{PAGE_COUNT}", null, 10, array(0, 0, 0));
        //trazabiliad.temuco@gmail.com

        Mail::send('pdf.alerta', $data, function ($message) use ($data) {
            $message->to($data["email"], $data["email"])
                ->subject($data["title"])
                ->attach($data["ruta"]);
        });

        session()->flash('message', 'Certificados enviados correctamente.');
        return redirect()->back();
    }
}
