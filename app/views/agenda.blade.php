<?php 
$idUsuario = Session::get('documentoUsuario');
?>
<html lang="en" class="no-js">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
  <title>.: Agenda SUID :.</title>
  <link rel="icon" href="{{ asset('favicon.png') }}">
  <link rel="icon" href="{{ asset('favicon.ico')}}">   
  <link href="{{asset('css/agenda/dailog.css')}}" rel="stylesheet" type="text/css"/>
  <link href="{{asset('css/agenda/calendar.css')}}" rel="stylesheet" type="text/css"/> 
  <link href="{{asset('css/agenda/dp.css')}}" rel="stylesheet" type="text/css"/>   
  <link href="{{asset('css/agenda/alert.css')}}" rel="stylesheet" type="text/css"/> 
  <link href="{{asset('css/agenda/main.css')}}" rel="stylesheet" type="text/css"/> 
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('font-awesome/css/font-awesome.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{asset('css/ionicons.min.css')}}">

  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('css/AdminLTE.css?v=2')}}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
  folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{asset('css/skins/_all-skins.css')}}">

 
  <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" type="text/css" media="all"/>
  <style>
  .label-rdo{
  margin-top: 2px !important;
  -moz-box-shadow:inset 0px 1px 0px 0px #ffffff;
  -webkit-box-shadow:inset 0px 1px 0px 0px #ffffff;
  box-shadow:inset 0px 1px 0px 0px #ffffff;
  background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #ffffff), color-stop(1, #f6f6f6));
  background:-moz-linear-gradient(top, #ffffff 5%, #f6f6f6 100%);
  background:-webkit-linear-gradient(top, #ffffff 5%, #f6f6f6 100%);
  background:-o-linear-gradient(top, #ffffff 5%, #f6f6f6 100%);
  background:-ms-linear-gradient(top, #ffffff 5%, #f6f6f6 100%);
  background:linear-gradient(to bottom, #ffffff 5%, #f6f6f6 100%);
  filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#f6f6f6',GradientType=0);
  background-color:#ffffff;
  -moz-border-radius:6px;
  -webkit-border-radius:6px;
  border-radius:6px;
  border:1px solid #dcdcdc;
  display:inline-block;
  color:#666666;
  font-size:1em;
  font-weight:bold;
  padding:1px 6px;
  text-decoration:none;
  text-shadow:0px 1px 0px #ffffff;
  }
  </style>
</head>
<body class="hold-transition skin-blue fixed sidebar-mini" style="padding-top: 51px !important;">

<!-- Site wrapper -->
<div class="wrapper">
<!-- HEADER -->
    <header class="main-header">
      <!-- Logo -->
      <a href="{{asset('/inicio')}}" class="logo" style="padding: 1px 0 0 0;">
        
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img src="{{ asset('img\SUID_transp2.png') }}" height="46"></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><img src="{{ asset('img\SUID_blancoWeb.png') }}" height="46" id="logo"></span>
      </a>
      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top">      
      </nav>
    </header>
    <!-- # HEADER -->

<!-- MODAL -->
<div id="modal" aria-hidden="true" aria-labelledby="modalTitle" aria-describedby="modalDescription" role="dialog">
  <div role="document">
    <!-- resultadoAgenda -->
    <div id="resultadoAgenda" style="min-height:300px;">
      <!-- CARGA AJAX -->
    </div>
    <!-- #resultadoAgenda -->
    <button id="modalCloseButton" class="modalCloseButton" title="Cerrar" onclick="recarga();"><img id="cancel" src="{{asset('img/close.png')}}" width="32"></button>
  </div>
</div>

<div id="modalSeleccionarProceso" aria-hidden="true" aria-labelledby="modalTitle" aria-describedby="modalDescription" role="dialog">
  <div role="document">
    <!-- resultadoSeleccionarProceso -->
    <div id="resultadoSeleccionarProceso">
      <!-- CONTENIDO AJAX --> 
    </div>
    <!-- # resultadoSeleccionarProceso -->
    <button id="modalCloseButton" class="modalCloseButton" title="Cerrar"><img id="cancel" src="{{asset('img/close.png')}}" width="32"></button>
  </div>
</div>

<!-- # MODAL -->

<div id="modalOverlay" tabindex="-1"></div>
  <div>
    <div id="calhead" style="padding-left:1px;padding-right:1px;" style="background:red;margin-top:96px;">
      <div class="cHead">
        <div class="ftitle">Agenda SUID</div>
        <div id="loadingpannel" class="ptogtitle loadicon" style="display: none;">Cargando datos...</div>
        <div id="errorpannel" class="ptogtitle loaderror" style="display: none;">Lo sentimos, no se han podido cargar los datos, por favor intente mas tarde
        </div>
      </div>          

      <div id="caltoolbar" class="ctoolbar">
        <!--
        <div id="faddbtn" class="fbutton">
          <div>
            <span title='Click para crear un nuevo evento' class="addcal">
              Nuevo Evento                
            </span>
          </div>
        </div> -->
        <div class="btnseparator"></div>
        <div id="showtodaybtn" class="fbutton">
          <div>
            <span title='Click to back to today ' class="showtoday">
              Hoy</span>
            </div>
          </div>
          <div class="btnseparator"></div>

          <div id="showdaybtn" class="fbutton">
            <div><span title='Día' class="showdayview">Día</span></div>
          </div>
          <div  id="showweekbtn" class="fbutton fcurrent">
            <div><span title='Semana' class="showweekview">Semana</span></div>
          </div>
          <div  id="showmonthbtn" class="fbutton">
            <div><span title='Mes' class="showmonthview">Mes</span></div>

          </div>
          <div class="btnseparator"></div>
          <div  id="showreflashbtn" class="fbutton">
            <div><span title='Refrescar vista' class="showdayflash">Refrescar</span></div>
          </div>
          <div class="btnseparator"></div>
          <div id="sfprevbtn" title="Prev"  class="fbutton">
            <span class="fprev"></span>

          </div>
          <div id="sfnextbtn" title="Next" class="fbutton">
            <span class="fnext"></span>
          </div>
          <div class="fshowdatep fbutton">
            <div>
              <input type="hidden" name="txtshow" id="hdtxtshow" />
              <span id="txtdatetimeshow">Cargar Fecha..</span>

            </div>
          </div>

          <div class="clear"></div>
        </div>
      </div>
      <div style="padding:1px;">

        <div class="t1 chromeColor">
          &nbsp;
        </div>
        <div class="t2 chromeColor">
          &nbsp;
        </div>
        <div id="dvCalMain" class="calmain printborder">
          <div id="gridcontainer" style="overflow-y: visible;">
          </div>
        </div>
        <div class="t2 chromeColor">

          &nbsp;</div>
          <div class="t1 chromeColor">
            &nbsp;
          </div>   
        </div>
        
</div>

      <script src="{{asset('js/agenda/jquery.js')}}" type="text/javascript"></script>     
      <script src="{{asset('js/agenda/Plugins/Common.js')}}" type="text/javascript"></script>    
      <script src="{{asset('js/agenda/Plugins/datepicker_lang_ES.js')}}" type="text/javascript"></script>     
      <script src="{{asset('js/agenda/Plugins/jquery.datepicker.js')}}" type="text/javascript"></script>
      <script src="{{asset('js/agenda/Plugins/jquery.alert.js')}}" type="text/javascript"></script>    
      <script src="{{asset('js/agenda/Plugins/jquery.ifrmdailog.js')}}" defer="defer" type="text/javascript"></script>
      <script src="{{asset('js/agenda/Plugins/wdCalendar_lang_ES.js')}}" type="text/javascript"></script>    
      <script src="{{asset('js/agenda/Plugins/jquery.calendar.js')}}" type="text/javascript"></script>
      <script type='text/javascript' src="{{ asset('js\plugins\fullcalendar\fullcalendar.js') }}"></script>
      {{ HTML::script('js/ajax.js') }}
      <!-- ALERTIFY -->
      <script src="{{asset('js/alertify.min.js')}}"></script>
      <!-- CSS -->
      <link rel="stylesheet" href="{{asset('css/alertify.min.css')}}"/>
      <!-- Bootstrap theme -->
      <link rel="stylesheet" href="{{asset('css/alertify-bootstrap.min.css')}}"/>
      <!-- # ALERTIFY -->

<script>
function recarga()
{
  $("#gridcontainer").reload();
}

function reset () 
{
  $("#toggleCSS").attr("href", "../themes/alertify.default.css");
  alertify.set({
    labels : {
      ok     : "OK",
      cancel : "Cancel"
    },
    delay : 5000,
    buttonReverse : false,
    buttonFocus   : "ok"
  });
}

// jQuery formatted selector to search for focusable items
var focusableElementsString = "a[href], area[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), iframe, object, embed, *[tabindex], *[contenteditable]";

// store the item that has focus before opening the modal window
var focusedElementBeforeModal;

$(document).ready(function() {
  jQuery('#startModal').click(function(e) {
    showModal($('#modal'));
  });
  jQuery('#cancel').click(function(e) {
    hideModal();
  });
  jQuery('#cancelButton').click(function(e) {
    hideModal();
  });
  jQuery('#enter').click(function(e) {
    enterButtonModal();
  });
  jQuery('#modalCloseButton').click(function(e) {
    hideModal();
  });
  jQuery('#modal').keydown(function(event) {
    trapTabKey($(this), event);
  })
  jQuery('#modal').keydown(function(event) {
    trapEscapeKey($(this), event);
  })

});

function trapEscapeKey(obj, evt) {

    // if escape pressed
    if (evt.which == 27) {

        // get list of all children elements in given object
        var o = obj.find('*');

        // get list of focusable items
        var cancelElement;
        cancelElement = o.filter("#cancel")

        // close the modal window
        cancelElement.click();
        evt.preventDefault();
      }

    }

    function trapTabKey(obj, evt) {

    // if tab or shift-tab pressed
    if (evt.which == 9) {

        // get list of all children elements in given object
        var o = obj.find('*');

        // get list of focusable items
        var focusableItems;
        focusableItems = o.filter(focusableElementsString).filter(':visible')

        // get currently focused item
        var focusedItem;
        focusedItem = jQuery(':focus');

        // get the number of focusable items
        var numberOfFocusableItems;
        numberOfFocusableItems = focusableItems.length

        // get the index of the currently focused item
        var focusedItemIndex;
        focusedItemIndex = focusableItems.index(focusedItem);

        if (evt.shiftKey) {
            //back tab
            // if focused on first item and user preses back-tab, go to the last focusable item
            if (focusedItemIndex == 0) {
              focusableItems.get(numberOfFocusableItems - 1).focus();
              evt.preventDefault();
            }

          } else {
            //forward tab
            // if focused on the last item and user preses tab, go to the first focusable item
            if (focusedItemIndex == numberOfFocusableItems - 1) {
              focusableItems.get(0).focus();
              evt.preventDefault();
            }
          }
        }

      }

      function setInitialFocusModal(obj) {
    // get list of all children elements in given object
    var o = obj.find('*');

    // set focus to first focusable item
    var focusableItems;
    focusableItems = o.filter(focusableElementsString).filter(':visible').first().focus();

  }

  function enterButtonModal() {
    // BEGIN logic for executing the Enter button action for the modal window
    alert('form submitted');
    // END logic for executing the Enter button action for the modal window
    hideModal();
  }

  function setFocusToFirstItemInModal(obj){
    // get list of all children elements in given object
    var o = obj.find('*');

    // set the focus to the first keyboard focusable item
    o.filter(focusableElementsString).filter(':visible').first().focus();
  }

  function showModal(obj) {
    jQuery('#mainPage').attr('aria-hidden', 'true'); // mark the main page as hidden
    jQuery('#modalOverlay').css('display', 'block'); // insert an overlay to prevent clicking and make a visual change to indicate the main apge is not available
    jQuery('#modal').css('display', 'block'); // make the modal window visible
    jQuery('#modal').attr('aria-hidden', 'false'); // mark the modal window as visible

    // attach a listener to redirect the tab to the modal window if the user somehow gets out of the modal window
    jQuery('body').on('focusin','#mainPage',function() {
      setFocusToFirstItemInModal(jQuery('#modal'));
    })

    // save current focus
    focusedElementBeforeModal = jQuery(':focus');

    setFocusToFirstItemInModal(obj);
  }

  function hideModal() {
    jQuery('#modalOverlay').css('display', 'none'); // remove the overlay in order to make the main screen available again
    jQuery('#modal').css('display', 'none'); // hide the modal window
    jQuery('#modal').attr('aria-hidden', 'true'); // mark the modal window as hidden
    jQuery('#mainPage').attr('aria-hidden', 'false'); // mark the main page as visible

    // remove the listener which redirects tab keys in the main content area to the modal
    jQuery('body').off('focusin','#mainPage');

    // set focus back to element that had it before the modal was opened
    focusedElementBeforeModal.focus();
  }

//CALENDAR---------------------------------------------------------
$(document).ready(function() {     
 var idUsuario = "{{$idUsuario}}";
 var view="week";  
 var DATA_FEED_URL = "{{asset('php/datafeed.php')}}";
 var op = {
  view: view,
  theme:9 ,
  showday: new Date(),
  EditCmdhandler:Edit,
  DeleteCmdhandler:Delete,
  ViewCmdhandler:View,    
  onWeekOrMonthToDay:wtd,
  onBeforeRequestData: cal_beforerequest,
  onAfterRequestData: cal_afterrequest,
  onRequestDataError: cal_onerror, 
  autoload:true,
  url: DATA_FEED_URL + "?method=list&idUsuario="+idUsuario,  
  quickAddUrl: DATA_FEED_URL + "?method=add&idUsuario="+idUsuario, 
  quickUpdateUrl: DATA_FEED_URL + "?method=update&idUsuario="+idUsuario,
  quickDeleteUrl: DATA_FEED_URL + "?method=remove&idUsuario="+idUsuario,
  intervalTime: 15 // You must add this option       
};
var $dv = $("#calhead");
var _MH = document.documentElement.clientHeight;
var dvH = $dv.height() + 2;
op.height = _MH - dvH;
op.eventItems =[];

var p = $("#gridcontainer").bcalendar(op).BcalGetOp();
if (p && p.datestrshow) {
  $("#txtdatetimeshow").text(p.datestrshow);
}
$("#caltoolbar").noSelect();

$("#hdtxtshow").datepicker({ picker: "#txtdatetimeshow", showtarget: $("#txtdatetimeshow"),
  onReturn:function(r){                          
    var p = $("#gridcontainer").gotoDate(r).BcalGetOp();
    if (p && p.datestrshow) {
      $("#txtdatetimeshow").text(p.datestrshow);
    }
  } 
});

function cal_beforerequest(type)
{
  var t="Cargando datos...";
  switch(type)
  {
    case 1:
    t="Cargando datos...";
    break;
    case 2:                      
    case 3:  
    case 4:    
    t="Procesando ...";                                   
    break;
  }
  $("#errorpannel").hide();
  $("#loadingpannel").html(t).show();    
}
function cal_afterrequest(type)
{
  switch(type)
  {
    case 1:
    $("#loadingpannel").hide();
    break;
    case 2:
    case 3:
    case 4:
    $("#loadingpannel").html("Guardado!");
    window.setTimeout(function(){ $("#loadingpannel").hide();},2000);
    break;
  }              

}
function cal_onerror(type,data)
{
  $("#errorpannel").show();
}
function Edit(data)
{
  showModal($('#modal'));

  var ruta = "agenda/mostrarEditarTarea";

  var parametros = {    
      "idTarea" : data[0]
  };

  $.ajax({                
      data:  parametros,
      url:   ruta,
      type:  'post',
      success:  function (responseText) 
      { 
        $("#resultadoAgenda").html(responseText);
      },
      error: function(responseText)
      {
        alert("Error.  Contacte al administrador (Cod.Error.458)");
      }
  }); 
}   

function View(data)
{  alert(data[0]);
  var str = "";
  $.each(data, function(i, item){
    str += "[" + i + "]: " + item + "\n";
  });
  alert(str);               
}    

function Delete(data,callback)
{           

  $.alerts.okButton="Sí";  
  $.alerts.cancelButton="Cancelar";  
  hiConfirm("Está seguro de borrar este evento", 'Confirm',function(r){ r && callback(0);});           
}

function wtd(p)
{
 if (p && p.datestrshow) {
  $("#txtdatetimeshow").text(p.datestrshow);
}
$("#caltoolbar div.fcurrent").each(function() {
  $(this).removeClass("fcurrent");
})
$("#showdaybtn").addClass("fcurrent");
}
            //to show day view
            $("#showdaybtn").click(function(e) { 
                //document.location.href="#day";
                $("#caltoolbar div.fcurrent").each(function() {
                  $(this).removeClass("fcurrent");
                })
                $(this).addClass("fcurrent");
                var p = $("#gridcontainer").swtichView("day").BcalGetOp();
                if (p && p.datestrshow) {
                  $("#txtdatetimeshow").text(p.datestrshow);
                }
              });
            //to show week view
            $("#showweekbtn").click(function(e) {
                //document.location.href="#week";
                $("#caltoolbar div.fcurrent").each(function() {
                  $(this).removeClass("fcurrent");
                })
                $(this).addClass("fcurrent");
                var p = $("#gridcontainer").swtichView("week").BcalGetOp();
                if (p && p.datestrshow) {
                  $("#txtdatetimeshow").text(p.datestrshow);
                }

              });
            //to show month view
            $("#showmonthbtn").click(function(e) {
                //document.location.href="#month";
                $("#caltoolbar div.fcurrent").each(function() {
                  $(this).removeClass("fcurrent");
                })
                $(this).addClass("fcurrent");
                var p = $("#gridcontainer").swtichView("month").BcalGetOp();
                if (p && p.datestrshow) {
                  $("#txtdatetimeshow").text(p.datestrshow);
                }
              });
            
            $("#showreflashbtn").click(function(e){
              $("#gridcontainer").reload();
            });
            
            //Add a new event
            /*$("#faddbtn").click(function(e) {
              var url ="edit.php";
              OpenModelWindow(url,{ width: 500, height: 400, caption: "Crear Nuevo Evento"});
            });*/

$("#faddbtn").click(function(e) 
{
  var ruta = "{{URL::to('agenda/showAddEvent/')}}";
  ajax=objetoAjax();
  ajax.open("GET", ruta, true); 

  ajax.onreadystatechange=function()
  {
    if(ajax.readyState==4)
    {
      resultadoAgenda.innerHTML=ajax.responseText;

      jq('.form_datetime').datetimepicker({
          language:  'es',
          weekStart: 1,
          todayBtn:  0,
          autoclose: 1,
          startDate: '{{date("Y-m-d H:i:s")}}',
          todayHighlight: 1,
          startView: 2,
          forceParse: 0,
          showMeridian: 1,
          minuteStep: 30
        });
    }
  }
  ajax.send(null);

  showModal($('#modal'));
});

            //go to today
            $("#showtodaybtn").click(function(e) {
              var p = $("#gridcontainer").gotoDate().BcalGetOp();
              if (p && p.datestrshow) {
                $("#txtdatetimeshow").text(p.datestrshow);
              }


            });
            //previous date range
            $("#sfprevbtn").click(function(e) {
              var p = $("#gridcontainer").previousRange().BcalGetOp();
              if (p && p.datestrshow) {
                $("#txtdatetimeshow").text(p.datestrshow);
              }

            });
            //next date range
            $("#sfnextbtn").click(function(e) {
              var p = $("#gridcontainer").nextRange().BcalGetOp();
              if (p && p.datestrshow) {
                $("#txtdatetimeshow").text(p.datestrshow);
              }
            });
          });

function validarAdicionarEvento()
{ 
  var color = $("input:radio[name ='cor']:checked").val();
  var fechaInicio = $('#fechaInicioAg').val();
  var fechaFinal = $('#fechaFinalAg').val();
  var radProceso = $('#radProceso').val();
  var vigProceso = $('#vigProceso').val();
  var asuntoTarea = $('#asuntoTarea').val();
  var descripcionTarea = $('#descripcionTarea').val();
  var lugarTarea = $('#lugarTarea').val();

  if(radProceso == "default")
  {
     alertify.error("Seleccione uno de sus procesos");
     playAudio('fail');
     $("#radProceso").focus();
     return false;
  }
  if(asuntoTarea == "")
  {
     alertify.error("Ingrese el asunto del evento");
     playAudio('fail');
     $("#asuntoTarea").focus();
     return false;
  }
  else if(descripcionTarea == "")
  {
     alertify.error("Ingrese la descripción");
     playAudio('fail');
     $("#descripcionTarea").focus();
     return false;
  }
  else if(lugarTarea == "")
  {
     alertify.error("Ingrese el lugar");
     playAudio('fail');
     $("#lugarTarea").focus();
     return false;
  }
  
  //Petición Ajax
  var ruta = "{{URL::to('agenda/guardarAgendarTarea/')}}";

  var parametros = {    
    "fechaInicio" : fechaInicio,
    "fechaFinal" : fechaFinal,
    "asuntoTarea" : asuntoTarea,
    "descripcionTarea" : descripcionTarea,
    "lugarTarea" : lugarTarea,
    "radProceso" : radProceso,
    "vigProceso" : vigProceso,
    "color" : color
  };

  $.ajax({                
            data:  parametros,
            url:   ruta,
            type:  'post',
            success:  function (responseText) 
            {
              playAudio('alert');
              alertify.success("Agendado correctamente");
              hideModal();
              $("#gridcontainer").reload();
            },
            error: function(responseText)
            {
              alert("Error.  Contacte al administrador (Cod.Error.calendar/649)");
            }
        }); 
}

function validarEditarEvento()
{ 
  var color = $("input:radio[name ='corEdit']:checked").val();
  var radProceso = $('#radProcesoEdit').val();
  var vigProceso = $('#vigProcesoEdit').val();
  var asuntoTarea = $('#asuntoTareaEdit').val();
  var descripcionTarea = $('#descripcionTareaEdit').val();
  var lugarTarea = $('#lugarTareaEdit').val();
  var idTarea = $('#idTareaEdit').val();

  if(radProceso == "default")
  {
     alertify.error("Seleccione uno de sus procesos");
     playAudio('fail');
     $("#radProcesoEdit").focus();
     return false;
  }
  if(asuntoTarea == "")
  {
     alertify.error("Ingrese el asunto del evento");
     playAudio('fail');
     $("#asuntoTareaEdit").focus();
     return false;
  }
  else if(descripcionTarea == "")
  {
     alertify.error("Ingrese la descripción");
     playAudio('fail');
     $("#descripcionTareaEdit").focus();
     return false;
  }
  else if(lugarTarea == "")
  {
     alertify.error("Ingrese el lugar");
     playAudio('fail');
     $("#lugarTareaEdit").focus();
     return false;
  }
  
  //Petición Ajax
  var ruta = "{{URL::to('agenda/editarAgendarTarea/')}}";

  var parametros = {    
    "asuntoTarea" : asuntoTarea,
    "descripcionTarea" : descripcionTarea,
    "lugarTarea" : lugarTarea,
    "radProceso" : radProceso,
    "vigProceso" : vigProceso,
    "color" : color,
    "idTarea" : idTarea
  };

  $.ajax({                
            data:  parametros,
            url:   ruta,
            type:  'post',
            success:  function (responseText) 
            {
              playAudio('alert');
              alertify.success("Modificado correctamente");
              hideModal();
              $("#gridcontainer").reload();
            },
            error: function(responseText)
            {
              alert("Error.  Contacte al administrador (Cod.Error.calendar/728)");
            }
        }); 
}

function vigenciaProceso(vigencia)
{ 
  //Petición Ajax
  var ruta = "{{URL::to('procesos/cargarProcesosSelect/')}}";

  var parametros = {    
    "vigencia" : vigencia
  };

  $.ajax({                
            data:  parametros,
            url:   ruta,
            type:  'post',
            success:  function (responseText) 
            {
              $("#resultadoProceso").html(responseText);
            },
            error: function(responseText)
            {
              alert("Error.  Contacte al administrador (Cod.Error.calendar/650)");
            }
        }); 
}

/* PLAY SOUND FUNCTION */
function playAudio(file){
    if(file === 'alert')
        document.getElementById('audio-alert').play();

    if(file === 'fail')
        document.getElementById('audio-fail').play();    
}
/* END PLAY SOUND FUNCTION */
</script>
<!-- START PRELOADS -->
<audio id="audio-alert" src="{{ asset('audio/alert.mp3')}}" preload="auto"></audio>
<audio id="audio-fail" src="{{ asset('audio/fail.mp3')}}" preload="auto"></audio>
<!-- END PRELOADS -->                  

<script type="text/javascript" src="{{ asset('js/jquery-1.8.3.min.js') }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.es.js') }}" charset="UTF-8"></script>
<script src="{{ asset('js/jquery.userTimeout.js')}}"></script> 

<script type="text/javascript">
var jq = jQuery.noConflict();
</script>
</div>
</body>
</html>