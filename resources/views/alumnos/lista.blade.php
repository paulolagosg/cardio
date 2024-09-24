@extends('adminlte::page')

@section('title', 'Alumnos')

@section('content_header')
<span class="text-xl text-bold text-dark">Alumnos</span> <span> Lista</span>
@stop
@section('content')

<div class="card card-dark">
    <x-div-procesando />
    <div class="row">
        @if ($errors->any())
        <div class="alert alert-danger mt-3 ml-3">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger mt-3 ml-3" style="display: block;">
            {{ session('error') }}
        </div>
        @endif
        @if (session()->has('message'))
        <div class="alert alert-success mt-3 ml-3" style="display: block;">
            {{ session('message') }}
        </div>
        @endif
    </div>
    <div class="card-body table-responsive">
        <p class="text-right">
        <div class="w-50 float-left">
            <form id="fAlumnos" method="POST" action="{{ url('importar_alumno') }}" accept-charset="utf-8" enctype="multipart/form-data">
                @csrf
                <label for="fileInput" class="btn text-white" style="font-weight:400 !important;background-color:#811916"><i class="fa fa-upload"></i> Cargar desde Excel</label>
                <input type="file" id="fileInput" name="file" class="d-none" onchange="importarAlumnos(this)">
                <a href="#" onclick="$('#modal-formato').modal('show');" class="btn text-white" style="background-color: #811916; margin-top:-9px"><i class="fa fa-file-excel"></i> Ver Formato</a>
            </form>
        </div>
        <div class="w-50 float-left text-right">
            <a href="{{route('alumnos.agregar')}}" class="btn text-white" style="background-color: #811916;"><i class="fa fa-plus-circle"></i> Agregar</a>
        </div>
        <div style="clear: both;"></div>
        </p>
        <table id="tablaParametros" class="table table-bordered table-hover dataTable dtr-inline table-striped" aria-describedby="example2_info">
            <thead>
                <tr>
                    <th class=" text-center">Curso</th>
                    <th class="  text-center">ID Curso</th>
                    <th class="  text-center">ID</th>
                    <th class="text-center">RUT</th>
                    <th class=" text-center">Nombre</th>
                    <th class=" text-center">Empresa</th>
                    <th class="  text-center">Nota</th>
                    <th class=" text-center">Asistencia (%)</th>
                    <th class=" text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($datos as $d)
                <tr>
                    <td class="text-left">{{$d->curso}}</td>
                    <td class="text-right">{{$d->id_curso}}</td>
                    <td class="text-right">{{$d->id}}</td>
                    <td class="text-left">{{$d->rut}}</td>
                    <td class="text-left">{{$d->nombre}}</td>
                    <td class="text-left">{{$d->empresa}}</td>
                    <td class="text-right">{{$d->nota}}</td>
                    <td class="text-right">{{$d->asistencia}}</td>
                    <td class="text-center">
                        @if(isset($d->certificado))
                        <a href="/{{$d->certificado}}" target="_blank"><i class="fa fa-address-card text-dark" data-toggle="tooltip" data-placement="top" title="Ver Certificado"></i></a>&nbsp;&nbsp;
                        <a href="/alumnos/validar_certificado/{{$d->codigo}}" target="_blank"><i class="fa fa-check-square text-dark" data-toggle="tooltip" data-placement="top" title="Validar Certificado"></i></a>&nbsp;&nbsp;
                        <a href="#" class="" onclick="enviar_certificado_alumno({{$d->id}})"><i class="fa fa-share-square text-dark" data-toggle="tooltip" data-placement="top" title="Enviar Certificado"></i></a>&nbsp;&nbsp;
                        @endif
                        @if(!isset($d->certificado))
                        <a href="/alumnos/editar/{{$d->id}}"><i class="fa fa-edit text-dark" data-toggle="tooltip" data-placement="top" title="Modificar"></i></a>&nbsp;&nbsp;
                        <a href="#" onclick="eliminar('/alumnos/eliminar/',{{$d->id}})"><i class="fa fa-trash text-danger" data-toggle="tooltip" data-placement="top" title="Eliminar"></i></a>&nbsp;&nbsp;
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
<!-- formato  -->
<div class="modal fade " id="modal-formato">
    <div class="modal-dialog" style="max-width: 90%;">
        <div class="modal-content table-responsive">
            <div class="modal-header">
                <h4 class="modal-title">Formato Archivo Excel</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="bodyModal">
                <div class="row">
                    <div class="col-md-12">
                        <table id="tablaParametros" class="table table-bordered table-hover dataTable dtr-inline table-striped" aria-describedby="example2_info">
                            <thead>
                                <tr>
                                    <th class="text-center">Campo</th>
                                    <th class="text-center">Descripción</th>
                                    <th class="text-center">Tipo de dato</th>
                                    <th class="text-center">Valores Posibles</th>
                                    <th class="text-center">Ejemplo</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <th class="text-left">Identificador de la versión del curso</th>
                                    <td class="text-left">Código de la versión de curso/capacitación a la pertenece el alumno</td>
                                    <td class="text-left">Numérico</td>
                                    <td class="text-left">
                                        @foreach($versiones as $v)
                                        {{$v->id}}: {{$v->nombre}}<br>
                                        @endforeach
                                    </td>
                                    <td class="text-left">3</td>
                                </tr>
                                <tr>
                                    <th class="text-left">RUT</th>
                                    <td class="text-left">RUT del alumno</td>
                                    <td class="text-left">Texto</td>
                                    <td class="text-left">No aplica</td>
                                    <td class="text-left">22.222.222-2</td>
                                </tr>
                                <tr>
                                    <th class="text-left">Nombre</th>
                                    <td class="text-left">Nombre y apellido del alumno</td>
                                    <td class="text-left">Texto</td>
                                    <td class="text-left">No aplica</td>
                                    <td class="text-left">Francisco Andrés Morales Toro</td>
                                </tr>
                                <tr>
                                    <th class="text-left">Correo electrónico</th>
                                    <td class="text-left">Correo electrónico del alumno</td>
                                    <td class="text-left">Texto</td>
                                    <td class="text-left">No aplica</td>
                                    <td class="text-left">fmorales@dominio.cl</td>
                                </tr>
                                <tr>
                                    <th class="text-left">Nota</th>
                                    <td class="text-left">Nota con la que aprobó/reprobó el curso/capacitación</td>
                                    <td class="text-left">Decimal</td>
                                    <td class="text-left">Entre 1.0 y 7.0</td>
                                    <td class="text-left">5.5</td>
                                </tr>
                                <tr>
                                    <th class="text-left">Asistencia</th>
                                    <td class="text-left">Porcentaje de asistencia del alumno al curso/capacitación</td>
                                    <td class="text-left">Numérico</td>
                                    <td class="text-left">Entre 1 y 100</td>
                                    <td class="text-left">85</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-right">
                <button type="button" class="btn bg-dark" data-dismiss="modal"><i class="fa fa-ban"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>
@stop
@section('css')
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">


<style>
    .page-item.active .page-link {
        background-color: #811916 !important;
        border: 1px solid #dee2e6;
        color: #fff !important;
    }

    .page-link {
        color: #811916 !important;
    }
</style>
@stop

@section('js')
<script src="/vendor/adminlte/dist/js/functions.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        $('.alert-success').fadeIn().delay(3000).fadeOut();
        $('.alert-danger').fadeIn().delay(3000).fadeOut();

        var groupColumn = [0, 1];
        $('#tablaParametros').DataTable({
            language: {
                info: 'Mostrando página _PAGE_ de _PAGES_',
                infoEmpty: 'No hay datos disponbles',
                infoFiltered: '(filtrados de _MAX_ registros totales)',
                lengthMenu: 'Mostrar _MENU_ registros por página',
                info: "Mostrando _START_ a _END_ de _TOTAL_ ",
                zeroRecords: 'No se encontraron datos',
                emptyTable: 'No se encontraron datos',
                search: 'Buscar: ',
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            columnDefs: [{
                visible: false,
                targets: groupColumn
            }, ],
            order: [
                [0, 'asc'],
                [1, 'asc']
            ],
            displayLength: 25,
            drawCallback: function(settings) {
                var api = this.api();
                var rows = api.rows({
                    page: 'current'
                }).nodes();
                var last = null;

                api.column(groupColumn, {
                        page: 'current'
                    })
                    .data()
                    .each(function(group, i, index) {

                        if (last !== group) {
                            let datos = group.split('-');
                            console.log('*' + datos[2] + '*');
                            let contenido = '<tr class="group" style="background-color: #811916;color:#fff;"><td colspan="8"><div class="float-left" style="width:80%">' + datos[1] + '</div>';
                            if (datos[2] !== "") {
                                contenido = contenido + '<div class="float-right text-right"><a href="#" class="text-white" onclick="enviar_certificados(\'' + datos[0] + '\')"><i class="fa fa-share-square fa-lg" data-toggle="tooltip" data-placement="top" title="Enviar Certificados"></i></a></div></td></tr>';
                            } else {
                                contenido = contenido + '<div class="float-right text-right"><a href="#" class="text-white" onclick="generar_certificados_version(\'' + datos[0] + '\')"><i class="fa fa-address-card fa-lg" data-toggle="tooltip" data-placement="top" title="Generar Certificados"></i></a></div></td></tr>';
                            }
                            $(rows)
                                .eq(i)
                                .before(
                                    contenido
                                );
                            last = group;
                        }
                    });
            },
            endRender: function(rows, group) {
                jobSummaryRowGroupNumber++;
                rows.nodes().each((tr, y) => {
                    $(tr).attr('data-groupnumber', jobSummaryRowGroupNumber)
                })
            }
        });

    });
    $('[data-toggle="tooltip"]').tooltip();
</script>



@stop