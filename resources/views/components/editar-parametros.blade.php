@props(['urlAgregar','urlGuardar','urlCancelar','titulo','datos' => $datos])
<div class="card  card-dark">
    <div class="card-header">
        <h3 class="card-title"> {{$titulo}}</h3>
    </div>
    <div class="card-body">
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
        </div>
        <div class="row">
            <div class="col-md-12">

                <div class="card">

                    <form method="post" action="{{$urlGuardar}}">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required value="{{old('nombre',$datos->nombre)}}">
                                <input type="hidden" class="form-control" id="id" name="id" value="{{$datos->id}}">
                            </div>

                            <div>
                                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
                                <a href="{{$urlCancelar}}" class="btn bg-dark"><i class="fa fa-ban"></i> Cancelar</a>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>