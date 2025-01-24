var base_url = $('meta[name="base_url"]').attr('content');
var loader = '<img src="' + base_url + '/img/loading.gif">';

$('#procesoOrigen').keyup(function (e) {
  clearTimeout($.data(this, 'timer'));
  $(this).data('timer', setTimeout(buscarProcesoAcumular(1), 10));
});

$('#procesoDestino').keyup(function (e) {
  clearTimeout($.data(this, 'timer'));
  $(this).data('timer', setTimeout(buscarProcesoAcumular(2), 10));
});

function buscarProcesoAcumular(tipo) {
  var numeroProcesoBuscar = $('#procesoOrigen').val();
  var contenedor = 'ajax-origen';
  //1 Origen, 2 Destino
  if (tipo == 2) {
    numeroProcesoBuscar = $('#procesoDestino').val();
    contenedor = 'ajax-destino';
  }

  //Si no está completo el campo o éste está vacío
  if (numeroProcesoBuscar.length < 9) {
    return;
  }

  const ruta = base_url + '/procesos/buscar-proceso-acumular';

  $.ajax({
    data: { numeroProcesoBuscar, tipo },
    url: ruta,
    type: 'post',
    beforeSend: function () {
      $('#ajax-buscar').html(
        '<p style="margin-top:10px; width:100%; text-align:center;">' +
          loader +
          '</p>'
      );
    },
    success: function (responseText) {
      $('#' + contenedor).html(responseText);
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function acumularProceso() {
  if (!$('#tipo1').val()) {
    playAudio('fail');
    alertify.error('Ingrese el proceso que se va a acumular');
    $('#procesoOrigen').focus();
    return;
  }

  if (!$('#tipo2').val()) {
    playAudio('fail');
    alertify.error('Ingrese el proceso que recibe la acumulación');
    $('#procesoDestino').focus();
    return;
  }

  if ($('#procesoOrigen').val().length < 9) {
    playAudio('fail');
    alertify.error('El número que se va a acumular está incompleto');
    $('#procesoOrigen').focus();
    return;
  }

  if ($('#procesoDestino').val().length < 9) {
    playAudio('fail');
    alertify.error('El número que recibe la acumulación está incompleto');
    $('#procesoDestino').focus();
    return;
  }

  if ($('#procesoOrigen').val() == $('#procesoDestino').val()) {
    playAudio('fail');
    alertify.error(
      'El número que recibe la acumulación es el mismo que se va a acumular'
    );
    $('#procesoOrigen').focus();
    return;
  }

  if ($('#motivo').val() == '') {
    playAudio('fail');
    alertify.error('Ingrese el motivo de la acumulación');
    $('#motivo').focus();
    return;
  }

  if ($('#fechaAcumulacion').val() == '') {
    playAudio('fail');
    alertify.error('Ingrese la fecha de la acumulación');
    $('#fechaAcumulacion').focus();
    return;
  }

  const ruta = base_url + '/procesos/acumular-proceso-proceso';

  $.ajax({
    data: {
      procesoOrigen: $('#procesoOrigen').val(),
      procesoDestino: $('#procesoDestino').val(),
      motivo: $('#motivo').val(),
      fechaAcumulacion: $('#fechaAcumulacion').val(),
    },
    url: ruta,
    type: 'post',
    success: function (responseText) {
      alertify.success('El proceso se acumuló correctamente.');
      const procesoOrigen = $('#procesoOrigen').val();
      var radicado = procesoOrigen.split('-');

      var vig = radicado[0];
      var rad = radicado[1];
      var rutaRedirect = base_url + '/procesos/ver/' + vig + '/' + rad;

      setTimeout(() => {
        window.location.href = rutaRedirect;
      }, 1000);
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}
