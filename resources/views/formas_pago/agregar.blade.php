@extends('adminlte::page')

@section('title', 'Formas de Pago - Agregar')

@section('content_header')
<span class="text-xl text-bold text-dark">Formas de Pago</span> <span> Agregar</span>
@stop

@section('content')
<x-agregar-parametros titulo="" urlCancelar="/formas_pago/lista" urlAgregar="/formas_pago/crear" />
@stop
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">

@stop

@section('js')


@stop