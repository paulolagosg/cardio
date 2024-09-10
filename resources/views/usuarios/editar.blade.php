@extends('adminlte::page')

@section('title', 'Usuarios - Modificar')

@section('content_header')
<span class="text-xl text-bold text-dark">Usuarios</span> <span> Modificar</span>
@stop
@section('content')
<div class="card card-dark">
    <div class="card-body">
        <div class="row">
            @if (session()->has('error'))
            <div class="alert alert-danger" style="display: block;" id="msgerror">
                {{ session('error') }}
                <script>
                    setTimeout(function() {
                        document.getElementById('msgerror').style.display = 'none';
                    }, 2000);
                </script>
            </div>
            @endif
            @if (session()->has('message'))
            <div class="alert alert-success" style="display: block;" id="msgsuccess">
                {{ session('message') }}
                <script>
                    setTimeout(function() {
                        document.getElementById('msgsuccess').style.display = 'none';
                    }, 2000);
                </script>
            </div>
            @endif
        </div>
        <div class="row">
            <div class="col-md-12">
                <form id="fUsuario" method="POST" action="{{route('usuarios.actualizar')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">RUT:</label>
                                        <input type="text" class="form-control" id="rut" name="rut" value="{{$datos->rut}}" required />
                                        <input type="hidden" class="form-control" id="id" name="id" value="{{$datos->id}}" required />
                                        <span id="error_rut" class="error">Debe ingresar un RUT válido</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Nombre:</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{$datos->name}}" required />
                                        <span id="error_nombre" class="error">Debe ingresar un nombre</span>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="correo">Correo Electrónico:</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{$datos->email}}" required />
                                        <span id="error_correo" class="error">Debe ingresar un correo electrónico válido</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="correo">Teléfono:</label>
                                        <input type="email" class="form-control" id="telefono" name="telefono" value="{{$datos->telefono}}" required />
                                        <span id="error_telefono" class="error">Debe ingresar un teléfono válido</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="correo">Foto de Perfil:</label>
                                        <input type="file" class="form-control" id="foto_perfil" name="foto_perfil" />
                                        <small>Formatos permitidos jpg, jpeg, png</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <a href="{{$datos->ruta}}" target="_blank"><img src="{{$datos->ruta}}" width="10%"></a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-success btn-actualizar"><i class="fa fa-save"></i> Guardar</button>
                                    <a href="{{route('usuarios.index')}}" class="btn bg-dark"><i class="fa fa-ban"></i> Cancelar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@stop
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

    .error {
        color: #87161b;
        font-size: 14px;
        display: none;
    }

    .obligatorio {
        color: red;
        font-size: 14px;
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

    $(".btn-actualizar").click(function(e) {
        e.preventDefault();
        $('.btn-actualizar').prop("disabled", true);

        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            if (form.checkValidity() === false) {
                form.classList.add('was-validated');
            }
        });

        $("#error_nombre").hide();
        $("#error_correo").hide();
        $("#error_telefono").hide();
        $("#error_rut").hide();

        var nombre = $("input[name='name']").val();
        var correo = $("input[name='email']").val();
        var telefono = $("input[name='telefono']").val();
        var rut = $("input[name='rut']").val();

        var errores = 0;
        var texto_error = [];

        if (nombre === "") {
            $("#error_nombre").show();
            errores = errores + 1;
        }

        if (correo === "") {
            $("#error_correo").show();
            errores = errores + 1;
        }

        if (correo != "" && !validarCorreoElectronico(correo)) {
            errores++;
            $('#error_correo').show();
        }
        if (telefono != "" && !validarNumerico(telefono)) {
            errores++;
            $('#error_telefono').show();
        }

        if (rut != "" && !validarRut(rut)) {
            errores++;
            $('#error_rut').show();
        }


        if (errores == 0) {
            $('#fUsuario').submit();
        } else {
            $('.btn-actualizar').prop("disabled", false);
        }

    });

    $(function() {
        $("#rut").rut().on('rutValido', function(e, rut, dv) {
            alert("El rut " + rut + "-" + dv + " es correcto");
        }, {
            minimumLength: 7
        });
    })
</script>



@stop