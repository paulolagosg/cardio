@extends('adminlte::page')

@section('title', 'Perfil')

@section('content_header')
<span class="text-xl text-bold text-dark">Perfil</span> <span> Datos</span>
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
                <form id="fPerfil" method="POST" action="{{route('perfil.editar')}}">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Nombre:</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{$datos->name}}" required />
                                        <input type="hidden" class="form-control" id="id" name="id" value="{{$datos->id}}" required />
                                        <span id="error_nombre" class="error">Debe ingresar un nombre</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="correo">Correo Electrónico:</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{$datos->email}}" required />
                                        <span id="error_correo" class="error">Debe ingresar un correo electrónico</span>
                                        <span id="error_correo_valido" class="error">Debe ingresar un correo electrónico válido</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" name="btnCambiar" id="btnCambiar" value="0">
                                                <label class="form-check-label" for="btnCambiar">Cambiar Contraseña</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="divClave" style="display:none;">
                                <div class="col-md-12">
                                    <!-- <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="form_clave">Contraseña Actual</label>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="password" class="form-control" id="form_contrasena_actual" name="password_actual" aria-describedby="contrasena_actual_error" placeholder="" value="">
                                                <span id="error_clave_vacia" class="error">Debe ingresar su contraseña actual<br></span>
                                                <span id="error_clave" class="error">La contraseña debe contener mínimo 8 caracteres, letras y números y al menos una letra en mayúscula</span>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <i class="fa fa-eye" id="mostrarCA"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="form_clave">Contraseña Nueva</label>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="password" class="form-control" id="form_contrasena" name="password" aria-describedby="contrasena_error" placeholder="" value="">
                                                <span id="error_clave_nueva" class="error">La contraseña debe contener mínimo 8 caracteres, letras y números y al menos una letra en mayúscula</span>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <i class="fa fa-eye" id="mostrarC"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="form_confirmar_clave">Reescribir Contraseña Nueva</label>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="password" class="form-control" id="form_confirmar_contrasena" name="re_password" aria-describedby="reescribir_contrasena_error" placeholder="" value="" required>
                                                <span id="error_clave_coincide" class="error">Las contraseñas no coinciden</span>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <i class="fa fa-eye" id="mostrarCC"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-success btn-actualizar"><i class="fa fa-save"></i> Guardar</button>
                                    <a href="{{route('home')}}" class="btn bg-dark"><i class="fa fa-ban"></i> Cancelar</a>
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

    $('#btnCambiar').change(function() {
        if ($(this).prop('checked')) {
            $('#divClave').toggle();
            if ($('#divClave').is(":visible")) {
                $('.btn-cambiar').html('Ocultar Contraseña');
            } else {
                $('.btn-cambiar').html('Ver Contraseña');
            }
            $("#form_clave").attr('type', 'password');
            $("#form_confirmar_clave").attr('type', 'password');
        } else {
            $('#divClave').toggle();
        }
    });

    $('#mostrarCA').on('click', function() {
        var passInput = $("#form_contrasena_actual");
        if (passInput.attr('type') === 'password') {
            passInput.attr('type', 'text');
            $('#mostrarCA').removeClass('fa-eye');
            $('#mostrarCA').addClass('fa-eye-slash');
        } else {
            passInput.attr('type', 'password');
            $('#mostrarCA').removeClass('fa-eye-slash');
            $('#mostrarCA').addClass('fa-eye');
        }
    });
    $('#mostrarC').on('click', function() {
        var passInput = $("#form_contrasena");
        if (passInput.attr('type') === 'password') {
            passInput.attr('type', 'text');
            $('#mostrarC').removeClass('fa-eye');
            $('#mostrarC').addClass('fa-eye-slash');
        } else {
            passInput.attr('type', 'password');
            $('#mostrarC').removeClass('fa-eye-slash');
            $('#mostrarC').addClass('fa-eye');
        }
    });

    $('#mostrarCC').on('click', function() {
        var passInput = $("#form_confirmar_contrasena");
        if (passInput.attr('type') === 'password') {
            passInput.attr('type', 'text');
            $('#mostrarCC').removeClass('fa-eye');
            $('#mostrarCC').addClass('fa-eye-slash');
        } else {
            passInput.attr('type', 'password');
            $('#mostrarCC').removeClass('fa-eye-slash');
            $('#mostrarCC').addClass('fa-eye');
        }
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
        $("#error_clave").hide();
        $("#error_clave_nueva").hide();
        $("#error_clave_coincide").hide();
        $('#error_clave_vacia').hide();

        var password_actual = $("input[name='password_actual']").val();
        var password = $("input[name='password']").val();
        var re_password = $("input[name='re_password']").val();
        var nombre = $("input[name='name']").val();
        var correo = $("input[name='email']").val();
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
        if ($('#btnCambiar').is(':checked')) {
            $('#btnCambiar').val(1);
            // if (password_actual === "") {
            //     $('#error_clave_vacia').show();
            //     $("#error_clave").show();
            //     errores = errores + 1;
            // }
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)([A-Za-z\d$@$!%*?&]|[^ ]){8,}$/;
            if (!(regex.test(password))) {
                $("#error_clave_nueva").show();
                errores = errores + 1;
            }
            if (password != re_password) {
                $("#error_clave_coincide").show();
                errores = errores + 1;
            }
        } else {
            $('#btnCambiar').val(0);
        }



        if (errores == 0) {
            $('#fPerfil').submit();
        } else {
            $('.btn-actualizar').prop("disabled", false);
        }

    });
</script>



@stop