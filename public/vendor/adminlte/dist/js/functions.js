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
  $('#error_nombre_ppal').hide();
  $('#error_correo_ppal').hide();
  $('#error_telefono_ppal').hide();

  let rut = $('#rut').val();
  let nombre = $('#nombre').val();
  let razon = $('#razon_social').val();
  let rubro = $('#id_rubro').val();
  let giro = $('#giro').val();
  let region = $('#id_region').val();
  let comuna = $('#id_comuna').val();
  let direccion = $('#direccion').val();
  let nombre_cp = $('#nombre_principal').val();
  let telefono_cp = $('#telefono_principal').val();
  let correo_cp = $('#correo_principal').val();
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

  if($('#correo_principal').val() != "" && !validarCorreoElectronico($('#correo_principal').val())){
    mensajes++;
    $('#error_correo_ppal').show();
  }
  if($('#telefono_principal').val() != "" && !validarNumerico($('#telefono_principal').val())){
    mensajes++;
    $('#error_telefono_ppal').show();
  }

  if(mensajes == 0){
      $('#fClientes').submit();
  }
}

function validar_empresa(){
  $('#error_rut').hide();
  $('#error_rut_val').hide();
  $('#error_rz').hide();
  $('#error_giro').hide();
  $('#error_direccion').hide();
  $('#error_correo').hide();
  $('#error_telefono').hide();
  $('#error_telefono_val').hide();
  $('#error_sitio').hide();
  $('#error_logo').hide();
  $('#error_banco').hide();
  $('#error_tipo').hide();
  $('#error_cuenta').hide();

  let rut = $('#rut').val();
  let razon = $('#razon_social').val();
  let giro = $('#giro').val();
  let direccion = $('#direccion').val();
  let telefono = $('#telefono').val();
  let correo = $('#correo_electronico').val();
  let sitio = $('#sitio_web').val();
  let banco = $('#id_banco').val();
  let tipo = $('#id_tipo_cuenta').val();
  let cuenta = $('#numero_cuenta').val();

  let mensajes = 0;

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
  if(giro == ""){
    mensajes++;
    $('#error_giro').show();
  }
  if(direccion == ""){
     mensajes++;
    $('#error_direccion').show();
  }

  if(!validarCorreoElectronico(correo)){
    mensajes++;
    $('#error_correo').show();
  }
  if(!validarNumerico(telefono)){
    mensajes++;
    $('#error_telefono_val').show();
  }

  if(sitio == ""){
    mensajes++;
    $('#error_sitio').show();
  }
  if(banco == ""){
    mensajes++;
    $('#error_banco').show();
  }
  if(tipo == ""){
    mensajes++;
    $('#error_tipo').show();
  }
  if(cuenta == ""){
    mensajes++;
    $('#error_cuenta').show();
  }

  if(mensajes == 0){
      $('#fEmpresas').submit();
  }
}

function validar_cotizacion(){
  $('#error_ejecutivo').hide();
  $('#error_empresa').hide();
  $('#error_solicitante').hide();
  $('#error_correo').hide();
  $('#error_rz').hide();
  $('#error_rut').hide();
  $('#error_rut_val').hide();
  $('#error_giro').hide();
  $('#error_telefono_val').hide();
  $('#error_direccion').hide();
  $('#error_region').hide();
  $('#error_comuna').hide();
  $('#error_vencimiento').hide();
  $('#error_tipo_transporte').hide();
  $('#error_tipo_pago').hide();
  $('#error_plazo_pago').hide();
  $('#error_tiempo').hide();
  $('#error_cantidad_filas').hide();
  $('#error_costo_envio').hide();
  let mensajes = 0;

  let ejecutivo = document.getElementById('id_usuario').value;
  let empresa = document.getElementById('id_empresa').value;
  let solicitante = document.getElementById('solicitante').value;
  let correo = document.getElementById('correo_electronico').value;
  let rz = document.getElementById('razon_social').value;
  let rut = document.getElementById('rut').value;
  let giro = document.getElementById('giro').value;
  let telefono = document.getElementById('telefono').value;
  let direccion = document.getElementById('direccion').value;
  let region = document.getElementById('id_region').value;
  let comuna = document.getElementById('id_comuna').value;
  let vencimiento = document.getElementById('id_vencimiento').value;
  let tipo_transporte = document.getElementById('id_tipo_transporte').value;
  let tipo_pago = document.getElementById('id_tipo_pago').value;
  let plazo_pago = document.getElementById('id_plazo_pago').value;
  let tiempo = document.getElementById('id_tiempo_entrega').value;
  let cantidadFilas = $("#tablaParametros tbody tr").length;
  let costo_envio = $('#costo_envio').val();

  if(ejecutivo == ""){
    mensajes++;
    $('#error_ejecutivo').show();
  }
  if(empresa == ""){
    mensajes++;
    $('#error_empresa').show();
  }
  if(solicitante == ""){
    mensajes++;
    $('#error_solicitante').show();
  }
  if(correo == ""){
    mensajes++;
    $('#error_correo').show();
  }
  if(rz == ""){
    mensajes++;
    $('#error_rz').show();
  }
  if(!validarRut(rut)){
    mensajes++;
    $('#error_rut_val').show();
  }
  if(giro == ""){
    mensajes++;
    $('#error_giro').show();
  }
  if(!validarCorreoElectronico(correo)){
    mensajes++;
    $('#error_correo').show();
  }
  if(!validarNumerico(telefono)){
    mensajes++;
    $('#error_telefono_val').show();
  }
  if(direccion == ""){
    mensajes++;
    $('#error_direccion').show();
  }
  if(region == ""){
    mensajes++;
    $('#error_region').show();
  }
  if(comuna == ""){
    mensajes++;
    $('#error_comuna').show();
  }
  if(vencimiento == ""){
    mensajes++;
    $('#error_vencimiento').show();
  }
  if(tipo_transporte == ""){
    mensajes++;
    $('#error_tipo_transporte').show();
  }
  if(tipo_pago == ""){
    mensajes++;
    $('#error_tipo_pago').show();
  }
  if(plazo_pago == ""){
    mensajes++;
    $('#error_plazo_pago').show();
  }
  if(tiempo == ""){
    mensajes++;
    $('#error_tiempo').show();
  }
  if(cantidadFilas <=1){
    mensajes++;
    $('#error_cantidad_filas').show();
  }
  if(!validarNumerico(costo_envio)){
    mensajes++;
    $('#error_costo_envio').show();
  }
  else{
    if(parseInt(costo_envio) < 0){
      mensajes++;
      $('#error_costo_envio').show();
    }
  }

  if(mensajes == 0){
    $('#fCotizacion').submit();
  }
}

function agregar_producto_vencimiento(){
  $('#modal-agregar').modal('show');
}

function agregar_mantencion(){
  $('#modal-agregar_mp').modal('show');
}

function modal_secundario(){
  $('#modal-agregar').modal('show');
}

$('#fClientes').on('click', '.editar_secundario', function (ists) {
  $('#modal-agregar').modal('show');
  var id = $(this).parent('a')[0].id;
  var json=JSON.parse($('#'+id).attr('datos'));
  console.log(json);
  $('#nombre_secundario').val(json.nombre);
  $('#telefono_secundario').val(json.telefono);
  $('#correo_secundario').val(json.correo_electronico);
  $('#id_contacto').val(json.id);
});

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

function obtener_precio(nID){
  $("#precio").val('');
  $.ajax({
      type: 'GET',
      url: '/productos/obtener_precio/' + nID,
      success: function(data) {
          $("#precio").val(data);
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

function agregar_secundario(){
  var data = {
    nombre_secundario: $('#nombre_secundario').val(),
    telefono_secundario: $('#telefono_secundario').val(),
    correo_secundario: $('#correo_secundario').val(),
    id_cliente: $('#id_cliente').val(),
    id_contacto: $('#id_contacto').val(),
    _token: $('#id_token_mp').val(),
  };
  $('#error_nombre_modal').hide();
  $('#error_telefono_modal').hide();
  $('#error_correo_modal').hide();
  let errores = 0;
  if($('#nombre_secundario').val() == ""){
    errores++;
    $('#error_nombre_modal').show();
  }
  if($('#telefono_secundario').val() == ""){
    errores++;
    $('#error_telefono_modal').show();
  }
  if($('#correo_secundario').val() == ""){
    errores++;
    $('#error_correo_modal').show();
  }

  if($('#correo_secundario').val() != "" && !validarCorreoElectronico($('#correo_secundario').val())){
    errores++;
    $('#error_correo_modal').show();
  }
  if($('#telefono_secundario').val() != "" && !validarNumerico($('#telefono_secundario').val())){
    errores++;
    $('#error_telefono_modal').show();
  }

  if(errores == 0){
    $.ajax({
        type: "post",
        url: "/clientes/guardar_secundario",
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
                  $('#nombre_secundario').val('');
                  $('#telefono_secundario').val('');
                  $('#correo_secundario').val('');
                  $('#id_contacto').val(''),
                  $('#msgOK').show();
                  $('#msgOK').html(OK[1]);
                  $('.alert-success').fadeIn().delay(3000).fadeOut();
                }
            }
        }
    });
  }
}
function guardar_mantencion(){
  var data = {
    id_tipo_mantencion: $('#id_tipo_mantencion').val(),
    id_trazabilidad: $('#id_trazabilidad').val(),
    fecha_mantencion: $('#fecha_mantencion').val(),
    guia_despacho: $('#guia_m').val(),
    factura: $('#factura_m').val(),
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
                  $('#guia_m').val('');
                  $('#factura_m').val('');
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
    guia: $('#guia').val(),
    factura: $('#factura').val(),
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
                      $('#guia').val('');
                      $('#factura').val('');
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

function validarGuionRut(rut){
  cadena= rut.value;
  if(cadena.length && cadena.indexOf('-') == -1){
      // Agregar guion
      largo=cadena.length;
      r=cadena.substring(0,largo-1);
      d=cadena.substring(largo-1,largo);
      c=r+'-'+d;
      rut.value=c;
  }

}

function formatoPesos({ currency, value}) {
  const formatter = new Intl.NumberFormat('es-AR', {
    style: 'currency',
    minimumFractionDigits: 2,
    currency
  }) 
  return formatter.format(value)
}