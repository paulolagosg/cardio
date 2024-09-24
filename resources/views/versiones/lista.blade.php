@extends('adminlte::page')

@section('title', 'Versiones')

@section('content_header')
<span class="text-xl text-bold text-dark">Versiones</span> <span> Lista</span>
@stop
@section('content')
<div class="card card-dark">
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
            <form id="fVersiones" method="POST" action="{{ url('importar_version') }}" accept-charset="utf-8" enctype="multipart/form-data">
                @csrf
                <label for="fileInput" class="btn text-white" style="font-weight:400 !important;background-color:#811916"><i class="fa fa-upload"></i> Cargar desde Excel</label>
                <input type="file" id="fileInput" name="file" class="d-none" onchange="importarVersiones(this)">
                <a href="#" onclick="$('#modal-formato').modal('show');" class="btn text-white" style="background-color: #811916; margin-top:-9px"><i class="fa fa-file-excel"></i> Ver Formato</a>
            </form>
        </div>
        <div class="w-50 float-left text-right">
            <a href="{{route('versiones.agregar')}}" class="btn text-white" style="background-color: #811916;"><i class="fa fa-plus-circle"></i> Agregar</a>
        </div>
        <div style="clear: both;"></div>
        </p>
        <table id="tablaParametros" class="table table-bordered table-hover dataTable dtr-inline table-striped" aria-describedby="example2_info">
            <thead>
                <tr>
                    <th class="sorting sorting_asc text-center">ID</th>
                    <th class="sorting text-center">Versión</th>
                    <th class="sorting text-center">Curso</th>
                    <th class="sorting text-center">Modalidad</th>
                    <th class="sorting text-center">Fecha</th>
                    <th class="sorting text-center">Instructor</th>
                    <th class="sorting text-center">Horas</th>
                    <th class="sorting text-center">Acciones</th>
                </tr>
                <tr>
                    <th class=" text-center">ID</th>
                    <th class="text-center">Versión</th>
                    <th class="text-center">Curso</th>
                    <th class="text-center">Modalidad</th>
                    <th class="text-center">Fecha</th>
                    <th class="text-center">Instructor</th>
                    <th class="text-center">Horas</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($datos as $d)
                <tr>
                    <td class="text-right">{{$d->id}}</td>
                    <td class="text-left">{{$d->nombre}}</td>
                    <td class="text-left">{{$d->curso}}</td>
                    <td class="text-left">{{$d->modalidad}}</td>
                    <td class="text-center">{{$d->fecha}}</td>
                    <td class="text-left">{{$d->instructor}}</td>
                    <td class="text-right">{{$d->horas}}</td>
                    <td class="text-center"><a href="/versiones/editar/{{$d->slug}}"><i class="fa fa-edit text-dark" data-toggle="tooltip" data-placement="top" title="Modificar"></i></a>&nbsp;
                        <a href="#" onclick="eliminar('/versiones/eliminar/',{{$d->id}})"><i class="fa fa-trash text-danger" data-toggle="tooltip" data-placement="top" title="Eliminar"></i></a>
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
                                    <th class="text-left">Nombre versión</th>
                                    <td class="text-left">Nombre que tendrá el registro</td>
                                    <td class="text-left">Texto</td>
                                    <td class="text-left">No aplica</td>
                                    <td class="text-left">Versión Octubre 2024</td>
                                </tr>
                                <tr>
                                    <th class="text-left">Identificador del Cliente</th>
                                    <td class="text-left">Código del cliente al que se le realizará el curso/capacitación</td>
                                    <td class="text-left">Numérico</td>
                                    <td class="text-left">
                                        @foreach($clientes as $c)
                                        {{$c->id}} : {{$c->nombre}}</br>
                                        @endforeach
                                    </td>
                                    <td class="text-left">7</td>
                                </tr>
                                <tr>
                                    <th class="text-left">Identificador del Curso</th>
                                    <td class="text-left">Código del curso que se realizará</td>
                                    <td class="text-left">Numérico</td>
                                    <td class="text-left">
                                        @foreach($cursos as $c)
                                        {{$c->id}} : {{$c->nombre}}</br>
                                        @endforeach
                                    </td>
                                    <td class="text-left">3</td>
                                </tr>
                                <tr>
                                    <th class="text-left">Identificador de Modalidad del Curso</th>
                                    <td class="text-left">Código de la modalidad en que se impartirá el curso/capacitación</td>
                                    <td class="text-left">Numérico</td>
                                    <td class="text-left">
                                        @foreach($modalidades as $c)
                                        {{$c->id}} : {{$c->nombre}}</br>
                                        @endforeach
                                    </td>
                                    <td class="text-left">3</td>
                                </tr>
                                <tr>
                                    <th class="text-left">Fecha Curso</th>
                                    <td class="text-left">Fecha de inicio del Curso</td>
                                    <td class="text-left">Fecha</td>
                                    <td class="text-left">Formato AAAA/MM/DD</td>
                                    <td class="text-left">2024-01-01</td>
                                </tr>
                                <tr>
                                    <th class="text-left">Identificador del Usuario Instructor</th>
                                    <td class="text-left">Código del colaborador que dictará el curso/capacitación</td>
                                    <td class="text-left">Numérico</td>
                                    <td class="text-left">
                                        @foreach($usuarios as $c)
                                        {{$c->id}} : {{$c->name}}</br>
                                        @endforeach
                                    </td>
                                    <td class="text-left">3</td>
                                </tr>
                                <tr>
                                    <th class="text-left">Identificador del Usuario Firmente</th>
                                    <td class="text-left">Código del colaborador que firmará los certificados asociados al curso/capacitación</td>
                                    <td class="text-left">Numérico</td>
                                    <td class="text-left">
                                        @foreach($usuarios as $c)
                                        {{$c->id}} : {{$c->name}}</br>
                                        @endforeach
                                    </td>
                                    <td class="text-left">3</td>
                                </tr>
                                <tr>
                                    <th class="text-left">Horas</th>
                                    <td class="text-left">Cantidad de horas de duración del curso</td>
                                    <td class="text-left">Numérico</td>
                                    <td class="text-left">No aplica</td>
                                    <td class="text-left">40</td>
                                </tr>
                                <tr>
                                    <th class="text-left">Contraparte</th>
                                    <td class="text-left">Nombre de la persona encargada del curso/capacitación por el lado del cliente</td>
                                    <td class="text-left">Texto</td>
                                    <td class="text-left">No aplica</td>
                                    <td class="text-left">Francisco Pérez González</td>
                                </tr>
                                <tr>
                                    <th class="text-left">RUT Contraparte</th>
                                    <td class="text-left">RUT de la persona encargada del curso/capacitación por el lado del cliente</td>
                                    <td class="text-left">Texto</td>
                                    <td class="text-left">No aplica</td>
                                    <td class="text-left">1111111-1</td>
                                </tr>
                                <tr>
                                    <th class="text-left">Correo Electrónico</th>
                                    <td class="text-left">Correo electrónico de la persona encargada del curso/capacitación por el lado del cliente</td>
                                    <td class="text-left">Texto</td>
                                    <td class="text-left">No aplica</td>
                                    <td class="text-left">fperez@cliente.cl</td>
                                </tr>
                                <tr>
                                    <th class="text-left">Teléfono</th>
                                    <td class="text-left">Número de teléfono de la persona encargada del curso/capacitación por el lado del cliente</td>
                                    <td class="text-left">Numérico</td>
                                    <td class="text-left">No aplica</td>
                                    <td class="text-left">999999999</td>
                                </tr>
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
            initComplete: function() {
                this.api().columns([0, 1, 2, 3, 4, 5]).every(function() {
                    var that = this;
                    var input = $('<input size="10" type="text" placeholder="Buscar.." />')
                        .appendTo($(this.header()).empty())
                        .on('keyup change', function() {
                            if (that.search() !== this.value) {
                                that
                                    .search(this.value)
                                    .draw();
                            }
                        });
                });
                this.api().columns(6).every(function() {
                    var that = this;
                    var input = $('')
                        .appendTo($(this.header()).empty())
                });
            }
        });

    });
    $('[data-toggle="tooltip"]').tooltip();
</script>



@stop