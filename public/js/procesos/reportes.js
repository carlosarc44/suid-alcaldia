var base_url = $('meta[name="base_url"]').attr('content');
var loader = '<img src="' + base_url + '/img/ajax-4.gif" height="40">';

var scrollLoad = true;

$(window).scroll(function () {
  if (
    scrollLoad &&
    $(window).scrollTop() >= $(document).height() - $(window).height() - 400
  ) {
    scrollLoad = false;
    //Add something at the end of the page
    ejecutarReporte(0);
  }
});

function ejecutarReporte(desdeBoton) {
  if (desdeBoton == 1) {
    inicio = $('#inicio').val(0);
    limite = $('#limite').val(10);
  }

  inicio = $('#inicio').val();
  limite = $('#limite').val();

  const vigencia = $('#vigencia').val();
  const estado = $('#estado').val();
  const etapa = $('#etapa').val();
  const abogado = $('#abogado').val();
  const dependencia = $('#dependenciaReporte').val();
  const faltas = $('#faltas').val();

  //console.log('abogado ', abogado)
  if (!abogado) {
    return;
  }

  const ruta = base_url + '/procesos/ejecutar-reporte';

  const parametros = {
    abogado,
    vigencia,
    etapa,
    estado,
    dependencia,
    faltas,
    inicio,
    limite,
  };

  //console.log(parametros, 'parametros')

  if (etapa == null) {
    playAudio('fail');
    alertify.error('Seleccione al menos una etapa');
    $('#etapa').focus();
    return;
  }

  if (dependencia == null) {
    playAudio('fail');
    alertify.error('Seleccione al menos una dependencia');
    $('#dependenciaReporte').focus();
    return;
  }

  if (faltas == null) {
    playAudio('fail');
    alertify.error('Seleccione al menos una falta');
    $('#faltas').focus();
    return;
  }

  $.ajax({
    data: parametros,
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

      calcularVencimientos(parametros);

      //Si este método es llamado desde el clic del botón
      if (desdeBoton == 1) {
        //Si no hay registros limpia el header de la tabla
        if (arrayJS['totalQuejas'] == 0) {
          $('#tabla-reporte').css('display', 'none');
        } else {
          $('#tabla-reporte').css('display', 'block');
        }

        //Limpia los datos de la tabla
        $('#ajax-reporte').empty().fadeIn(600);
        //Agrega el total de registros encontrados
        $('#ajax-resumen').html(arrayJS['vistaResumenReporte']).fadeIn(600);
        scrollToTop();
      }

      $('#loader').empty().fadeIn(600);
      //Agrega los registros a la tabla
      $('#ajax-reporte').append(arrayJS['vista']).fadeIn(600);

      // Incrementar el valor límite para obtener el siguiente conjunto de valores desde el servidor
      const inicio = parseInt($('#inicio').val());
      const limite = parseInt($('#limite').val());
      $('#inicio').val(inicio + limite);
      //calcular los vencimientos

      scrollLoad = true;
      //calcularVencimientos(parametros)

      //Gráfica
      const labels = ['January', 'February', 'March', 'April', 'May', 'June'];

      const data = {
        labels: labels,
        datasets: [
          {
            label: 'My First dataset',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            data: [0, 10, 5, 2, 20, 30, 45],
          },
        ],
      };

      const config = {
        type: 'line',
        data: data,
        options: {
          responsive: true,
          maintainAspectRatio: false,
        },
      };

      const myChart = new Chart(document.getElementById('grafica'), config);
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

function calcularVencimientos(parametros) {
  const ruta = base_url + '/procesos/calcular-vencimientos';

  $.ajax({
    data: parametros,
    url: ruta,
    type: 'post',
    success: function (responseText) {
      console.log('arrayRadicados: ', responseText);

      var arrayJS = JSON.parse(JSON.stringify(responseText));
      var arr = arrayJS['arrayRadicados'];

      for (var i = 0; i < arr.length; i++) {
        $(
          '#ajax-vencimientos_' +
            arr[i]['vigencia'] +
            '-' +
            arr[i]['idRadicado']
        ).html(
          arr[i]['vistaVto_' + arr[i]['vigencia'] + '-' + arr[i]['idRadicado']]
        );
      }
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

$(document).on('change', '#etapa', function () {
  const etapas = $(this).val();
  if (etapas.length > 1) {
    arr = etapas.filter(function (item) {
      return item !== '0';
    });
    $('#etapa').val(arr).trigger('change.select2');
  }
});

$(document).on('change', '#vigencia', function () {
  const vigencias = $(this).val();
  if (vigencias.length > 1) {
    arr = vigencias.filter(function (item) {
      return item !== '0';
    });
    $('#vigencia').val(arr).trigger('change.select2');
  }
});

$(document).on('change', '#dependenciaReporte', function () {
  const dependencias = $(this).val();
  if (dependencias.length > 1) {
    arr = dependencias.filter(function (item) {
      return item !== '0';
    });
    $('#dependenciaReporte').val(arr).trigger('change.select2');
  }
});

$(document).on('change', '#faltas', function () {
  const faltas = $(this).val();
  if (faltas.length > 1) {
    arr = faltas.filter(function (item) {
      return item !== '0';
    });
    $('#faltas').val(arr).trigger('change.select2');
  }
});

window.addEventListener('scroll', (e) => {
  var el = document.getElementById('jsScroll');
  if (window.scrollY > 200) {
    el.classList.add('visible');
  } else {
    el.classList.remove('visible');
  }
});

function scrollToTop() {
  window.scrollTo({
    top: 0,
    behavior: 'smooth',
  });
}
