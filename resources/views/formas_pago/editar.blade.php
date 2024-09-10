@extends('adminlte::page')

@section('title', 'Formas de Pago - Modificar')

@section('content_header')
<span class="text-xl text-bold text-dark">Formas de Pago</span> <span> Modificar</span>
@stop

@section('content')
<x-editar-parametros titulo="" urlAgregar="/formas_pago/agregar" urlGuardar="/formas_pago/actualizar" urlCancelar="/formas_pago/lista" :datos="$datos" />
@stop
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
@stop

@section('js')


@stop