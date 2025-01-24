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

$(document).on('change', '#estado', function () {
  const estado = $(this).val();
  if (estado.length > 1) {
    arr = estado.filter(function (item) {
      return item !== '0';
    });
    $('#estado').val(arr).trigger('change.select2');
  }
});

function consultarEstadosQueja() {
  const fechaInicio = $('#fechaInicio').val();
  const fechaFin = $('#fechaFin').val();
  const estado = $('#estado').val();

  var contenedor = 'ajax-estadosQueja';
  const ruta = base_url + '/quejas/consultar-estados-queja';

  $.ajax({
    data: {
      fechaInicio,
      fechaFin,
      estado,
    },
    url: ruta,
    type: 'post',
    beforeSend: function () {
      beforeAjax(contenedor);
    },
    success: function (responseText) {
      //Formato json para los datos recibidos
      var arrayJS = JSON.parse(JSON.stringify(responseText));
      //console.log(arrayJS['valores0'], 'valores cero');
      $('#' + contenedor)
        .html(arrayJS['vistaConsultarEstadosQueja'])
        .fadeIn(600);

      //Contenedor
      var ctx = document.getElementById('grafica-1').getContext('2d');
      // Global Options:
      Chart.defaults.global.defaultFontColor = 'black';
      Chart.defaults.global.defaultFontSize = 14;

      //Datos
      const datos = {
        labels: arrayJS['tipos1'],
        datasets: [
          {
            data: arrayJS['valores1'],
            backgroundColor: arrayJS['colores1'],
            //borderWidth: 1,
            //fill: true,
          },
        ],
      };

      //Opciones
      var opciones = {
        title: {
          display: true,
          text:
            'Todos los estados de las quejas desde el ' +
            arrayJS['fechaInicio'] +
            ' hasta el ' +
            arrayJS['fechaFin'],
          position: 'top',
          padding: 20,
        },
        legend: {
          display: false,
        },
        tooltips: {
          enabled: false,
        },
        cutoutPercentage: 50,
        plugins: {
          labels: [
            {
              render: 'value',
              fontSize: 12,
              fontStyle: 'bold',
              fontColor: '#000',
              fontFamily: '"Lucida Console", Monaco, monospace',
              textShadow: true,
              position: 'border',
              textMargin: 4,
            },
          ],
        },
      };

      //Configuración
      const config = {
        type: 'bar',
        data: datos,
        options: opciones,
      };

      //Render
      const myChart = new Chart(ctx, config);

      $('#tablaQuejas').DataTable({
        iDisplayLength: 100,
      });
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}
