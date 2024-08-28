function eliminar(url,id) {
    Swal.fire({
        title: 'Eliminar',
        text: "¿Está seguro(a) que desea eliminar el registro?",
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#343a40',
        confirmButtonText: 'Aceptar'
    }).then((result) => {
        if (result.value == true) {
            window.location.href = url + id
        }
    })
}

function obtener_modelos(nID){
    $("#id_modelo").empty();
    $.ajax({
        type: 'GET',
        url: '/modelos/' + nID,
        success: function(data) {
            $("#id_modelo").empty();
            $("#id_modelo").append('<option value="">Seleccione</option>');
            data.forEach(function(d, index) {
                var o = new Option(d.nombre, d.id);
                $(o).html(d.nombre);
                $("#id_modelo").append(o);
              });
            }
    });
}

function obtener_comunas(nID){
    $("#id_comuna").empty();
    $.ajax({
        type: 'GET',
        url: '/comunas/' + nID,
        success: function(data) {
            $("#id_comuna").empty();
            $("#id_comuna").append('<option value="">Seleccione</option>');
            data.forEach(function(d, index) {
                var o = new Option(d.nombre, d.id);
                $(o).html(d.nombre);
                $("#id_comuna").append(o);
              });
            }
    });
}

function validarRut(rut) {
    // Eliminar los puntos y guiones
    rut = rut.replace(/\./g, '').replace(/-/g, '');

    const rutBody = rut.slice(0, -1);
    const verificador = rut.slice(-1).toUpperCase();

    const reversedRut = rutBody.split('').reverse().join('');

    let suma = 0;
    let multiplicador = 2;

    for (let i = 0; i < reversedRut.length; i++) {
        suma += parseInt(reversedRut[i]) * multiplicador;
        multiplicador = multiplicador === 7 ? 2 : multiplicador + 1;
    }

    const mod = 11 - (suma % 11);
    let dvCalculado = mod === 11 ? '0' : mod === 10 ? 'K' : mod.toString();

    return dvCalculado === verificador;
}

function validarCorreoElectronico(correo) {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return regex.test(correo);
}

function validarNumerico(valor) {
  const regex = /^[+-]?\d+$/;
  return regex.test(valor);
}

function validar_cliente(){
  $('#error_rut').hide();
  $('#error_rut_val').hide();
  $('#error_nombre').hide();
  $('#error_rz').hide();
  $('#error_rubro').hide();
  $('#error_giro').hide();
  $('#error_region').hide();
  $('#error_comuna').hide();
  $('#error_direccion').hide();
  $('#error_correo').hide();
  $('#error_telefono').hide();

  let rut = $('#rut').val();
  let nombre = $('#nombre').val();
  let razon = $('#razon_social').val();
  let rubro = $('#id_rubro').val();
  let giro = $('#giro').val();
  let region = $('#id_region').val();
  let comuna = $('#id_comuna').val();
  let direccion = $('#direccion').val();
  let mensajes = 0;
  if(nombre == ""){
    mensajes++;
    $('#error_nombre').show();
  }
  if(rut == ""){
    mensajes++;
    $('#error_rut').show();
  }
  if(rut != "" && !validarRut(rut)){
    mensajes++;
    $('#error_rut_val').show();
  }

  if(razon == ""){
    mensajes++;
    $('#error_rz').show();
  }
  if(rubro == ""){
    mensajes++;
    $('#error_rubro').show();
  }
  if(giro == ""){
    mensajes++;
    $('#error_giro').show();
  }

  if(region === ""){
    mensajes++;
    $('#error_region').show();
  }
  if(comuna == ""){
    mensajes++;
    $('#error_comuna').show();
  }
  if(direccion == ""){
     mensajes++;
    $('#error_direccion').show();
  }

  if($('#correo').val() != "" && !validarCorreoElectronico($('#correo').val())){
    mensajes++;
   $('#error_correo').show();
 }
  if($('#telefono').val() != "" && !validarNumerico($('#telefono').val())){
    mensajes++;
    $('#error_telefono').show();
  }

  if(mensajes == 0){
      $('#fClientes').submit();
  }
}

function agregar_producto_vencimiento(){
  $('#modal-agregar').modal('show');
}

function agregar_mantencion(){
  $('#modal-agregar_mp').modal('show');
}


function obtener_productos(nID){
  $("#id_producto_d").empty();
  $.ajax({
      type: 'GET',
      url: '/tipos_productos/obtener/' + nID,
      success: function(data) {
          $("#id_producto_d").empty();
          $("#id_producto_d").append('<option value="">Seleccione</option>');
          data.forEach(function(d, index) {
              var o = new Option(d.nombre, d.id);
              $(o).html(d.nombre);
              $("#id_producto_d").append(o);
            });
          }
  });
}

function trazabilidad_con_cliente(){

  $('#error_cliente').hide();
  $('#error_dea').hide();
  $('#error_ubicacion').hide();
  $('#error_serie').hide();

  let dea = $('#id_producto').val();
  let ubicacion = $('#ubicacion').val();
  let serie = $('#numero_serie').val();
  let cliente = $('#id_cliente').val();
  let mensajes = 0;

  if(cliente == ""){
    mensajes++;
    $('#error_cliente').show();
  }
  if(dea == ""){
    mensajes++;
    $('#error_dea').show();
  }
  if(ubicacion == ""){
    mensajes++;
    $('#error_ubicacion').show();
  }
  if(serie == ""){
    mensajes++;
    $('#error_serie').show();
  }

  if(mensajes == 0){
    $('#fClientes').submit();
  }
}
function trazabilidad_cliente(){
  $('#error_rut').hide();
  $('#error_rut_val').hide();
  $('#error_nombre').hide();
  $('#error_rz').hide();
  $('#error_rubro').hide();
  $('#error_giro').hide();
  $('#error_region').hide();
  $('#error_comuna').hide();
  $('#error_direccion').hide();
  $('#error_correo').hide();
  $('#error_telefono').hide();
  $('#error_dea').hide();
  $('#error_ubicacion').hide();
  $('#error_serie').hide();

  let rut = $('#rut').val();
  let nombre = $('#nombre').val();
  let razon = $('#razon_social').val();
  let rubro = $('#id_rubro').val();
  let giro = $('#giro').val();
  let region = $('#id_region').val();
  let comuna = $('#id_comuna').val();
  let direccion = $('#direccion').val();
  let mensajes = 0;
  let dea = $('#id_producto').val();
  let ubicacion = $('#ubicacion').val();
  let serie = $('#numero_serie').val();

  if(nombre == ""){
    mensajes++;
    $('#error_nombre').show();
  }
  if(rut == ""){
    mensajes++;
    $('#error_rut').show();
  }
  if(rut != "" && !validarRut(rut)){
    mensajes++;
    $('#error_rut_val').show();
  }

  if(razon == ""){
    mensajes++;
    $('#error_rz').show();
  }
  if(rubro == ""){
    mensajes++;
    $('#error_rubro').show();
  }
  if(giro == ""){
    mensajes++;
    $('#error_giro').show();
  }

  if(region === ""){
    mensajes++;
    $('#error_region').show();
  }
  if(comuna == ""){
    mensajes++;
    $('#error_comuna').show();
  }
  if(direccion == ""){
    mensajes++;
    $('#error_direccion').show();
  }
  if(dea == ""){
    mensajes++;
    $('#error_dea').show();
  }
  if(ubicacion == ""){
    mensajes++;
    $('#error_ubicacion').show();
  }
  if(serie == ""){
    mensajes++;
    $('#error_serie').show();
  }

  if($('#correo').val() != "" && !validarCorreoElectronico($('#correo').val())){
    mensajes++;
    $('#error_correo').show();
  }
  if($('#telefono').val() != "" && !validarNumerico($('#telefono').val())){
    mensajes++;
    $('#error_telefono').show();
  }

  if(mensajes == 0){
    $('#fClientes').submit();
  }
}

function guardar_mantencion(){
  var data = {
    id_tipo_mantencion: $('#id_tipo_mantencion').val(),
    id_trazabilidad: $('#id_trazabilidad').val(),
    fecha_mantencion: $('#fecha_mantencion').val(),
    _token: $('#id_token_mp').val(),
  };
  $('#error_tipo_mantencion_modal').hide();
  $('#error_fmantencion_modal').hide();
  let errores = 0;
  if($('#fecha_mantencion').val() == ""){
    errores++;
    $('#error_fmantencion_modal').show();
  }
  if($('#id_tipo_mantencion').val() == ""){
    errores++;
    $('#error_tipo_mantencion_modal').show();
  }

  if(errores == 0){
    $.ajax({
        type: "post",
        url: "/trazabilidad/guardar_mantencion",
        data: data,
        success: function(data) {
            let error = data.split("error");
            if (error[1]) {
                $('#msgErrorM').show();
                $('#msgErrorM').html(error[1]);
                $('.alert-danger').fadeIn().delay(3000).fadeOut();
            } else {
                let OK = data.split("OK");
                if (OK[1]) {
                  $('#id_tipo_mantencion').val(null).trigger('change');
                  $('#fecha_mantencion').val('');
                  $('#msgOKM').show();
                  $('#msgOKM').html(OK[1]);
                  $('.alert-success').fadeIn().delay(3000).fadeOut();
                }
            }
        }
    });
  }
}
function agregar_dispositivo(){
  var data = {
    id_tipo_producto: $('#id_tipo_producto').val(),
    id_trazabilidad: $('#id_trazabilidad').val(),
    id_producto: $('#id_producto_d').val(),
    lote: $('#lote').val(),
    vencimiento: $('#vencimiento').val(),
    _token: $('#id_token').val(),
  };
  $('#error_tproducto_modal').hide();
  $('#error_suministro_modal').hide();
  $('#error_lote_modal').hide();
  $('#error_vencimiento_modal').hide();
  let errores = 0;
  if($('#id_tipo_producto').val() == ""){
    errores++;
    $('#error_tproducto_modal').show();
  }
  if($('#id_producto_d').val() == ""){
    errores++;
    $('#error_suministro_modal').show();
  }
  if($('#lote').val() == ""){
    errores++;
    $('#error_lote_modal').show();
  }
  if($('#vencimiento').val() == ""){
    errores++;
    $('#error_vencimiento_modal').show();
  }

    if(errores == 0){
        $.ajax({
            type: "post",
            url: "/trazabilidad/guardar_dispositivo",
            data: data,
            success: function(data) {
                let error = data.split("error");
                if (error[1]) {
                    $('#msgError').show();
                    $('#msgError').html(error[1]);
                    $('.alert-danger').fadeIn().delay(3000).fadeOut();
                } else {
                    let OK = data.split("OK");
                    if (OK[1]) {
                      $('#id_tipo_producto').val(null).trigger('change');
                      $('#id_producto_d').val(null).trigger('change');
                      $('#lote').val('');
                      $('#vencimiento').val('');
                      $('#msgOK').show();
                      $('#msgOK').html(OK[1]);
                      $('.alert-success').fadeIn().delay(3000).fadeOut();
                    }
                }
            }
        });
    }
}


function agregar_tipo_producto(tTitulo,nTipo){
  $('#modal-agregar').modal('show');
  $('#titulo_modal').html(tTitulo);
}

function guardar_tipo_producto(){
  var data = {
    id_tipo_producto: $('#id_tipo_producto').val(),
    id_trazabilidad: $('#id_trazabilidad').val(),
    id_producto: $('#id_producto_d').val(),
    lote: $('#lote').val(),
    vencimiento: $('#vencimiento').val(),
    _token: $('#id_token').val(),
  };
  $.ajax({
    type: "post",
    url: "/tipos_productos/crear",
    data: data,
    success: function(data) {
        let error = data.split("error");
        if (error[1]) {
            $('#msgError').show();
            $('#msgError').html(error[1]);
            $('.alert-danger').fadeIn().delay(3000).fadeOut();
        } else {
            let OK = data.split("OK");
            if (OK[1]) {
              $('#id_tipo_producto').val(null).trigger('change');
              $('#id_producto_d').val(null).trigger('change');
              $('#lote').val('');
              $('#vencimiento').val('');
              $('#msgOK').show();
              $('#msgOK').html(OK[1]);
              $('.alert-success').fadeIn().delay(3000).fadeOut();
            }
        }
    }
});
}

function activaTab(tab){
  $('.nav-tabs a[href="#' + tab + '"]').tab('show');
};


function cambiar_estado(url,id) {
  Swal.fire({
      title: 'Cambiar Estado',
      text: "¿Está seguro(a) que desea cambiar el estado del registro?",
      showCancelButton: true,
      cancelButtonText: 'Cancelar',
      confirmButtonColor: '#28a745',
      cancelButtonColor: '#343a40',
      confirmButtonText: 'Aceptar'
  }).then((result) => {
      if (result.value == true) {
          window.location.href = url  +'/'+ id
      }
  })
}