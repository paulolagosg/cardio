@extends('adminlte::page')

@section('title', 'Marcas - Agregar')

@section('content_header')
<span class="text-xl text-bold text-dark">Marcas</span> <span> Agregar</span>
@stop

@section('content')
<x-agregar-parametros titulo="" urlCancelar="/marcas/lista" urlAgregar="/marcas/crear" />
@stop
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">

@stop

@section('js')


@stop