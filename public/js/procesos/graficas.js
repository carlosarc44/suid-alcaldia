var base_url = $('meta[name="base_url"]').attr('content');
var loader = '<img src="' + base_url + '/img/ajax-4.gif" height="40">';

function ejecutarReporte(desdeBoton) {
  const vigencia = $('#vigencia').val();

  const ruta = base_url + '/procesos/ejecutar-reporte-graficas';

  $.ajax({
    data: {
      vigencia,
    },
    url: ruta,
    type: 'post',
    beforeSend: function () {
      //Si este método es llamado desde el clic del botón
      if (desdeBoton == 1) {
        $('#loader').html(
          '<p style="margin-top:10px; width:100%; text-align:center;">' +
            loader +
            '</p>'
        );
      }
    },
    success: function (responseText) {
      //Formato json para los datos recibidos
      var arrayJS = JSON.parse(JSON.stringify(responseText));
      console.log(arrayJS['valores0'], 'valores cero');

      $('#ajax-graficas').html(arrayJS['vistaReporteGraficas']).fadeIn(600);
      $('#loader').empty().fadeIn(600);

      //Gráfica vigencias
      const config = {
        type: 'line',
        data: {
          labels: arrayJS['tipos0'],
          datasets: [
            {
              label: 'Procesos',
              backgroundColor: arrayJS['colores0'],
              borderColor: 'rgb(255, 99, 132)',
              data: arrayJS['valores0'],
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            title: {
              display: true,
              text: 'QUEJAS POR VIGENCIAS:',
            },
          },
        },
      };
      new Chart(document.getElementById('grafica-vigencias'), config);

      //Gráfica Abogados
      const config0 = {
        type: 'bar',
        data: {
          labels: [
            'Ene',
            'Feb',
            'Mar',
            'Abr',
            'May',
            'Jun',
            'Jul',
            'Ago',
            'Sep',
            'Oct',
            'Nov',
            'Dic',
          ],
          datasets: arrayJS['dataAbogados'],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            title: {
              display: true,
              text: 'CANTIDAD PROCESOS ABOGADOS. VIGENCIA: ' + vigencia,
            },
          },
        },
      };
      new Chart(document.getElementById('grafica-abogados'), config0);

      //Gráfica Etapas
      const config1 = {
        type: 'doughnut',
        data: {
          labels: arrayJS['tipos1'],
          datasets: [
            {
              label: 'Procesos',
              backgroundColor: arrayJS['colores1'],
              data: arrayJS['valores1'],
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            title: {
              display: true,
              text: 'ESTADO DE LOS PROCESOS POR ETAPAS.  VIGENCIA: ' + vigencia,
            },
            cutoutPercentage: 50,
            legend: {
              position: 'left',
            },
          },
        },
      };
      new Chart(document.getElementById('grafica-1'), config1);

      //Gráfica Dependencias
      const config2 = {
        type: 'bar',
        data: {
          labels: arrayJS['tipos2'],
          datasets: [
            {
              label: 'Procesos',
              backgroundColor: arrayJS['colores2'],
              data: arrayJS['valores2'],
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            title: {
              display: true,
              text: 'PROCESOS POR DEPENDENCIAS.  VIGENCIA: ' + vigencia,
            },
          },
        },
      };
      new Chart(document.getElementById('grafica-2'), config2);

      //Gráfica Faltas
      const config3 = {
        type: 'pie',
        data: {
          labels: arrayJS['tipos3'],
          datasets: [
            {
              label: 'Procesos',
              backgroundColor: arrayJS['colores3'],
              data: arrayJS['valores3'],
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            title: {
              display: true,
              text: 'FALTAS COMUNES.  VIGENCIA: ' + vigencia,
            },
            cutoutPercentage: 50,
            legend: {
              position: 'left',
            },
          },
        },
      };
      new Chart(document.getElementById('grafica-3'), config3);

      //--
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
