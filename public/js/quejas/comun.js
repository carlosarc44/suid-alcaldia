var base_url = $('meta[name="base_url"]').attr('content');
var loader = '<img src="' + base_url + '/img/loading.gif">';
var loaderDark =
  '<img height="50" src="' + base_url + '/img/ajax-loading2.gif">';

function verifyChk(id) {
  //extrae el id del campo
  var campos = id.split('-');
  var clave = campos[1];

  if ($('#' + id).prop('checked')) {
    //Activa el campo
    $('#' + clave).prop('disabled', false);
  } else {
    //Inactiva el campo
    $('#' + clave).prop('disabled', true);
  }
}

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

function notificacion(titulo, mensaje) {
  playAudio('notification');
  if (!('Notification' in window)) {
    alert('Este navegador no soporta notificaciones de escritorio');
  } else if (Notification.permission === 'granted') {
    var options = {
      body: mensaje,
      icon: "{{asset('img/SUID_transp2.png')}}",
      dir: 'ltr',
    };
    var notification = new Notification(titulo, options);
  } else if (Notification.permission !== 'denied') {
    Notification.requestPermission(function (permission) {
      if (!('permission' in Notification)) {
        Notification.permission = permission;
      }
      if (permission === 'granted') {
        var options = {
          body: mensaje,
          icon: "{{asset('img/SUID_transp2.png')}}",
          dir: 'ltr',
        };
        var notification = new Notification(titulo, options);
      }
    });
  }
}

//Mostrar cambiar password
function cambiarPassword() {
  $('#modalCambiarPassword').modal('show');
  $('#passwordAnterior').focus();
}
// # Mostrar cambiar password

function validarCambiarPassword() {
  var passwordAnterior = $('#passwordAnterior').val();
  var passwordNuevo = $('#passwordNuevo').val();
  var passwordNuevoR = $('#passwordNuevoR').val();

  if (passwordAnterior == '') {
    playAudio('fail');
    alertify.error('Ingrese la contraseña anterior');
    document.getElementById('passwordAnterior').focus();
    return false;
  } else if (passwordNuevo == '') {
    playAudio('fail');
    alertify.error('Ingrese la nueva contraseña');
    document.getElementById('passwordNuevo').focus();
    return false;
  } else if (passwordNuevoR == '') {
    playAudio('fail');
    alertify.error('Repita la nueva contraseña');
    document.getElementById('passwordNuevoR').focus();
    return false;
  }

  if (passwordNuevo != passwordNuevoR) {
    playAudio('fail');
    alertify.error('Las contraseñas no coinciden');
    document.getElementById('passwordNuevo').focus();
    return false;
  }

  var ruta = base_url + '/users/password';

  var parametros = {
    passwordAnterior,
    passwordNuevo,
  };

  $.ajax({
    data: parametros,
    url: ruta,
    type: 'post',
    success: function (responseText) {
      if (responseText == 1) {
        //1 Contraseña es la misma que el documento del usuario
        playAudio('fail');
        alertify.error(
          'No puede asignar como contraseña el documento de identificación'
        );
        document.getElementById('passwordNuevo').focus();
        return false;
      } else if (responseText == 2) {
        //2 Contraseña anterior ingresada no coincide con la almacenada
        playAudio('fail');
        alertify.error(
          'La contraseña anterior no coincide con la almacenada en la base de datos'
        );
        document.getElementById('passwordAnterior').focus();
        return false;
      } else if (responseText == 3) {
        //3 la Contraseña se modificó correctamente
        playAudio('alert');
        alertify.success('La contraseña se modificó correctamente');
      }
      //Limpia los campos
      $('#passwordAnterior').val('');
      $('#passwordNuevo').val('');
      $('#passwordNuevoR').val('');
      //Oculta la ventana modal
      $('#modalCambiarPassword').modal('hide');
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function fijarDestinatarioOfGral(documentoPersona) {
  var ruta = base_url + '/procesos/fijarDestinatario';

  var parametros = {
    documentoPersona: documentoPersona,
  };

  $.ajax({
    data: parametros,
    url: ruta,
    type: 'post',
    beforeSend: function (responseText) {
      $('#resultadoDestino').html(
        '<p style="margin-top:10px; width:100%; text-align:center;">' +
          loader +
          '</p>'
      );
    },
    success: function (responseText) {
      $('#resultadoDestino').html(responseText);
      //Initialize Select2 Elements
      $('.select2').select2();
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function fijarDestinatarioEntOfGral(idComReg) {
  var ruta = base_url + '/procesos/fijarDestinatarioEnt';

  var parametros = {
    idComReg: idComReg,
  };

  $.ajax({
    data: parametros,
    url: ruta,
    type: 'post',
    beforeSend: function (responseText) {
      $('#resultadoDestino').html(
        '<p style="margin-top:10px; width:100%; text-align:center;">' +
          loader +
          '</p>'
      );
    },
    success: function (responseText) {
      $('#resultadoDestino').html(responseText);
      //Initialize Select2 Elements
      $('.select2').select2();
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function validarGenerarOficioGeneral() {
  var destinatario = $('#destinatario').val();
  var entidad = $('#entidad').val();
  var direccion = $('#direccion').val();
  var asunto = $('#asunto').val();

  if (destinatario == '') {
    playAudio('fail');
    alertify.error('Ingrese el nombre del destinatario');
    $('#destinatario').focus();
    return;
  } else if (direccion == '') {
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

  if (asunto == '') {
    playAudio('fail');
    alertify.error('Ingrese el asunto del oficio');
    $('#asunto').focus();
    return;
  }

  var parametros = {
    destinatario: destinatario.replace(/(\\|\/)+/gi, '-'),
    entidad: entidad.replace(/(\\|\/)+/gi, '-'),
    direccion: direccion.replace(/(\\|\/)+/gi, '-'),
    ciudad: ciudad,
    asunto: asunto.replace(/(\\|\/)+/gi, '-'),
  };

  var vector = JSON.stringify(parametros);

  var rutaRedirect = base_url + '/procesos/guardarOficioGeneral';
  window.location.href = rutaRedirect + '/' + vector;

  //Cierra la ventana modal
  $('#modalOficioGeneral').modal('hide');

  //Llama al método de carga de plantilla
  //setTimeout(function(){$('#modalGenerarOficio').modal('hide');plantilla(idPlantilla, idTipoPlantilla);},2000); // 2000ms = 2s
}

function cargarCiudad(idDepartamento) {
  if (idDepartamento != 'default') {
    var ruta = base_url + '/procesos/cargarCiudad';
    var parametros = { idDepartamento: idDepartamento };

    $.ajax({
      data: parametros,
      url: ruta,
      type: 'post',
      success: function (responseText) {
        $('#resultadoCargarCiudad').html(responseText);
        //Initialize Select2 Elements
        $('.select2').select2();
      },
      error: function (responseText) {
        errorAjax(responseText);
      },
    });
  }
}
///---------------------

function buscarProceso(vigencia) {
  $('#modalBuscarProceso').modal('show');
  buscarProcesoVigencia(vigencia);
}

function buscarProcesoVigencia(vigencia) {
  var ruta = base_url + '/procesos/mostrarBuscarProceso';
  var parametros = {
    vigencia: vigencia,
  };

  $.ajax({
    data: parametros,
    url: ruta,
    type: 'post',
    beforeSend: function () {
      $('#resultadoBuscarProceso').html(
        "<p style='width:100%; margin-top:20px; text-align:center;'>" +
          loader +
          '</p>'
      );
    },
    success: function (responseText) {
      $('#resultadoBuscarProceso').html(responseText);
      $('#tablaProcesos').DataTable({
        iDisplayLength: 100,
        aaSorting: [[0, 'asc']],
        columnDefs: [{ type: 'natural-nohtml', targets: 0 }],
      });
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

/* PLAY SOUND FUNCTION */
function playAudio(file) {
  if (file === 'alert') document.getElementById('audio-alert').play();

  if (file === 'fail') document.getElementById('audio-fail').play();

  if (file === 'notification')
    document.getElementById('audio-notification').play();
}

function agregarQuejoso(idQueja) {
  $('#modalAgregarQuejoso').modal('show');

  var ruta = base_url + '/quejas/agregarQuejoso';

  $.ajax({
    data: { idQueja },
    url: ruta,
    type: 'post',
    beforeSend: function () {
      $('#ajax-agregarQuejoso').html(
        "<p style='width:100%; margin-top:20px; text-align:center;'>" +
          loader +
          '</p>'
      );
    },
    success: function (responseText) {
      $('#ajax-agregarQuejoso').html(responseText);
      quejososQueja(idQueja);
      //Suelta tecla
      $('#docQuejoso').keyup(function (e) {
        clearTimeout($.data(this, 'timer'));
        if (e.keyCode == 32)
          //32 espacio
          buscarDocQuejoso(true, 0);
        else $(this).data('timer', setTimeout(buscarDocQuejoso, 500));
      });
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

$('#numeroProcesoBuscar').keyup(function (e) {
  clearTimeout($.data(this, 'timer'));
  $(this).data('timer', setTimeout(buscarNumeroProceso, 10));
});

$('#quejosoBuscar').keyup(function (e) {
  clearTimeout($.data(this, 'timer'));
  if (e.keyCode == 32)
    //32 espacio
    buscarNombreQuejoso(true, 0);
  else $(this).data('timer', setTimeout(buscarNombreQuejoso, 500));
});

$('#presuntoBuscar').keyup(function (e) {
  clearTimeout($.data(this, 'timer'));
  if (e.keyCode == 32)
    //32 espacio
    buscarNombrePresunto(true, 0);
  else $(this).data('timer', setTimeout(buscarNombrePresunto, 500));
});

$('#palabraClave').keyup(function (e) {
  clearTimeout($.data(this, 'timer'));
  if (e.keyCode == 32)
    //32 espacio
    buscarPalabraClave(true, 0);
  else $(this).data('timer', setTimeout(buscarPalabraClave, 500));
});

function anonimo(idQueja) {
  alertify.confirm(
    '<b>Quejoso Anónimo</b>',
    'Se establecerá el quejoso como anónimo.  Está seguro?',
    function () {
      var ruta = base_url + '/quejas/anonimo';

      $.ajax({
        data: { idQueja },
        url: ruta,
        type: 'post',
        success: function (responseText) {
          //$(".ajax-listaQuejosos_2_"+idQueja).hide().html(arrayJS['vista2']).fadeIn(600)
          quejososQueja(idQueja);
        },
        error: function (responseText) {
          errorAjax(responseText);
        },
      });
    },
    function () {
      alertify.closeAll();
      return false;
    }
  );
}

function porDeterminar(idQueja) {
  alertify.confirm(
    '<b>Presunto Responsable por Determinar</b>',
    'Se establecerá el presunto responsable por determinar.  Está seguro?',
    function () {
      var ruta = base_url + '/quejas/porDeterminar';

      $.ajax({
        data: { idQueja },
        url: ruta,
        type: 'post',
        success: function (responseText) {
          presuntosResponsablesQueja(idQueja);
        },
        error: function (responseText) {
          errorAjax(responseText);
        },
      });
    },
    function () {
      alertify.closeAll();
      return false;
    }
  );
}

function agregarPresuntoResponsable(idQueja) {
  $('#modalAgregarPresuntoResponsable').modal('show');

  var ruta = base_url + '/quejas/agregarPresuntoResponsable';

  $.ajax({
    data: { idQueja },
    url: ruta,
    type: 'post',
    beforeSend: function () {
      $('#ajax-agregarPresuntoResponsable').html(
        "<p style='width:100%; margin-top:20px; text-align:center;'>" +
          loader +
          '</p>'
      );
    },
    success: function (responseText) {
      $('#ajax-agregarPresuntoResponsable').html(responseText);
      presuntosResponsablesQueja(idQueja);
      //Suelta tecla
      $('#docPresuntoResponsable').keyup(function (e) {
        clearTimeout($.data(this, 'timer'));
        if (e.keyCode == 32)
          //32 espacio
          buscarDocPresuntoResponsable(true, 0);
        else
          $(this).data('timer', setTimeout(buscarDocPresuntoResponsable, 500));
      });
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function buscarDocPersona(force) {
  var docPersona = $('#docPersona').val();

  if (!force && docPersona.length < 5) {
    return; //wasn't enter, not > 2 char
  }

  const ruta = base_url + '/quejas/buscarDocPersona';

  $.ajax({
    data: { docPersona },
    url: ruta,
    type: 'post',
    success: function (responseText) {
      $('#ajax-docPersona').html(responseText);
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function buscarDocQuejoso(force) {
  var docQuejoso = $('#docQuejoso').val();

  if (!force && docQuejoso.length < 5) {
    return; //wasn't enter, not > 2 char
  }

  const ruta = base_url + '/quejas/buscarDocQuejoso';

  $.ajax({
    data: { docQuejoso },
    url: ruta,
    type: 'post',
    success: function (responseText) {
      $('#ajax-docQuejoso').html(responseText);
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function buscarNumeroProceso() {
  var numeroProcesoBuscar = $('#numeroProcesoBuscar').val();
  //Si no está completo el campo o éste está vacío
  if (numeroProcesoBuscar.includes('_') || numeroProcesoBuscar.length == 0) {
    return;
  }

  const ruta = base_url + '/procesos/buscar-proceso';

  $.ajax({
    data: { numeroProcesoBuscar },
    url: ruta,
    type: 'post',
    beforeSend: function () {
      $('#ajax-buscar').html(
        '<p style="margin-top:10px; width:100%; text-align:center;">' +
          loaderDark +
          '</p>'
      );
    },
    success: function (responseText) {
      $('#ajax-buscar').html(responseText);

      const numero = numeroProcesoBuscar.split('-');
      let vigencia = numero[0];
      let idRadicado = numero[1];

      //Fase 0 (Desconocida en este punto)
      traerFase(0, vigencia, idRadicado, 0);
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function buscarNombreQuejoso(force) {
  var quejosoBuscar = $('#quejosoBuscar').val();

  if (!force && quejosoBuscar.length < 4) {
    return;
  }

  const ruta = base_url + '/procesos/buscar-nombre-quejoso';

  $.ajax({
    data: { quejosoBuscar },
    url: ruta,
    type: 'post',
    beforeSend: function () {
      $('#ajax-buscar').html(
        '<p style="margin-top:10px; width:100%; text-align:center;">' +
          loaderDark +
          '</p>'
      );
    },
    success: function (responseText) {
      $('#ajax-buscar').html(responseText);
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function buscarNombrePresunto(force) {
  var presuntoBuscar = $('#presuntoBuscar').val();

  if (!force && presuntoBuscar.length < 4) {
    return;
  }

  const ruta = base_url + '/procesos/buscar-nombre-presunto';

  $.ajax({
    data: { presuntoBuscar },
    url: ruta,
    type: 'post',
    beforeSend: function () {
      $('#ajax-buscar').html(
        '<p style="margin-top:10px; width:100%; text-align:center;">' +
          loaderDark +
          '</p>'
      );
    },
    success: function (responseText) {
      $('#ajax-buscar').html(responseText);
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function buscarPalabraClave(force) {
  var palabraClave = $('#palabraClave').val();

  if (!force && palabraClave.length < 4) {
    return;
  }

  const ruta = base_url + '/procesos/buscar-palabra-clave';

  $.ajax({
    data: { palabraClave },
    url: ruta,
    type: 'post',
    beforeSend: function () {
      $('#ajax-buscar').html(
        '<p style="margin-top:10px; width:100%; text-align:center;">' +
          loaderDark +
          '</p>'
      );
    },
    success: function (responseText) {
      $('#ajax-buscar').html(responseText);
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function buscarDocPresuntoResponsable(force) {
  var docPresuntoResponsable = $('#docPresuntoResponsable').val();

  if (!force && docPresuntoResponsable.length < 5) {
    return; //wasn't enter, not > 2 char
  }

  const ruta = base_url + '/quejas/buscarDocPresuntoResponsable';

  $.ajax({
    data: { docPresuntoResponsable },
    url: ruta,
    type: 'post',
    success: function (responseText) {
      $('#ajax-docPresuntoResponsable').html(responseText);
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function seleccionadoQuejoso(documentoPersona) {
  var idQueja = $('#idQuejaAgregarQuejoso').val();

  var ruta = base_url + '/quejas/seleccionadoQuejoso';

  $.ajax({
    data: { idQueja, documentoPersona },
    url: ruta,
    type: 'post',
    beforeSend: function () {
      $('#ajax-listaQuejosos').html(
        "<p style='width:100%; margin-top:20px; text-align:center;'>" +
          loader +
          '</p>'
      );
    },
    success: function (responseText) {
      playAudio('alert');
      quejososQueja(idQueja);
      $('#ajax-docQuejoso').html('');
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function asignarProceso(idAbogado) {
  //Pregunta
  Swal.fire({
    title: 'Confirmar reparto al abogado?',
    text: 'Al hacer clic se asignará el proceso al abogado seleccionado.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, asignar proceso',
    cancelButtonText: 'Cancelar',
  }).then((result) => {
    if (result.isConfirmed) {
      confirmarAsignarProceso(idAbogado);
    }
  });
}

function confirmarAsignarProceso(idAbogado) {
  var idRadicado = $('#idRadicado').val();
  var vigencia = $('#vigencia').val();

  var ruta = base_url + '/procesos/asignar-proceso';

  $.ajax({
    data: { idAbogado, idRadicado, vigencia },
    url: ruta,
    type: 'post',
    success: function (responseText) {
      alertify.success('El proceso se asignó correctamente a ' + responseText);
      setTimeout(function () {
        const rutaRedirect = base_url + '/procesos/reparto-juzgamiento';
        window.location.href = rutaRedirect;
      }, 2000);
      playAudio('alert');
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function seleccionadoPresuntoResponsable(documentoPersona) {
  var idQueja = $('#idQuejaAgregarPresuntoResponsable').val();

  var ruta = base_url + '/quejas/seleccionadoPresuntoResponsable';

  $.ajax({
    data: { idQueja, documentoPersona },
    url: ruta,
    type: 'post',
    beforeSend: function () {
      $('#ajax-listaPresuntosResponsables').html(
        "<p style='width:100%; margin-top:20px; text-align:center;'>" +
          loader +
          '</p>'
      );
    },
    success: function (responseText) {
      playAudio('alert');
      presuntosResponsablesQueja(idQueja);
      $('#ajax-docPresuntoResponsable').html('');
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function quejososQueja(idQueja, fijarOficio = 0) {
  const ruta = base_url + '/quejas/quejososQueja';

  $.ajax({
    data: { idQueja, fijarOficio },
    url: ruta,
    type: 'post',
    beforeSend: function () {
      $('.ajax-listaQuejosos_1_' + idQueja).html(
        "<p style='width:100%; margin-top:20px; text-align:center;'>" +
          loader +
          '</p>'
      );
      $('.ajax-listaQuejosos_2_' + idQueja).html(
        "<p style='width:100%; margin-top:20px; text-align:center;'>" +
          loader +
          '</p>'
      );
      $('.ajax-listaQuejosos_3_' + idQueja).html(
        "<p style='width:100%; margin-top:20px; text-align:center;'>" +
          loader +
          '</p>'
      );
      $('.ajax-listaQuejosos_4_' + idQueja).html(
        "<p style='width:100%; margin-top:20px; text-align:center;'>" +
          loader +
          '</p>'
      );
    },
    success: function (responseText) {
      var arrayJS = JSON.parse(JSON.stringify(responseText));
      /*
                ajax-listaQuejosos_1_ : en ajaxAgregarQuejoso
                ajax-listaQuejosos_2_ : en quejasEnviar
                ajax-listaQuejosos_3_ : en ajaxVerQueja
            */
      $('.ajax-listaQuejosos_1_' + idQueja)
        .hide()
        .html(arrayJS['vista1'])
        .fadeIn(600);
      $('.ajax-listaQuejosos_2_' + idQueja)
        .hide()
        .html(arrayJS['vista2'])
        .fadeIn(600);
      $('.ajax-listaQuejosos_3_' + idQueja)
        .hide()
        .html(arrayJS['vista3'])
        .fadeIn(600);
      $('.ajax-listaQuejosos_4_' + idQueja)
        .hide()
        .html(arrayJS['vista4'])
        .fadeIn(600);
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function presuntosResponsablesQueja(idQueja, fijarOficio = 0) {
  const ruta = base_url + '/quejas/presuntosResponsablesQueja';

  $.ajax({
    data: { idQueja, fijarOficio },
    url: ruta,
    type: 'post',
    beforeSend: function () {
      $('.ajax-listaPresuntosResponsables_1_' + idQueja).html(
        "<p style='width:100%; margin-top:20px; text-align:center;'>" +
          loader +
          '</p>'
      );
      $('.ajax-listaPresuntosResponsables_2_' + idQueja).html(
        "<p style='width:100%; margin-top:20px; text-align:center;'>" +
          loader +
          '</p>'
      );
      $('.ajax-listaPresuntosResponsables_3_' + idQueja).html(
        "<p style='width:100%; margin-top:20px; text-align:center;'>" +
          loader +
          '</p>'
      );
      $('.ajax-listaPresuntosResponsables_4_' + idQueja).html(
        "<p style='width:100%; margin-top:20px; text-align:center;'>" +
          loader +
          '</p>'
      );
    },
    success: function (responseText) {
      var arrayJS = JSON.parse(JSON.stringify(responseText));
      /*
                ajax-listaPresuntosResponsables_1_ : en ajaxAgregarPresuntoResponsable
                ajax-listaPresuntosResponsables_2_ : en quejasEnviar
                ajax-listaPresuntosResponsables_3_ : en ajaxVerQueja
            */
      $('.ajax-listaPresuntosResponsables_1_' + idQueja)
        .hide()
        .html(arrayJS['vista1'])
        .fadeIn(600);
      $('.ajax-listaPresuntosResponsables_2_' + idQueja)
        .hide()
        .html(arrayJS['vista2'])
        .fadeIn(600);
      $('.ajax-listaPresuntosResponsables_3_' + idQueja)
        .hide()
        .html(arrayJS['vista3'])
        .fadeIn(600);
      $('.ajax-listaPresuntosResponsables_4_' + idQueja)
        .hide()
        .html(arrayJS['vista4'])
        .fadeIn(600);
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function verQuejoso(documentoPersona) {
  const ruta = base_url + '/quejas/verQuejoso';

  $.ajax({
    data: { documentoPersona },
    url: ruta,
    type: 'post',
    beforeSend: function () {
      $('#ajax-docQuejoso').html(
        '<p style="margin-top:10px; width:100%; text-align:center;">' +
          loader +
          '</p>'
      );
    },
    success: function (responseText) {
      $('#ajax-docQuejoso').hide().html(responseText).fadeIn(600);
      $('.js-example-basic-single').select2();
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function verPresuntoResponsable(documentoPersona) {
  const ruta = base_url + '/quejas/verPresuntoResponsable';

  $.ajax({
    data: { documentoPersona },
    url: ruta,
    type: 'post',
    beforeSend: function () {
      $('#ajax-docPresuntoResponsable').html(
        '<p style="margin-top:10px; width:100%; text-align:center;">' +
          loader +
          '</p>'
      );
    },
    success: function (responseText) {
      $('#ajax-docPresuntoResponsable').hide().html(responseText).fadeIn(600);
      $('.js-example-basic-single').select2();
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function modificarQuejoso(documentoPersona, nuevo) {
  //Si es un quejoso nuevo
  if (nuevo === 1) {
    documentoPersona = $('#documentoPersona').val();
  }

  const quejoso = $('#quejoso').val();
  const idQueja = $('#idQuejaAgregarQuejoso').val();
  const nombre = $('#nombre').val();
  const documentoPersonaField = $('#documentoPersona').val();
  const direccionCorrespondencia = $('#direccionCorrespondencia').val();
  var ciudadCorrespondencia = $('#ciudadCorrespondencia').val();
  const telefono = $('#telefono').val();
  const telefono2 = $('#telefono2').val();
  const email = $('#email').val();

  if ($('#chk-documentoPersona').prop('checked')) {
    if (documentoPersonaField == '') {
      playAudio('fail');
      alertify.error(
        'Ingrese el documento del quejoso.  Si no lo conoce, marque el selector para determinarlo despúes'
      );
      $('#documentoPersona').focus();
      return;
    }

    if (documentoPersonaField.length < 6) {
      playAudio('fail');
      alertify.error('El documento del quejoso es muy corto');
      $('#documentoPersona').focus();
      return;
    }
  }

  //El nombre es obligatorio
  if (nombre == '') {
    playAudio('fail');
    alertify.error('El nombre del quejoso es obligatorio');
    $('#nombre').focus();
    return;
  }

  if (nombre.length < 10) {
    playAudio('fail');
    alertify.error('El nombre del quejoso es muy corto');
    $('#nombre').focus();
    return;
  }

  if ($('#chk-direccionCorrespondencia').prop('checked')) {
    if (direccionCorrespondencia == '') {
      playAudio('fail');
      alertify.error(
        'Ingrese la dirección.  Si no la conoce, marque el selector para determinarla despúes'
      );
      $('#direccionCorrespondencia').focus();
      return;
    }
  }

  if ($('#chk-ciudadCorrespondencia').prop('checked')) {
    if (ciudadCorrespondencia == '') {
      playAudio('fail');
      alertify.error(
        'Seleccione la ciudad.  Si no la conoce, marque el selector para determinarla despúes'
      );
      $('#ciudadCorrespondencia').focus();
      return;
    }
  } else {
    ciudadCorrespondencia = null;
  }

  if ($('#chk-telefono').prop('checked')) {
    if (telefono == '') {
      playAudio('fail');
      alertify.error(
        'Ingrese el teléfono.  Si no lo conoce, marque el selector para determinarlo despúes'
      );
      $('#telefono').focus();
      return;
    }
  }

  if ($('#chk-telefono2').prop('checked')) {
    if (telefono2 == '') {
      playAudio('fail');
      alertify.error(
        'Ingrese el celular.  Si no lo conoce, marque el selector para determinarlo despúes'
      );
      $('#telefono2').focus();
      return;
    }
  }

  if ($('#chk-email').prop('checked')) {
    if (email == '') {
      playAudio('fail');
      alertify.error(
        'Ingrese el correo electrónico.  Si no lo conoce, marque el selector para determinarlo despúes'
      );
      $('#email').focus();
      return;
    }
  }

  const ruta = base_url + '/quejas/modificarQuejoso';
  console.log(
    quejoso,
    idQueja,
    documentoPersona,
    documentoPersonaField,
    nombre,
    direccionCorrespondencia,
    ciudadCorrespondencia,
    telefono,
    telefono2,
    email,
    nuevo
  );
  $.ajax({
    data: {
      quejoso,
      idQueja,
      documentoPersona,
      documentoPersonaField,
      nombre,
      direccionCorrespondencia,
      ciudadCorrespondencia,
      telefono,
      telefono2,
      email,
      nuevo,
    },
    url: ruta,
    type: 'post',
    success: function (responseText) {
      //Formato json para los datos recibidos
      var arrayJS = JSON.parse(JSON.stringify(responseText));

      if (arrayJS['error'] == 1) {
        playAudio('fail');
        alertify.error(arrayJS['mensaje']);
        return;
      }

      quejososQueja(idQueja);
      $('#ajax-docQuejoso').hide().html('').fadeIn(600);

      alertify.success(arrayJS['mensaje']);
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function modificarPresuntoResponsable(documentoPersona, nuevo) {
  //Si es un presunto responsable nuevo
  if (nuevo === 1) {
    documentoPersona = $('#documentoPersona').val();
  }
  const documentoPersonaField = $('#documentoPersona').val();
  const presuntoResponsable = $('#presuntoResponsable').val();
  const idQueja = $('#idQuejaAgregarPresuntoResponsable').val();
  const nombre = $('#nombre').val();
  const direccionCorrespondencia = $('#direccionCorrespondencia').val();
  const ciudadCorrespondencia = $('#ciudadCorrespondencia').val();
  const telefono = $('#telefono').val();
  const telefono2 = $('#telefono2').val();
  const email = $('#email').val();
  const dependencia = $('#dependencia').val();
  const cargo = $('#cargo').val();

  if ($('#chk-documentoPersona').prop('checked')) {
    if (documentoPersona == '') {
      playAudio('fail');
      alertify.error(
        'Ingrese el documento del presunto responsable.  Si no lo conoce, marque el selector para determinarlo despúes'
      );
      $('#documentoPersona').focus();
      return;
    }

    if (documentoPersona.length < 6) {
      playAudio('fail');
      alertify.error('El documento del presunto responsable es muy corto');
      $('#documentoPersona').focus();
      return;
    }
  }

  //El nombre es obligatorio
  if (nombre == '') {
    playAudio('fail');
    alertify.error('El nombre del presunto responsable es obligatorio');
    $('#nombre').focus();
    return;
  }

  if (nombre.length < 10) {
    playAudio('fail');
    alertify.error('El nombre del presunto responsable es muy corto');
    $('#nombre').focus();
    return;
  }

  if ($('#chk-direccionCorrespondencia').prop('checked')) {
    if (direccionCorrespondencia == '') {
      playAudio('fail');
      alertify.error(
        'Ingrese la dirección del presunto responsable.  Si no la conoce, marque el selector para determinarla despúes'
      );
      $('#direccionCorrespondencia').focus();
      return;
    }
  }

  if ($('#chk-ciudadCorrespondencia').prop('checked')) {
    if (ciudadCorrespondencia == '') {
      playAudio('fail');
      alertify.error(
        'Seleccione la ciudad del presunto responsable.  Si no la conoce, marque el selector para determinarla despúes'
      );
      $('#ciudadCorrespondencia').focus();
      return;
    }
  }

  if ($('#chk-telefono').prop('checked')) {
    if (telefono == '') {
      playAudio('fail');
      alertify.error(
        'Ingrese el teléfono del presunto responsable.  Si no lo conoce, marque el selector para determinarlo despúes'
      );
      $('#telefono').focus();
      return;
    }
  }

  if ($('#chk-telefono2').prop('checked')) {
    if (telefono2 == '') {
      playAudio('fail');
      alertify.error(
        'Ingrese el celular del presunto responsable.  Si no lo conoce, marque el selector para determinarlo despúes'
      );
      $('#telefono2').focus();
      return;
    }
  }

  if ($('#chk-email').prop('checked')) {
    if (email == '') {
      playAudio('fail');
      alertify.error(
        'Ingrese el correo electrónico del presunto responsable.  Si no lo conoce, marque el selector para determinarlo despúes'
      );
      $('#email').focus();
      return;
    }
  }

  if ($('#chk-dependencia').prop('checked')) {
    if (dependencia == '') {
      playAudio('fail');
      alertify.error(
        'Seleccione la dependencia del presunto responsable.  Si no la conoce, marque el selector para determinarla despúes'
      );
      $('#dependencia').focus();
      return;
    }
  }

  if ($('#chk-cargo').prop('checked')) {
    if (cargo == '') {
      playAudio('fail');
      alertify.error(
        'Seleccione el cargo del presunto responsable.  Si no lo conoce, marque el selector para determinarlo despúes'
      );
      $('#cargo').focus();
      return;
    }
  }

  const data2 = {
    presuntoResponsable,
    documentoPersonaField,
    idQueja,
    documentoPersona,
    nombre,
    direccionCorrespondencia,
    ciudadCorrespondencia,
    telefono,
    telefono2,
    email,
    dependencia,
    cargo,
    nuevo,
  };

  console.log(data2, 'data2');

  const ruta = base_url + '/quejas/modificarPresuntoResponsable';

  $.ajax({
    data: {
      presuntoResponsable,
      idQueja,
      documentoPersona,
      documentoPersonaField,
      nombre,
      direccionCorrespondencia,
      ciudadCorrespondencia,
      telefono,
      telefono2,
      email,
      dependencia,
      cargo,
      nuevo,
    },
    url: ruta,
    type: 'post',
    beforeSend: function () {
      $('#ajax-docQuejoso').html(
        '<p style="margin-top:10px; width:100%; text-align:center;">' +
          loader +
          '</p>'
      );
    },
    success: function (responseText) {
      //Formato json para los datos recibidos
      var arrayJS = JSON.parse(JSON.stringify(responseText));

      if (arrayJS['error'] == 1) {
        playAudio('fail');
        alertify.error(arrayJS['mensaje']);
        return;
      }

      presuntosResponsablesQueja(idQueja);
      $('#ajax-docPresuntoResponsable').hide().html('').fadeIn(600);
      alertify.success(arrayJS['mensaje']);
    },
    error: function (responseText) {
      error(responseText);
    },
  });
}

function quitarQuejoso(documentoPersona) {
  alertify.confirm(
    '<b>Quitar Quejoso</b>',
    'Se quitará el quejoso de esta queja.  Está seguro?',
    function () {
      var idQueja = $('#idQuejaAgregarQuejoso').val();

      const ruta = base_url + '/quejas/quitarQuejoso';

      $.ajax({
        data: {
          documentoPersona,
          idQueja,
        },
        url: ruta,
        type: 'post',
        beforeSend: function () {
          $('#ajax-docQuejoso').html(
            '<p style="margin-top:10px; width:100%; text-align:center;">' +
              loader +
              '</p>'
          );
        },
        success: function (responseText) {
          quejososQueja(idQueja);
          $('#ajax-docQuejoso').hide().html('').fadeIn(600);
          alertify.success('Se quitó el quejoso correctamente');
        },
        error: function (responseText) {
          errorAjax(responseText);
        },
      });
    },
    function () {
      alertify.closeAll();
      return false;
    }
  );
}

function quitarPresuntoResponsable(documentoPersona) {
  alertify.confirm(
    '<b>Quitar Presunto Responsable</b>',
    'Se quitará el presunto responsable de esta queja.  Está seguro?',
    function () {
      var idQueja = $('#idQuejaAgregarPresuntoResponsable').val();

      const ruta = base_url + '/quejas/quitarPresuntoResponsable';

      $.ajax({
        data: {
          documentoPersona,
          idQueja,
        },
        url: ruta,
        type: 'post',
        beforeSend: function () {
          $('#ajax-docPresuntoResponsable').html(
            '<p style="margin-top:10px; width:100%; text-align:center;">' +
              loader +
              '</p>'
          );
        },
        success: function (responseText) {
          presuntosResponsablesQueja(idQueja);
          $('#ajax-docPresuntoResponsable').hide().html('').fadeIn(600);
          alertify.success('Se quitó el presunto responsable correctamente');
        },
        error: function (responseText) {
          errorAjax(responseText);
        },
      });
    },
    function () {
      alertify.closeAll();
      return false;
    }
  );
}

function nuevoQuejoso() {
  const ruta = base_url + '/quejas/nuevoQuejoso';

  $.ajax({
    data: null,
    url: ruta,
    type: 'post',
    beforeSend: function () {
      $('#ajax-docQuejoso').html(
        '<p style="margin-top:10px; width:100%; text-align:center;">' +
          loader +
          '</p>'
      );
    },
    success: function (responseText) {
      $('#ajax-docQuejoso').hide().html(responseText).fadeIn(600);
      $('.js-example-basic-single').select2();
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function nuevoPresuntoResponsable() {
  const ruta = base_url + '/quejas/nuevoPresuntoResponsable';

  $.ajax({
    data: null,
    url: ruta,
    type: 'post',
    beforeSend: function () {
      $('#ajax-docPresuntoResponsable').html(
        '<p style="margin-top:10px; width:100%; text-align:center;">' +
          loader +
          '</p>'
      );
    },
    success: function (responseText) {
      $('#ajax-docPresuntoResponsable').hide().html(responseText).fadeIn(600);
      $('.js-example-basic-single').select2();
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function mostrarQueja(idQueja) {
  $('#modalVerQueja').modal('show');
  verQueja(idQueja, 0);
}

function verQueja(idQueja, multiples) {
  const ruta = base_url + '/quejas/verQueja';

  $.ajax({
    data: { idQueja, multiples },
    url: ruta,
    type: 'post',
    beforeSend: function () {
      if (multiples == 1) {
        $('#ajax-verQueja_' + idQueja).html(
          '<p style="margin-top:10px; width:100%; text-align:center;">' +
            loader +
            '</p>'
        );
      } else {
        $('#ajax-verQueja').html(
          '<p style="margin-top:10px; width:100%; text-align:center;">' +
            loader +
            '</p>'
        );
      }
    },
    success: function (responseText) {
      if (multiples == 1) {
        $('#ajax-verQueja_' + idQueja)
          .hide()
          .html(responseText)
          .fadeIn(600);
      } else {
        $('#ajax-verQueja').hide().html(responseText).fadeIn(600);
      }
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function caratula(idQueja) {
  const rutaRedirect = base_url + '/quejas/caratula';
  window.location.href = rutaRedirect + '/' + idQueja;
}

//Portada
function portada(vigencia, idProceso) {
  const rutaRedirect = base_url + '/procesos/portada';
  window.location.href = rutaRedirect + "/" + vigencia + "/" + idProceso;
}

function editarQueja(idQueja, multiples) {
  const ruta = base_url + '/quejas/editarQueja';

  $.ajax({
    data: { idQueja, multiples },
    url: ruta,
    type: 'post',
    beforeSend: function () {
      if (multiples == 1) {
        $('#ajax-verQueja_' + idQueja).html(
          '<p style="margin-top:10px; width:100%; text-align:center;">' +
            loader +
            '</p>'
        );
      } else {
        $('#ajax-verQueja').html(
          '<p style="margin-top:10px; width:100%; text-align:center;">' +
            loader +
            '</p>'
        );
      }
    },
    success: function (responseText) {
      if (multiples == 1) {
        $('#ajax-verQueja_' + idQueja)
          .hide()
          .html(responseText)
          .fadeIn(600);
      } else {
        $('#ajax-verQueja').hide().html(responseText).fadeIn(600);
      }
      $('.select2').select2();
      //fechaQueja
      $('#fechaQueja').datepicker({
        autoclose: true,
        dateFormat: 'yyyy-mm-dd',
      });

      $('#fechaRecepcion').datepicker({
        autoclose: true,
        dateFormat: 'yyyy-mm-dd',
      });
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function validarEditarQueja(idQueja, multiples) {
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
  } else if (tipoRecepcion == 'default') {
    playAudio('fail');
    alertify.error('Seleccione el tipo de recepción');
    $('#tipoRecepcion').focus();
    return;
  } else if (fechaQueja == '') {
    playAudio('fail');
    alertify.error('Seleccione la fecha de la queja');
    $('#fechaQueja').focus();
    return;
  } else if (fechaRecepcion == '') {
    playAudio('fail');
    alertify.error('Seleccione la fecha de recepción');
    $('#fechaRecepcion').focus();
    return;
  } else if (presuntoLugar == '') {
    playAudio('fail');
    alertify.error('Ingrese el presunto lugar');
    $('#presuntoLugar').focus();
    return;
  } else if (presuntosHechos == '') {
    playAudio('fail');
    alertify.error('Ingrese los presuntos hechos');
    $('#presuntosHechos').focus();
    return;
  } else if (dependenciaQueja == 'default') {
    playAudio('fail');
    alertify.error(
      'Seleccione la dependencia a la que pertence el presunto responsable'
    );
    $('#dependenciaQueja').focus();
    return;
  }

  var ruta = base_url + '/quejas/validarEditarQueja';

  var parametros = {
    idQueja,
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
      verQueja(idQueja, multiples);
      alertify.success('Se modificó correctamente la queja');
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function validarRemitirQueja(origenQueja, idQueja) {
  var idEntidadSeleccionada = $('#idEntidadSeleccionada').val();
  var destinatario = $('#destinatario').val();
  var entidad = $('#entidad').val();
  var direccion = $('#direccion').val();
  var oficio = $('#oficio').val();
  var motivo = $('#motivo').val();
  var tipoRemision = $('#tipoRemision').val();

  if (destinatario == '') {
    playAudio('fail');
    alertify.error('Ingrese el nombre del destinatario');
    $('#destinatario').focus();
    return;
  } else if (direccion == '') {
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

  if (oficio == '') {
    playAudio('fail');
    alertify.error('Ingrese el número de oficio que se remite');
    $('#oficio').focus();
    return;
  } else if (motivo == '') {
    playAudio('fail');
    alertify.error('Ingrese el motivo de la remisión de la queja');
    $('#motivo').focus();
    return;
  }

  var parametros = {
    origenQueja: origenQueja,
    idQueja: idQueja,
    destinatario: destinatario.replace(/(\\|\/)+/gi, '-'),
    entidad: entidad.replace(/(\\|\/)+/gi, '-'),
    direccion: direccion.replace(/(\\|\/)+/gi, '-'),
    ciudad: ciudad,
    oficio: oficio.replace(/(\\|\/)+/gi, '-'),
    motivo: motivo.replace(/(\\|\/)+/gi, '-'),
    tipoRemision: tipoRemision,
    idEntidadSeleccionada: idEntidadSeleccionada,
  };

  var vector = JSON.stringify(parametros);

  var rutaRedirect = base_url + '/procesos/guardarOficioRemision';

  window.location.href = rutaRedirect + '/' + vector;

  var rutaRedirect = base_url + '/index';

  setTimeout(function () {
    window.location.href = rutaRedirect;
  }, 2000);
  playAudio('alert');

  var ruta2 = base_url + '/inicio';
  window.location.href = ruta2;
}

function validarAcumularQueja(idQueja) {
  var proceso = $('#proceso').val();
  var motivo = $('#motivo').val();

  if (proceso == '') {
    playAudio('fail');
    alertify.error('Seleccione un proceso');
    $('#proceso').focus();
    return;
  } else if (motivo == '') {
    playAudio('fail');
    alertify.error('Ingrese el motivo');
    $('#motivo').focus();
    return;
  }

  var ruta = base_url + '/quejas/guardarAcumularQueja';

  var parametros = {
    idQueja: idQueja,
    proceso: proceso,
    motivo: motivo,
  };

  $.ajax({
    data: parametros,
    url: ruta,
    type: 'post',
    success: function (responseText) {
      var ruta2 = base_url + '/inicio';
      window.location.href = ruta2;
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function buscarRadicadoPlantillas() {
  var radicado = $('#radicado').val();
  //Si no está completo el campo o éste está vacío
  if (radicado.includes('_') || radicado.length == 0) {
    alertify.error('Ingrese el número de radicado del proceso');
    return;
  }

  const contenedor = 'ajax-buscar';
  const ruta = base_url + '/procesos/buscar-radicado-plantillas';

  $.ajax({
    data: { radicado },
    url: ruta,
    type: 'post',
    beforeSend: function () {
      beforeAjax(contenedor);
    },
    success: function (responseText) {
      $('#' + contenedor).html(responseText);
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function beforeAjax(contenedor) {
  $('#' + contenedor).html(
    '<p style="margin-top:10px; width:100%; text-align:center;">' +
      loader +
      '</p>'
  );
}

function cargarPlantillas(idTipoPlantilla, idEtapa, vigencia, idRadicado) {
  const contenedor = 'resultadoPlantillas';
  const ruta = base_url + '/procesos/cargarPlantillas';

  $.ajax({
    data: { idTipoPlantilla, idEtapa, vigencia, idRadicado },
    url: ruta,
    type: 'post',
    beforeSend: function () {
      beforeAjax(contenedor);
    },
    success: function (responseText) {
      $('#' + contenedor).html(responseText);
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

//Genera oficio general
function oficioGeneral() {
  //lanza la ventana modal
  $('#modalOficioGeneral').modal('show');

  const contenedor = 'resultadoOficioGeneral';
  const ruta = base_url + '/procesos/cargarOficioGeneral';

  $.ajax({
    data: null,
    url: ruta,
    type: 'post',
    beforeSend: function (responseText) {
      beforeAjax(contenedor);
    },
    success: function (responseText) {
      $('#' + contenedor).html(responseText);
      //Initialize Select2 Elements
      $('.select2').select2();
      $('#destinatario').focus();
      //Tabla personas
      $('#tablaPersonas').DataTable();
      //Tabla entidades
      $('#tablaEntidades').DataTable();

      //Suelta tecla
      $('#docPersona').keyup(function (e) {
        clearTimeout($.data(this, 'timer'));
        if (e.keyCode == 32)
          //32 espacio
          buscarDocPersona(true, 0);
        else $(this).data('timer', setTimeout(buscarDocPersona, 500));
      });
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function plantilla(idPlantilla, idTipoPlantilla, vigencia, idRadicado) {
  const contenedor = 'resultadoOficios';

  //1 Citaciones, 2 Comunicaciones, 3 Solicitudes
  if (idTipoPlantilla == 1 || idTipoPlantilla == 2 || idTipoPlantilla == 3) {
    //lanza la ventana modal
    $('#modalGenerarOficio').modal('show');

    const ruta = base_url + '/procesos/cargarGenOficio';

    $.ajax({
      data: { idPlantilla, idTipoPlantilla, vigencia, idRadicado },
      url: ruta,
      type: 'post',
      beforeSend: function () {
        beforeAjax(contenedor);
      },
      success: function (responseText) {
        $('#' + contenedor).html(responseText);
        //Initialize Select2 Elements
        $('.select2').select2();
        $('#destinatario').focus();
        //Tabla entidades
        $('#tablaEntidades').DataTable();

        //Suelta tecla
        $('#docPersona').keyup(function (e) {
          clearTimeout($.data(this, 'timer'));
          if (e.keyCode == 32)
            //32 espacio
            buscarDocPersona(true, 0);
          else $(this).data('timer', setTimeout(buscarDocPersona, 500));
        });
      },
      error: function (responseText) {
        errorAjax(responseText);
      },
    });
  } else {
    const rutaRedirect = base_url + '/procesos/plantilla';
    window.location.href =
      rutaRedirect +
      '/' +
      vigencia +
      '/' +
      idRadicado +
      '/' +
      idPlantilla +
      '/' +
      idTipoPlantilla;
  }
}

function fijarDestinatarioEnt(idComReg) {
  const contenedor = 'resultadoDestino';
  const ruta = base_url + '/procesos/fijarDestinatarioEnt';

  $.ajax({
    data: { idComReg },
    url: ruta,
    type: 'post',
    beforeSend: function () {
      beforeAjax(contenedor);
    },
    success: function (responseText) {
      $('#' + contenedor).html(responseText);
      //Initialize Select2 Elements
      $('.select2').select2();
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function validarGenerarOficio(
  idPlantilla,
  idTipoPlantilla,
  vigencia,
  idRadicado
) {
  var destinatario = $('#destinatario').val();
  var entidad = $('#entidad').val();
  var direccion = $('#direccion').val();
  var asunto = $('#asunto').val();

  if (destinatario == '') {
    playAudio('fail');
    alertify.error('Ingrese el nombre del destinatario');
    $('#destinatario').focus();
    return;
  } else if (direccion == '') {
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

  if (asunto == '') {
    playAudio('fail');
    alertify.error('Ingrese el asunto del oficio');
    $('#asunto').focus();
    return;
  }

  asuntoOficio = 'PROCESO ' + vigencia + '-' + idRadicado + '. ' + asunto;

  var parametros = {
    vigencia: vigencia,
    idRadicado: idRadicado,
    destinatario: destinatario.replace(/(\\|\/)+/gi, '-'),
    entidad: entidad.replace(/(\\|\/)+/gi, '-'),
    direccion: direccion.replace(/(\\|\/)+/gi, '-'),
    ciudad: ciudad,
    asunto: asuntoOficio.replace(/(\\|\/)+/gi, '-'),
    idPlantilla: idPlantilla,
    idTipoPlantilla: idTipoPlantilla,
  };

  var vector = JSON.stringify(parametros);

  const rutaRedirect = base_url + '/procesos/guardarOficio';
  window.location.href = rutaRedirect + '/' + vector;

  //Cierra la ventana modal
  $('#modalGenerarOficio').modal('hide');

  //Llama al método de carga de plantilla
  //setTimeout(function(){$('#modalGenerarOficio').modal('hide');plantilla(idPlantilla, idTipoPlantilla);},2000); // 2000ms = 2s
}

/*
var inputRadicado = document.getElementById("radicado");
inputRadicado.addEventListener("keydown", function (e) {
  if (e.code === "Enter") {
    buscarRadicadoPlantillas();
  }
});
*/

function editarPersona(documentoPersona, idQueja = 0) {
  $('#modalEditarPersona').modal('show');

  const contenedor = 'ajax-editarPersona';
  const ruta = base_url + '/quejas/editarPersona';

  $.ajax({
    data: { documentoPersona, idQueja },
    url: ruta,
    type: 'post',
    beforeSend: function () {
      beforeAjax(contenedor);
    },
    success: function (responseText) {
      $('#' + contenedor).html(responseText);
      $('.js-example-basic-single').select2();
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function modificarPersona(documentoPersona, idQueja) {
  const persona = $('#persona').val();
  const nombre = $('#nombre').val();
  const documentoPersonaField = $('#documentoPersona').val();
  const direccionCorrespondencia = $('#direccionCorrespondencia').val();
  var ciudadCorrespondencia = $('#ciudadCorrespondencia').val();
  const telefono = $('#telefono').val();
  const telefono2 = $('#telefono2').val();
  const email = $('#email').val();

  if ($('#chk-documentoPersona').prop('checked')) {
    if (documentoPersonaField == '') {
      playAudio('fail');
      alertify.error(
        'Ingrese el documento del quejoso.  Si no lo conoce, marque el selector para determinarlo despúes'
      );
      $('#documentoPersona').focus();
      return;
    }

    if (documentoPersonaField.length < 6) {
      playAudio('fail');
      alertify.error('El documento del quejoso es muy corto');
      $('#documentoPersona').focus();
      return;
    }
  }

  //El nombre es obligatorio
  if (nombre == '') {
    playAudio('fail');
    alertify.error('El nombre del quejoso es obligatorio');
    $('#nombre').focus();
    return;
  }

  if (nombre.length < 10) {
    playAudio('fail');
    alertify.error('El nombre del quejoso es muy corto');
    $('#nombre').focus();
    return;
  }

  if ($('#chk-direccionCorrespondencia').prop('checked')) {
    if (direccionCorrespondencia == '') {
      playAudio('fail');
      alertify.error(
        'Ingrese la dirección.  Si no la conoce, marque el selector para determinarla despúes'
      );
      $('#direccionCorrespondencia').focus();
      return;
    }
  }

  if ($('#chk-ciudadCorrespondencia').prop('checked')) {
    if (ciudadCorrespondencia == '') {
      playAudio('fail');
      alertify.error(
        'Seleccione la ciudad.  Si no la conoce, marque el selector para determinarla despúes'
      );
      $('#ciudadCorrespondencia').focus();
      return;
    }
  } else {
    ciudadCorrespondencia = null;
  }

  if ($('#chk-telefono').prop('checked')) {
    if (telefono == '') {
      playAudio('fail');
      alertify.error(
        'Ingrese el teléfono.  Si no lo conoce, marque el selector para determinarlo despúes'
      );
      $('#telefono').focus();
      return;
    }
  }

  if ($('#chk-telefono2').prop('checked')) {
    if (telefono2 == '') {
      playAudio('fail');
      alertify.error(
        'Ingrese el celular.  Si no lo conoce, marque el selector para determinarlo despúes'
      );
      $('#telefono2').focus();
      return;
    }
  }

  if ($('#chk-email').prop('checked')) {
    if (email == '') {
      playAudio('fail');
      alertify.error(
        'Ingrese el correo electrónico.  Si no lo conoce, marque el selector para determinarlo despúes'
      );
      $('#email').focus();
      return;
    }
  }

  const ruta = base_url + '/quejas/modificarPersona';

  $.ajax({
    data: {
      persona,
      documentoPersona,
      documentoPersonaField,
      nombre,
      direccionCorrespondencia,
      ciudadCorrespondencia,
      telefono,
      telefono2,
      email,
    },
    url: ruta,
    type: 'post',
    success: function (responseText) {
      //Formato json para los datos recibidos
      var arrayJS = JSON.parse(JSON.stringify(responseText));

      if (arrayJS['error'] == 1) {
        playAudio('fail');
        alertify.error(arrayJS['mensaje']);
        return;
      }

      alertify.success(arrayJS['mensaje']);
      if (idQueja != 0) {
        presuntosResponsablesQueja(idQueja, 1);
        quejososQueja(idQueja, 1);
        console.log('idQueja mayor', idQueja);
      } else {
        console.log('idQueja igual a cero', idQueja);
      }
      buscarDocPersona(true, 0);
      $('#modalEditarPersona').modal('hide');
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function contenedorBuscar() {
  $('#contenedorBuscar').css('display', 'block');
  $('#radicado').focus();
}

function fijarDestinatario(documentoPersona) {
  const contenedor = 'resultadoDestino';
  const ruta = base_url + '/procesos/fijarDestinatario';

  $.ajax({
    data: { documentoPersona },
    url: ruta,
    type: 'post',
    beforeSend: function () {
      beforeAjax(contenedor);
    },
    success: function (responseText) {
      $('#' + contenedor).html(responseText);
      //Initialize Select2 Elements
      $('.select2').select2();
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function eliminarSolicitud(idSolicitudAuto) {
  const contenedor = 'resultadoAutos';
  const ruta = base_url + '/procesos/eliminarSolicitud';

  //Pregunta
  Swal.fire({
    title: 'Eliminar solicitud de Número de Auto?',
    text: 'Al hacer clic se eliminará la solicitud de número de auto.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, eliminar solicitud',
    cancelButtonText: 'Cancelar',
  }).then((result) => {
    if (result.isConfirmed) {
      //Confirmar
      $.ajax({
        data: { idSolicitudAuto },
        url: ruta,
        type: 'post',
        success: function (responseText) {
          $('#' + contenedor).html(responseText);
        },
        error: function (responseText) {
          errorAjax(responseText);
        },
      });
      //---------
    }
  });
}

function traerFase(fase, vigencia, idRadicado, actuacion) {
  var contenedor = 'ajax-fase';

  const ruta = base_url + '/procesos/traer-fase';

  $.ajax({
    data: { fase, vigencia, idRadicado, actuacion },
    url: ruta,
    type: 'post',
    beforeSend: function () {
      beforeAjax(contenedor);
    },
    success: function (responseText) {
      $('#' + contenedor).html(responseText);

      $('.select2')
        .prepend('<option selected=""></option>')
        .select2({ placeholder: 'Select Month' });
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function verExpediente(vigencia, idRadicado) {
  const idEtapa = $('#etapaExpediente').val();

  var contenedor = 'resultadoExpediente';
  const ruta = base_url + '/procesos/verExpediente';

  $.ajax({
    data: {
      idEtapa,
      vigencia,
      idRadicado,
    },
    url: ruta,
    type: 'post',
    beforeSend: function () {
      beforeAjax(contenedor);
    },
    success: function (responseText) {
      $('#' + contenedor).html(responseText);
      $('#tablaExpediente').DataTable();
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function cargarAbogadosReparto() {
  const ruta = base_url + '/procesos/cargar-abogados-reparto';

  $('#modalReparto').modal('show');

  $.ajax({
    data: {},
    url: ruta,
    type: 'get',
    success: function (responseText) {
      $('#ajax-reparto').hide().html(responseText).fadeIn(600);
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}

function cargarProcesosActivosEtapa(idEtapa) {
  const ruta = base_url + '/procesos/cargar-procesos-activos-etapa';
  $.ajax({
    data: { idEtapa },
    url: ruta,
    type: 'get',
    success: function (responseText) {
      $('#ajax-procesos').hide().html(responseText).fadeIn(600);
    },
    error: function (responseText) {
      errorAjax(responseText);
    },
  });
}
