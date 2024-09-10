@extends('adminlte::page')

@section('title', 'Vencimientos - Modificar')

@section('content_header')
<span class="text-xl text-bold text-dark">Vencimientos</span> <span> Modificar</span>
@stop

@section('content')
<x-editar-parametros titulo="" urlAgregar="/vencimientos/agregar" urlGuardar="/vencimientos/actualizar" urlCancelar="/vencimientos/lista" :datos="$datos" />
@stop
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
@stop

@section('js')


@stop