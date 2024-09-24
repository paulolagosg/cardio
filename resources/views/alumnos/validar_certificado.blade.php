<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Validación de Certificados</title>

    <!-- Required meta tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.0/js/bootstrap.min.js"></script>

    <!-- Datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/v/bs4-4.1.1/dt-1.10.18/datatables.min.css">
    <script src="https://cdn.datatables.net/v/bs4-4.1.1/dt-1.10.18/datatables.min.js"></script>

    <!-- Buttons -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.53/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.53/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="{{ asset('/vendor/tagsinput/bootstrap-tagsinput.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/tagsinput/app.css') }}">
    <link rel="stylesheet" href="/css/bootstrap-multiselect.css" type="text/css" />


    <style type="text/css">
        .valido {
            color: #28a745;
        }

        .invalido {
            color: #f71313;
        }

        .contenedor_global {
            width: 50px;
            height: 50px;
        }

        body {
            width: auto;
            height: auto;
        }

        h1 {
            font-size: 3rem;
            font-weight: 700;
            color: #000000;
            margin: 0 0 1.5rem;
        }

        i {
            font-size: 1.3rem;
        }

        h1 {
            font-size: 2rem;
        }

        .cover {
            padding: 20px;
        }
    </style>
</head>

<body class="border container">
    <div class="cover">
        <h1>Validación de Certificados</h1>
    </div>

    @if(isset($certificado))
    <div class="row">
        <div class="col-md-12">
            <div class="linea_content_header mt-0"></div>
            <div class="card">
                <div class="card-body">
                    <table id="tabla_documento" class="table table-bordered">
                        <tr>
                            <th colspan="2" scope="row"><i>Resultado</i></th>
                        </tr>
                        <tr>
                            <td colspan="2" class="valido">Código de certificado válido</td>
                        </tr>
                        <tr>
                            <th scope="row">Folio</th>
                            <td>{{$certificado->id}}</td>
                        </tr>
                        <tr>
                            <th scope="row">Fecha</th>
                            <td colspan="2">{{$certificado->fecha}}</td>
                        </tr>
                        <tr>
                            <th scope="row">Código Validación</th>
                            <td>{{$certificado->codigo}}</td>
                        </tr>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12 border">
            <embed class="pdf" src="/{{$certificado->ruta}}" width="100%" height="1000px">
        </div>
    </div>
    @else
    <div class="linea_content_header"></div>
    <div class="card" id="card_invalido" style="border: 2px;">
        <div class="card-body">
            <table id="tabla_documento" class="table table-bordered">
                <tr>
                    <th scope="row">Resultado</th>
                    <td class="invalido">Código de certificado inválido</td>
                </tr>
            </table>
        </div>
    </div>
    @endif
</body>

</html>