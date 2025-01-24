var base_url = $('meta[name="base_url"]').attr('content');
var loader = '<img src="' + base_url + '/img/ajax-4.gif" height="40">';

cargarAutos();

function desactivarBoton(btn, icon) {
  $('#' + btn).removeClass('fa-' + icon);
  $('#' + btn).addClass('fa-refresh fa-spin');
  $('#' + btn)
    .parent()
    .prop('disabled', true);
}

function activarBoton(btn, icon) {
  $('#' + btn).removeClass('fa-refresh fa-spin');
  $('#' + btn).addClass('fa-' + icon);
  $('#' + btn)
    .parent()
    .prop('disabled', false);
}

function errorAjax(responseText) {
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
}

function cargarLineaTiempo() {
  const idRadicado = $('#idRadicado').val();
  const vigencia = $('#vigencia').val();

  const ruta = base_url + '/procesos/cargar-linea-tiempo';

  $.ajax({
    data: { idRadicado, vigencia },
    url: ruta,
    type: 'post',
    success: function (responseText) {
      $('#timelineProgress').hide().html(responseText).fadeIn(600);
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function cargarWidgetProceso() {
  const idRadicado = $('#idRadicado').val();
  const vigencia = $('#vigencia').val();

  const ruta = base_url + '/procesos/cargar-widget-proceso';

  $.ajax({
    data: { idRadicado, vigencia },
    url: ruta,
    type: 'post',
    success: function (responseText) {
      $('#ajax-widgetProceso').hide().html(responseText).fadeIn(600);
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function cargarWidgetPrescripcion() {
  const idRadicado = $('#idRadicado').val();
  const vigencia = $('#vigencia').val();

  const ruta = base_url + '/procesos/cargar-widget-prescripcion';

  $.ajax({
    data: { idRadicado, vigencia },
    url: ruta,
    type: 'post',
    success: function (responseText) {
      $('#ajax-widgetPrescripcion').hide().html(responseText).fadeIn(600);
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function cargarWidgetFalta() {
  const idRadicado = $('#idRadicado').val();
  const vigencia = $('#vigencia').val();

  const ruta = base_url + '/procesos/cargar-widget-falta';

  $.ajax({
    data: { idRadicado, vigencia },
    url: ruta,
    type: 'post',
    success: function (responseText) {
      $('#ajax-widgetFaltas').hide().html(responseText).fadeIn(600);
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function modalCambiarFecha(idEtapa, fase, vigencia, idRadicado, actuacion) {
  const ruta = base_url + '/procesos/modal-cambiar-fecha';

  $('#modalCambiarFecha').modal({
    show: true,
    keyboard: false,
    backdrop: 'static',
  });

  $.ajax({
    data: { idEtapa, fase, vigencia, idRadicado, actuacion },
    url: ruta,
    type: 'post',
    success: function (responseText) {
      $('#ajax-cambiarFecha').hide().html(responseText).fadeIn(600);
      //fechaQueja
      $('#fechaEtapa').datepicker({
        autoclose: true,
        dateFormat: 'yyyy-mm-dd',
        maxDate: new Date(),
        endDate: 'today',
      });
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function cambiarFecha(idEtapa, fase, vigencia, idRadicado, actuacion) {
  const fechaEtapa = $('#fechaEtapa').val();

  const ruta = base_url + '/procesos/cambiar-fecha';

  $('#modalCambiarFecha').hide();

  $.ajax({
    data: { idRadicado, vigencia, idEtapa, fechaEtapa },
    url: ruta,
    type: 'post',
    success: function (responseText) {
      traerFase(fase, vigencia, idRadicado, actuacion);
      alertify.success('La fecha se modificó correctamente.');
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function modalCambiarFechaHechos() {
  const idRadicado = $('#idRadicado').val();
  const vigencia = $('#vigencia').val();

  const ruta = base_url + '/procesos/modal-cambiar-fecha-hechos';

  $('#modalCambiarFechaHechos').modal({
    show: true,
    keyboard: false,
    backdrop: 'static',
  });

  $.ajax({
    data: { idRadicado, vigencia },
    url: ruta,
    type: 'post',
    success: function (responseText) {
      $('#ajax-cambiarFechaHechos').hide().html(responseText).fadeIn(600);
      //fechaQueja
      $('#fechaHechos').datepicker({
        autoclose: true,
        dateFormat: 'yyyy-mm-dd',
        maxDate: new Date(),
        endDate: 'today',
      });
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function modalCambiarFaltasComunes() {
  const idRadicado = $('#idRadicado').val();
  const vigencia = $('#vigencia').val();

  const ruta = base_url + '/procesos/modal-cambiar-faltas-comunes';

  $('#modalCambiarFaltasComunes').modal({
    show: true,
    keyboard: false,
    backdrop: 'static',
  });

  $.ajax({
    data: { idRadicado, vigencia },
    url: ruta,
    type: 'post',
    success: function (responseText) {
      $('#ajax-cambiarFaltasComunes').hide().html(responseText).fadeIn(600);
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function modalRemitirPorCompetencia() {
  const idRadicado = $('#idRadicado').val();
  const vigencia = $('#vigencia').val();

  const ruta = base_url + '/procesos/modal-remitir-por-competencia';

  $('#modalRemitirPorCompetencia').modal({
    show: true,
    keyboard: false,
    backdrop: 'static',
  });

  $.ajax({
    data: { vigencia, idRadicado },
    url: ruta,
    type: 'post',
    success: function (responseText) {
      $('#ajax-remitirPorCompetencia').hide().html(responseText).fadeIn(600);
      //Initialize Select2 Elements
      $('.select2').select2();
      $('#destinatario').focus();
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function remitirPorCompetencia() {
  const idRadicado = $('#idRadicado').val();
  const vigencia = $('#vigencia').val();
  const destinatario = $('#destinatario').val();
  const entidad = $('#entidad').val();
  const direccion = $('#direccion').val();
  const motivo = $('#motivo').val();
  const tipoRemision = $('#tipoRemision').val();

  if (destinatario == '') {
    playAudio('fail');
    alertify.error('Ingrese el nombre del destinatario');
    $('#destinatario').focus();
    return;
  }

  if (direccion == '') {
    playAudio('fail');
    alertify.error('Ingrese la dirección de destino');
    $('#direccion').focus();
    return;
  }

  if (document.getElementById('ciudad')) {
    var ciudad = $('#ciudad').val();

    if (ciudad == 'default') {
      playAudio('fail');
      alertify.error('Seleccione la ciudad de destino');
      $('#ciudad').focus();
      return;
    }
  } else {
    playAudio('fail');
    alertify.error('Seleccione el departamento de destino');
    $('#departamento').focus();
    return;
  }

  if (motivo == '') {
    playAudio('fail');
    alertify.error('Ingrese el motivo de la remisión de la queja');
    $('#motivo').focus();
    return;
  }

  var parametros = {
    idRadicado,
    vigencia,
    destinatario: destinatario.replace(/(\\|\/)+/gi, '-'),
    entidad: entidad.replace(/(\\|\/)+/gi, '-'),
    direccion: direccion.replace(/(\\|\/)+/gi, '-'),
    ciudad,
    motivo: motivo.replace(/(\\|\/)+/gi, '-'),
    tipoRemision,
  };

  var vector = JSON.stringify(parametros);

  var rutaRedirect = base_url + '/procesos/remitir-por-competencia';
  window.location.href = rutaRedirect + '/' + vector;

  //Cierra la ventana modal
  $('#modalRemitirPorCompetencia').modal('hide');

  Swal.fire({
    position: 'top-end',
    icon: 'success',
    title:
      'Se realizó la anotación de remisión por competencia.  Se está generando el Oficio remisorio, en breve será redirigido al inicio.  Por favor espere un momento.',
    showConfirmButton: false,
    timer: 10000,
  });

  var rutaRedirect = base_url + '/inicio';

  setTimeout(function () {
    window.location.href = rutaRedirect;
  }, 7000);
  playAudio('alert');
}

function cambiarFechaHechos() {
  const idRadicado = $('#idRadicado').val();
  const vigencia = $('#vigencia').val();
  const fechaHechos = $('#fechaHechos').val();

  const ruta = base_url + '/procesos/cambiar-fecha-hechos';

  $('#modalCambiarFechaHechos').hide();

  $.ajax({
    data: { idRadicado, vigencia, fechaHechos },
    url: ruta,
    type: 'post',
    success: function (responseText) {
      cargarWidgetPrescripcion();
      alertify.success('La fecha se modificó correctamente.');
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function cambiarFalta() {
  const idRadicado = $('#idRadicado').val();
  const vigencia = $('#vigencia').val();
  const falta = $('#falta').val();

  const ruta = base_url + '/procesos/cambiar-falta';

  $('#modalCambiarFaltasComunes').hide();

  $.ajax({
    data: { idRadicado, vigencia, falta },
    url: ruta,
    type: 'post',
    success: function (responseText) {
      cargarWidgetFalta();
      alertify.success('La falta se modificó correctamente.');
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function cargarAutos() {
  const idRadicado = $('#idRadicado').val();
  const vigencia = $('#vigencia').val();

  const ruta = base_url + '/procesos/cargar-autos';

  $.ajax({
    data: { idRadicado, vigencia },
    url: ruta,
    type: 'post',
    success: function (responseText) {
      $('#ajax-AutosWidget').hide().html(responseText).fadeIn(600);
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function solicitarNumeroAuto() {
  const idEtapa = $('#etapaAuto').val();
  const observacion = $('#observacionAuto').val();

  if (idEtapa == '0') {
    playAudio('fail');
    alertify.error('Seleccione el tipo de auto');
    $('#etapaAuto').focus();
    return false;
  }

  if (observacion == '') {
    playAudio('fail');
    alertify.error('Ingrese el asunto del auto');
    $('#observacionAuto').focus();
    return false;
  }

  Swal.fire({
    title: 'Estás seguro?',
    text: 'Se enviará la solicitud de número de auto',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, enviar solicitud',
    cancelButtonText: 'Cancelar',
  }).then((result) => {
    if (result.isConfirmed) {
      confirmarSolicitarNumeroAuto(idEtapa, observacion);
    }
  });
}

function mensajeAutoRemision() {
  Swal.fire({
    title: 'No existe un número de auto de Remisión por Competencia',
    text: 'Primero debe solicitar un número de auto para Remisión por Competencia.  Cuando le sea asignado el número de auto, actualice esta página y repita el procedimiento.',
    icon: 'error',
    confirmButtonText: 'Aceptar',
  });
}

function confirmarSolicitarNumeroAuto(idEtapa, observacion) {
  const idRadicado = $('#idRadicado').val();
  const vigencia = $('#vigencia').val();

  const btn = 'btn-solicitar-auto';
  const icon = 'hashtag';

  const ruta = base_url + '/procesos/solicitar-numero-auto';

  $.ajax({
    data: { idRadicado, vigencia, idEtapa, observacion },
    url: ruta,
    type: 'post',
    beforeSend: function () {
      desactivarBoton(btn, icon);
    },
    success: function (responseText) {
      $('#etapaAuto').val(0);
      $('#observacionAuto').val('');
      activarBoton(btn, icon);

      cargarAutos();

      //Formato json para los datos recibidos
      var arrayJS = JSON.parse(JSON.stringify(responseText));

      //Realtime
      socket.emit('actualizarSolicitudNumeros', {
        vistaAutos: arrayJS['vistaAutos'],
        nombresUsuario: arrayJS['nombresUsuario'],
      });

      playAudio('alert');
      alertify.success('Se envió la solicitud al director');
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function terminarEtapa(vigencia, idRadicado, idEtapa) {
  var idEtapaSiguiente = $('#etapaSiguiente').val();

  if (idEtapaSiguiente == '') {
    playAudio('fail');
    alertify.error('Seleccione la siguiente etapa');
    $('#etapaSiguiente').focus();
    return;
  }

  alertify.confirm(
    '<b>Finalizar la etapa actual: </b>',
    'El proceso va a pasar a la etapa seleccionada.  <b>Desea continuar?</b>',
    function () {
      //SI
      //13 Etapa Avoca Conocimiento (Juzgamiento)
      if (idEtapaSiguiente == 13) {
        Swal.fire({
          title: 'Fase de Juzgamiento',
          text: 'El proceso pasará a reparto en la fase de juzgamiento',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, enviar a juzgamiento',
          cancelButtonText: 'Cancelar',
        }).then((result) => {
          if (result.isConfirmed) {
            confirmarTerminarEtapa(
              vigencia,
              idRadicado,
              idEtapa,
              idEtapaSiguiente
            );
          }
        });
      } else {
        confirmarTerminarEtapa(vigencia, idRadicado, idEtapa, idEtapaSiguiente);
      }
      //# SI
    },
    function () {
      //NO
      alertify.error('Acción cancelada');
      alertify.closeAll();
      return false;
      //#NO
    }
  );
}

function confirmarTerminarEtapa(
  vigencia,
  idRadicado,
  idEtapa,
  idEtapaSiguiente
) {
  const ruta = base_url + '/procesos/terminar-etapa';

  $.ajax({
    data: { vigencia, idRadicado, idEtapa, idEtapaSiguiente },
    url: ruta,
    type: 'post',
    success: function (responseText) {
      playAudio('alert');
      alertify.success('Se cambió de etapa correctamente');

      //13 Avoca conocimiento
      if (idEtapaSiguiente == 13) {
        setTimeout(function () {
          window.location.href = base_url + '/procesos/activos';
        }, 1000);
      }
      //14 Finalizados
      else if (idEtapaSiguiente == 14) {
        setTimeout(function () {
          window.location.href =
            base_url + '/procesos/ver/' + vigencia + '/' + idRadicado;
        }, 1000);
      } else {
        setTimeout(function () {
          window.location.href =
            base_url + '/procesos/actuaciones/' + vigencia + '/' + idRadicado;
        }, 1000);
      }
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}
