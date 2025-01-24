var base_url = $('meta[name="base_url"]').attr('content');
var loader = '<img src="' + base_url + '/img/ajax-4.gif" height="40">';

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

function cargarRepartoJuzgamiento() {
  const ruta = base_url + '/procesos/cargar-reparto-juzgamiento';

  $.ajax({
    data: {},
    url: ruta,
    type: 'get',
    success: function (responseText) {
      $('#ajax-buzonJuzgamiento').hide().html(responseText).fadeIn(600);
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}
