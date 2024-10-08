@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Panel de Control</h1>
@stop

@section('content')
<div class="row table-responsive">
    <div class="row">
        <div class="col-12 ml-2">
            <h3>Vencimiento de Suministros</h3>
        </div>
        <div class="col-4">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h4 class="d-none d-sm-block">Vencimiento en 6 meses</h4>
                    <h5 class="d-block d-sm-none">Vencimiento en 6 meses</h5>
                    <h3>{{$vencimientos->seis}}</h3>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="{{route('trazabilidad.vencimientos_panel',6)}}" class="small-box-footer">Mas información <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-4">
            <div class="small-box bg-orange">
                <div class="inner">
                    <h4 class="d-none d-sm-block">Vencimiento en 3 meses</h4>
                    <h5 class="d-block d-sm-none">Vencimiento en 3 meses</h5>
                    <h3>{{$vencimientos->tres}}</h3>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="{{route('trazabilidad.vencimientos_panel',3)}}" class="small-box-footer">Mas información<i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-4">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h4 class="d-none d-sm-block">Vencimiento en 1 mes</h4>
                    <h5 class="d-block d-sm-none">Vencimiento en 1 mes</h5>
                    <h3>{{$vencimientos->uno}}</h3>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="{{route('trazabilidad.vencimientos_panel',1)}}" class="small-box-footer">Mas información <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-12 ml-2">
            <h3>Vencimiento de Mantenciones Preventivas</h3>
        </div>
        <div class="col-6">
            <div class="small-box bg-orange">
                <div class="inner">
                    <h4 class="d-none d-sm-block">Mantención Preventiva Anual</h4>
                    <h5 class="d-block d-sm-none">Mantención Preventiva Anual</h5>
                    <h3>{{$mantenciones->anual}}</h3>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                @if($mantenciones->anual > 0)
                <a href="{{route('trazabilidad.mantenciones','a')}}" class="small-box-footer">Mas información <i class="fas fa-arrow-circle-right"></i></a>
                @else
                <a href="#" onclick="alert('No hay información para mostrar')" class="small-box-footer">Mas información <i class="fas fa-arrow-circle-right"></i></a>
                @endif
            </div>
        </div>
        <div class="col-6">
            <div class="small-box bg-red">
                <div class="inner">
                    <h4 class="d-none d-sm-block">Mantención Preventiva Semestral</h4>
                    <h5 class="d-block d-sm-none">Mantención Preventiva Semestral</h5>
                    <h3>{{$mantenciones->sem}}</h3>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                @if($mantenciones->sem > 0)
                <a href="{{route('trazabilidad.mantenciones','s')}}" class="small-box-footer">Mas información <i class="fas fa-arrow-circle-right"></i></a>
                @else
                <a href="#" onclick="alert('No hay información para mostrar')" class="small-box-footer">Mas información <i class="fas fa-arrow-circle-right"></i></a>
                @endif
            </div>
        </div>
    </div>
    <div class="card d-none d-sm-block">
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div style="height:50vh;">
                        <h4 class="text-center">Clientes por Región</h4>
                        <canvas id="gregiones"></canvas>
                    </div>
                </div>
                <div class="col-6">
                    <div style="height:50vh;;width:100%;">
                        <h4 class="text-center">Clientes por Rubro</h4>
                        <canvas id="grubros"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card d-block d-sm-none table-responsive">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <h4 class="text-center">Clientes por Región</h4>
                    <table id="tablaCR" class="table table-bordered table-hover dataTable dtr-inline table-striped" aria-describedby="tcr">
                        <thead>
                            <tr>
                                <th class="text-center">Región</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clientes_regiones_tabla as $c)
                            <tr>
                                <td class="text-left">{{$c->nombre}}</td>
                                <td class="text-right">{{$c->total}}</td>
                                <td class="text-center">
                                    @if($c->total > 0)
                                    <a href="/clientes/comuna/{{$c->nombre}}"><i class="fa fa-eye text-dark" data-toggle="tooltip" data-placement="top" title="Ver"></i></a>
                                    @else
                                    <a href="#" onclick="alert('No hay información para mostrar')"><i class="fa fa-eye text-dark" data-toggle="tooltip" data-placement="top" title="Ver"></i></a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <h4 class="text-center">Clientes por Rubro</h4>
                    <table id="tablaCR" class="table table-bordered table-hover dataTable dtr-inline table-striped" aria-describedby="tcr">
                        <thead>
                            <tr>
                                <th class="text-center">Rubro</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clientes_rubros_tabla as $c)
                            <tr>
                                <td class="text-left">{{$c->nombre}}</td>
                                <td class="text-right">{{$c->total}}</td>
                                <td class="text-center">
                                    @if($c->total > 0)
                                    <a href="/clientes/rubro/{{$c->nombre}}"><i class="fa fa-eye text-dark" data-toggle="tooltip" data-placement="top" title="Ver"></i></a>
                                    @else
                                    <a href="#" onclick="alert('No hay información para mostrar')"><i class="fa fa-eye text-dark" data-toggle="tooltip" data-placement="top" title="Ver"></i></a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white">
        <div class="row">
            <div class="col-12">
                <div style="max-height:500px;">
                    <x-maps-google :zoomLevel="5" :centerPoint="['lat' => -38, 'long' => -72]" :markers="$mapa"></x-maps-google>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('css')
@stop

@section('js')
<script src="//cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    //grafico clientes por region
    const canvasr = document.getElementById('gregiones');
    const gcreg = canvasr.getContext('2d');
    const chartr = new Chart(gcreg, {
        type: 'bar',
        data: {
            labels: @json($arreglo_regiones),
            datasets: [{
                data: @json($arreglo_cantidades),
                backgroundColor: @json($arreglo_colores),
            }],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false,
                    labels: {
                        color: 'rgb(255, 99, 132)'
                    }
                }
            }
        },
    });
    canvasr.onclick = (evt) => {
        const res = chartr.getElementsAtEventForMode(
            evt,
            'nearest', {
                intersect: true
            },
            true
        );
        // If didn't click on a bar, `res` will be an empty array
        if (res.length === 0) {
            return;
        }
        // Alerts "You clicked on A" if you click the "A" chart
        location.href = "clientes/comuna/" + chartr.data.labels[res[0].index];
    };

    //grafico clientes por rubro
    const canvas = document.getElementById('grubros');
    const gcr = canvas.getContext('2d');
    const chart = new Chart(gcr, {
        type: 'bar',
        data: {
            labels: @json($cr_nombres),
            datasets: [{
                data: @json($cr_cantidades),
                backgroundColor: @json($cr_colores),
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false,
                    labels: {
                        color: 'rgb(255, 99, 132)'
                    }
                }
            }
        },
    });
    canvas.onclick = (evt) => {
        const res = chart.getElementsAtEventForMode(
            evt,
            'nearest', {
                intersect: true
            },
            true
        );
        // If didn't click on a bar, `res` will be an empty array
        if (res.length === 0) {
            return;
        }
        // Alerts "You clicked on A" if you click the "A" chart
        location.href = "clientes/rubro/" + chart.data.labels[res[0].index];
    };
</script>

@stop