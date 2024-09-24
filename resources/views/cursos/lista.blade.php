@extends('adminlte::page')

@section('title', 'Cursos')

@section('content_header')
<span class="text-xl text-bold text-dark">Cursos</span> <span> Lista</span>
@stop
@section('content')
<div class="card card-dark">
    <div class="card-header">
    </div>
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
        <div class="alert alert-danger mt-3 ml-3" style="display: none;position: absolute;">
            {{ session('error') }}
        </div>
        @endif
        @if (session()->has('message'))
        <div class="alert alert-success mt-3 ml-3" style="display: none;position: absolute;">
            {{ session('message') }}
        </div>
        @endif
    </div>
    <div class="card-body table-responsive">
        <p class="text-right">
        <div class="w-50 float-left">
            <form id="fCursos" method="POST" action="{{ url('import-excel-csv-file') }}" accept-charset="utf-8" enctype="multipart/form-data">
                @csrf
                <label for="fileInput" class="btn text-white" style="font-weight:400 !important;background-color:#811916"><i class="fa fa-upload"></i> Cargar desde Excel</label>
                <input type="file" id="fileInput" name="file" class="d-none" onchange="importarCursos(this)">
                <a href="#" onclick="$('#modal-formato').modal('show');" class="btn text-white" style="background-color: #811916; margin-top:-9px"><i class="fa fa-file-excel"></i> Ver Formato</a>
            </form>
        </div>
        <div class="w-50 float-left text-right">
            <a href="/cursos/agregar" class="btn text-white" style="background-color: #811916;"><i class="fa fa-plus-circle"></i> Agregar</a>
        </div>
        <div style="clear: both;"></div>
        </p>
        <table id="tablaParametros" class="table table-bordered table-hover dataTable dtr-inline table-striped" aria-describedby="example2_info">
            <thead>
                <tr>
                    <th class="sorting sorting_asc text-center">ID</th>
                    <th class="sorting text-center">Nombre</th>
                    <th class="sorting text-center">Acciones</th>
                </tr>
                <tr>
                    <th class="th2 filtered">ID</th>
                    <th class="th2 filtered">Nombre</th>
                    <th class="th2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($datos as $d)
                <tr>
                    <td class="text-right">{{$d->id}}</td>
                    <td class="text-left">{{$d->nombre}}</td>
                    <td class="text-center"><a href="/cursos/editar/{{$d->slug}}"><i class="fa fa-edit text-dark" data-toggle="tooltip" data-placement="top" title="Modificar"></i></a>&nbsp;
                        <a href="#" onclick="eliminar('/cursos/eliminar/',{{$d->id}})"><i class="fa fa-trash text-danger" data-toggle="tooltip" data-placement="top" title="Eliminar"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
<!-- formato  -->
<div class="modal fade" id="modal-formato">
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
                                    <th class="text-center">Ejemplo</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <th class="text-left">Nombre Curso</th>
                                    <td class="text-left">Nombre que tendrá el curso</td>
                                    <td class="text-left">Texto</td>
                                    <td class="text-left">Reanimación cardiopulmonar básica y uso del desfibrilador en caso de emergencia según Ley 21.156.</td>
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
                this.api().columns([0, 1]).every(function() {
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
                this.api().columns(2).every(function() {
                    var that = this;
                    var input = $('')
                        .appendTo($(this.header()).empty())
                });
            }
        });

    });
</script>



@stop