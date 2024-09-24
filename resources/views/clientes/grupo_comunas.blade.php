@extends('adminlte::page')

@section('title', 'Clientes por Comuna')

@section('content_header')
<span class="text-xl text-bold text-dark">Clientes {{ucfirst($clientes_comunas[0]->nombre)}}</span>
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
        <div class="row">
            @foreach($clientes_comunas as $c)
            <div class="col-md-3 col-sm-12">
                <div class="small-box @if($c->total == 0) bg-danger @else bg-success @endif">
                    <div class="inner">
                        <h4 class="d-none d-sm-block">{{$c->comuna}}</h4>
                        <h5 class="d-block d-sm-none">{{$c->comuna}}</h5>
                        <h5>Total Clientes {{$c->total}} <br> Total Equipos {{$c->equipos}}</h5>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    @if($c->total > 0)
                    <a href="{{route('clientes.clientes_comunas_detalle',$c->slug)}}" class="small-box-footer">Mas información <i class="fas fa-arrow-circle-right"></i></a>
                    @else
                    <a href="#" onclick="alert('No hay información para mostrar')" class="small-box-footer">Mas información <i class="fas fa-arrow-circle-right"></i></a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-12">
                <a href="/home" class="btn text-white" style="background-color: #87161b"><i class="fa fa-arrow-left"></i> Volver</a>
            </div>
        </div>
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




    });
</script>



@stop