@extends('adminlte::page')

@section('title', 'Clientes')

@section('content_header')
<span class="text-xl text-bold text-dark">Clientes {{ucfirst($datos[0]->region)}}</span>
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
        <table id="tablaParametros" class="table table-bordered table-hover dataTable dtr-inline table-striped" aria-describedby="example2_info">
            <thead>
                <tr>
                    <th class="sorting sorting_asc text-center">ID</th>
                    <th class="sorting text-center">RUT</th>
                    <th class="sorting text-center">Nombre</th>
                    <th class="sorting text-center">Razón Social</th>
                    <th class="sorting text-center">Región</th>
                    <th class="sorting text-center">Coumna</th>
                    <th class="sorting text-center">Dirección</th>
                    <th class="sorting text-center">Teléfono</th>
                    <th class="sorting text-center">Acciones</th>
                </tr>
                <tr>
                    <th class="th2 filtered">ID</th>
                    <th class="">RUT</th>
                    <th class="">Nombre</th>
                    <th class="">Razón Social</th>
                    <th class="">Región</th>
                    <th class="">Coumna</th>
                    <th class="">Dirección</th>
                    <th class="">Teléfono</th>
                    <th class="">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($datos as $d)
                <tr>
                    <td class="text-right">{{$d->id}}</td>
                    <td class="text-left">{{$d->rut}}</td>
                    <td class="text-left">{{$d->nombre}}</td>
                    <td class="text-left">{{$d->razon_social}}</td>
                    <td class="text-left">{{$d->region}}</td>
                    <td class="text-left">{{$d->comuna}}</td>
                    <td class="text-left">{{$d->direccion}}</td>
                    <td class="text-right">{{$d->telefono}}</td>
                    <td class="text-center"><a href="/clientes/ver/{{$d->slug}}"><i class="fa fa-eye text-dark"></i></a>&nbsp;
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
                this.api().columns([0, 1, 2, 3, 4, 5, 6, 7]).every(function() {
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
                this.api().columns(8).every(function() {
                    var that = this;
                    var input = $('')
                        .appendTo($(this.header()).empty())
                });
            }
        });

    });
</script>



@stop