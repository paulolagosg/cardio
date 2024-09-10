@extends('adminlte::page')

@section('title', 'Plazos Pago - Modificar')

@section('content_header')
<span class="text-xl text-bold text-dark">Plazos Pago</span> <span> Modificar</span>
@stop

@section('content')
<x-editar-parametros titulo="" urlAgregar="/plazos_pagos/agregar" urlGuardar="/plazos_pagos/actualizar" urlCancelar="/plazos_pagos/lista" :datos="$datos" />
@stop
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
@stop

@section('js')


@stop