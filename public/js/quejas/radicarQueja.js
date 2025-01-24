var base_url = $('meta[name="base_url"]').attr('content');
var loader = '<img src="' + base_url + '/img/loading.gif">';

//Funciones
function validarGuardarQueja() {
  //QUEJA
  const origenQueja = $('#origenQueja').val();
  const tipoRecepcion = $('#tipoRecepcion').val();
  const fechaQueja = $('#fechaQueja').val();
  const fechaRecepcion = $('#fechaRecepcion').val();
  const numeroOficio = $('#numeroOficio').val();
  const presuntoLugar = $('#presuntoLugar').val();
  const presuntosHechos = $('#presuntosHechos').val();
  const dependenciaQueja = $('#dependenciaQueja').val();

  if (origenQueja == 'default') {
    playAudio('fail');
    alertify.error('Seleccione el origen: (Queja / Informe)');
    $('#tipoRecepcion').focus();
    return;
  }

  if (tipoRecepcion == 'default') {
    playAudio('fail');
    alertify.error('Seleccione el tipo de recepción');
    $('#tipoRecepcion').focus();
    return;
  }

  if (fechaQueja == '') {
    playAudio('fail');
    alertify.error('Seleccione la fecha de la queja');
    $('#fechaQueja').focus();
    return;
  }

  if (fechaRecepcion == '') {
    playAudio('fail');
    alertify.error('Seleccione la fecha de recepción');
    $('#fechaRecepcion').focus();
    return;
  }

  if (presuntoLugar == '') {
    playAudio('fail');
    alertify.error('Ingrese el presunto lugar');
    $('#presuntoLugar').focus();
    return;
  }

  if (presuntosHechos == '') {
    playAudio('fail');
    alertify.error('Ingrese los presuntos hechos');
    $('#presuntosHechos').focus();
    return;
  }

  if (presuntosHechos.length < 10) {
    playAudio('fail');
    alertify.error(
      'Descripción de los presuntos hechos demasiado corta.  Ingrese una descripción más amplia'
    );
    $('#presuntosHechos').focus();
    return;
  }

  if (dependenciaQueja == 'default') {
    playAudio('fail');
    alertify.error(
      'Seleccione la dependencia a la que pertence el presunto responsable'
    );
    $('#dependenciaQueja').focus();
    return;
  }

  var ruta = base_url + '/quejas/guardarQueja';

  var parametros = {
    origenQueja,
    fechaRecepcion,
    fechaQueja,
    tipoRecepcion,
    numeroOficio,
    presuntoLugar,
    presuntosHechos,
    dependenciaQueja,
  };

  $.ajax({
    data: parametros,
    url: ruta,
    type: 'post',
    success: function (responseText) {
      playAudio('alert');
      alertify.confirm(
        '<b>Registro Exitoso con el número: ' + responseText + '<b>',
        'Desea ver las quejas pendientes de enviar a reparto?',
        function () {
          var ruta1 = base_url + '/quejas/quejasEnviar';
          window.location.href = ruta1;
        },
        function () {
          var ruta2 = base_url + '/quejas/radicarQueja';
          window.location.href = ruta2;
        }
      );
    },
    error: function (responseText) {
      switch (responseText.status) {
        case 500:
          console.error('Error ' + responseText.status + ' ' + responseText);
          break;
        case 401:
          alertify.confirm(
            '<b>Sesión desconectada</b>',
            'Será redirigido al login para que ingrese nuevamente',
            function () {
              var rutaRedirect = base_url + '/' + 'login';
              window.location.href = rutaRedirect;
            },
            function () {
              return false;
            }
          );
          break;
      }
    },
  });
}

function quitarFila(row) {
  row.closest('tr').remove();
}
