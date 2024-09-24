<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 10px 15px 150px 10px;
            /* Ajustamos márgenes para header y footer */
        }

        body {
            /* font-family: 'DejaVu Sans', sans-serif; */
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            margin: 0;
            padding: 0;
            /* background-image: url('logo-interior.png'); */
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            font-size: 10px;
        }

        header {
            position: fixed;
            top: -100px;
            left: 0;
            right: 0;
            height: 100px;
            /* Coincide con el margen superior */
            text-align: center;
            font-size: 10px;
            /* Centrar texto verticalmente */
        }

        footer {
            position: fixed;
            bottom: -100px;
            left: 0;
            right: 0;
            height: 80px;
            /* Coincide con el margen inferior */
            text-align: center;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .content {
            text-align: justify;
            margin-top: 0px;
            font-size: 14px;
            /* Ajuste para evitar desplazamiento a la segunda página */
        }

        .footer-logo {
            width: 20%;
            height: auto;
        }

        .page-number {
            position: absolute;
            bottom: 0;
            right: 10px;
            font-size: 10px;
        }

        .nombre {
            font-size: 25px;
            color: #000;
            font-weight: bold;
            text-transform: uppercase;
        }

        .titulo_pie {
            font-size: 10px;
            color: #000;
            font-weight: bold;
        }

        .datos {
            font-size: 10px;
            color: #000;
        }

        .datos_totales {
            font-size: 14px;
            color: #000;
        }

        .modelo {
            font-size: 14px;
            color: #6c6c6c;
        }

        .texto {
            font-size: 15px;
            color: #8b8b8b;
            font-weight: bold;
        }

        .derecha {
            text-align: right;
        }

        .izquierda {
            text-align: left;
        }

        .centro {
            text-align: center;
        }

        .page-break {
            page-break-after: always;
        }

        h1 {
            color: #811916;
            text-transform: uppercase;
            font-size: 20px;
        }

        .curso {
            color: #811916;
            text-transform: uppercase;
            font-size: 18px;
        }
    </style>
</head>

<body>
    <!-- <header>
        <div style="width:100%;text-align:center;">

        </div>
    </header> -->

    <footer>
        <div style="width:100%;text-align:left;">
            <img src="{{$certia}}" style="height: 70px;">
            <img src="{{$qr}}">
            <p> {!! $texto_valdacion !!}</p>
        </div>
    </footer>

    <div class="content">
        <div style="width:100%">
            <img src="{{ public_path('fondo_certificado.PNG') }}" style="width:1100px;height:200px" />
        </div>
        <div style="width:30%;text-align:left;float:left">
            <h1>Certificado de Asistencia</h1>
            <!-- <img src="{{$qr}}"> -->
        </div>
        <div style="width:70%;text-align:left;float:left">
            <p class="texto">En reconocimiento a un compromiso con la excelencia profesional.<br>Certificamos que:</p>
            <p class="nombre">{{$alumno->nombre}}</p>
            <p class="texto">Realizó el curso en modalidad <span style="font-weight: bolder;color: #811916;">{{$alumno->modalidad}}</span></p>
            <p class="curso"> {{($alumno->curso)}}</p>
            <p class="texto">Con una carga académica de <span style="font-weight: bolder;color: #811916;">{{$alumno->horas}}</span> horas pedagógicas. Ha @if($alumno->nota >= 4.0) <span style="font-weight: bolder;color: #811916;">aprobado</span> @else <span style="font-weight: bolder;color: #811916;">reprobado</span> @endif el curso con nota <span style="font-weight: bolder;color: #811916;">{{$alumno->nota}}</span> y <span style="font-weight: bolder;color: #811916;">{{$alumno->asistencia}}%</span> de asistencia. Realizado en la ciudad <span style="font-weight: bolder;color: #811916;">{{$alumno->ciudad}}</span>. </p>
            <table style="width:100%">
                <tr>
                    <td style="width:50%; vertical-align: bottom;" class="texto">{{$alumno->fecha}}</td>
                    <td style="width:50%"><img src="{{$firma}}" style="width:50%"></td>
                </tr>
                <tr>
                    <td class="texto">Fecha</td>
                    <td class="texto">{{$alumno->usuario}}<br>{{$alumno->empresa}}</td>
                </tr>
            </table>
        </div>
    </div>

</body>

</html>