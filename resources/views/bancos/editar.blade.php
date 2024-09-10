@extends('adminlte::page')

@section('title', 'Bancos - Modificar')

@section('content_header')
<span class="text-xl text-bold text-dark">Bancos</span> <span> Modificar</span>
@stop

@section('content')
<x-editar-parametros titulo="" urlAgregar="/bancos/agregar" urlGuardar="/bancos/actualizar" urlCancelar="/bancos/lista" :datos="$datos" />
@stop
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
@stop

@section('js')


@stop