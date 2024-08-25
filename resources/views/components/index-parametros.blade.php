@props(['urlAgregar','urlEditar','urlEliminar','titulo','idTabla','datos' => $datos])
<div class="card card-dark">
    <div class="card-header">
        <h3 class="card-title">{{$titulo}}</h3>
    </div>
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
        <p class="text-right"><a href="{{$urlAgregar}}" class="btn text-white" style="background-color: #811916;"><i class="fa fa-plus-circle"></i> Agregar</a></p>
        <table id="{{$idTabla}}" class="table table-bordered table-hover dataTable dtr-inline table-striped" aria-describedby="example2_info">
            <thead>
                <tr>
                    <th class="sorting sorting_asc text-center">ID</th>
                    <th class="sorting text-center">Nombre</th>
                    <th class="sorting text-center">Acciones</th>
                </tr>
                <tr>
                    <th class="th2 filtered">ID</th>
                    <th class="th2 filtered">Nombre</th>
                    <th class="th2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($datos as $d)
                <tr>
                    <td class="text-right">{{$d->id}}</td>
                    <td class="text-left">{{$d->nombre}}</td>
                    <td class="text-center"><a href="{{$urlEditar}}/{{$d->slug}}"><i class="fa fa-edit text-dark"></i></a>&nbsp;
                        <a href="#" onclick="eliminar('{{$urlEliminar}}',{{$d->id}})"><i class="fa fa-trash text-danger"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>