@extends('adminlte::page')

@section('title', 'Cotizaciones')

@section('content_header')
<span class="text-xl text-bold text-dark">Cotizaciones</span> <span> Lista</span>
@stop
@section('content')
<div class="card card-dark">
    <div class="row">
        @if ($errors->any())
        <div class="alert alert-danger">
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
        <p class="text-right"><a href="{{route('cotizaciones.agregar')}}" class="btn text-white" style="background-color: #811916;"><i class="fa fa-plus-circle"></i> Agregar</a></p>
        <table id="tablaParametros" class="table table-bordered table-hover dataTable dtr-inline table-striped" aria-describedby="example2_info">
            <thead>
                <tr>
                    <th class="sorting sorting_asc text-center">ID</th>
                    <th class="sorting text-center">Fecha</th>
                    <th class="sorting text-center">Solicitante</th>
                    <th class="sorting text-center">Razón Social</th>
                    <th class="sorting text-center">RUT</th>
                    <th class="sorting text-center">Comuna</th>
                    <th class="sorting text-center">Ejecutivo</th>
                    <th class="sorting text-center">Estado</th>
                    <th class="sorting text-center">Acciones</th>
                </tr>
                <tr>
                    <th class="th2 filtered">ID</th>
                    <th class="">Fecha</th>
                    <th class="">Solicitante</th>
                    <th class="">Razón Social</th>
                    <th class="">RUT</th>
                    <th class="">Comuna</th>
                    <th class="">Ejecutivo</th>
                    <th class="">Estado</th>
                    <th class="">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($datos as $d)
                <tr>
                    <td class="text-right">{{$d->id}}</td>
                    <td class="text-center">{{$d->fecha}}</td>
                    <td class="text-left">{{$d->solicitante}}</td>
                    <td class="">{{$d->razon_social}}</td>
                    <td class="text-right">{{$d->rut}}</td>
                    <td class="text-left">{{$d->comuna}}</td>
                    <td class="text-left">{{$d->ejecutivo}}</td>
                    <td class="text-left">{{$d->estado}}</td>
                    <td class="text-center">
                        <a href="{{route('cotizaciones.editar', $d->id)}}"><i class="fa fa-edit text-dark" data-toggle="tooltip" data-placement="top" title="Modificar"></i></a>&nbsp;&nbsp;
                        <a href="{{route('cotizaciones.pdf', $d->id)}}" target="_blank"><i class="fa fa-file text-dark" data-toggle="tooltip" data-placement="top" title="Ver Cotización"></i></a>&nbsp;&nbsp;
                        <a href="{{route('cotizaciones.enviar_pdf', $d->id)}}"><i class="fa fa-share-square text-dark" data-toggle="tooltip" data-placement="top" title="Enviar Cotización al Solicitante"></i></a>
                        @if($d->id_estado == 2)
                        &nbsp;&nbsp;<a href="{{route('cotizaciones.cambiar_estado', [$d->id,3])}}"><i class="fa fa-check-circle text-dark" data-toggle="tooltip" data-placement="top" title="Marcar como Aceptada"></i></a>&nbsp;&nbsp;
                        <a href="{{route('cotizaciones.cambiar_estado', [$d->id,4])}}"><i class="fa fa-ban text-dark" data-toggle="tooltip" data-placement="top" title="Marcar como Aceptada"></i></a>&nbsp;&nbsp;
                        @endif
                        &nbsp;&nbsp;<a href="{{route('cotizaciones.cambiar_estado', [$d->id,5])}}"><i class="fa fa-trash text-danger" data-toggle="tooltip" data-placement="top" title="Eliminar"></i></a>&nbsp;&nbsp;

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>@stop
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
                this.api().columns([0, 1, 2, 3, 4, 5, 6, 7, 8]).every(function() {
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
                this.api().columns(9).every(function() {
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