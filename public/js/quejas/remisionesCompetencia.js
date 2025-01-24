var base_url = $('meta[name="base_url"]').attr('content');
var loader = '<img src="' + base_url + '/img/loading.gif">';
var loaderDark =
  '<img height="50" src="' + base_url + '/img/ajax-loading2.gif">';

function errorAjax(responseText) {
  playAudio('fail');
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

function consultarRemisionesCompetencia() {
  const fechaInicio = $('#fechaInicio').val();
  const fechaFin = $('#fechaFin').val();

  var contenedor = 'ajax-remisionesCompetencia';
  const ruta = base_url + '/quejas/consultar-remisiones-competencia';

  $.ajax({
    data: {
      fechaInicio,
      fechaFin,
    },
    url: ruta,
    type: 'post',
    beforeSend: function () {
      beforeAjax(contenedor);
    },
    success: function (responseText) {
      $('#' + contenedor)
        .html(responseText)
        .fadeIn(600);

      $('#tablaQuejas').DataTable({
        iDisplayLength: 100,
      });
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}
