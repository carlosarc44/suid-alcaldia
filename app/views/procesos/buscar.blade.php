@extends('plantillas.layout')
<!--includes de la cabecera-->
@section('cabecera')
<link href="{{ asset('css/timeline-progress.css?v=6') }}" rel="stylesheet">  
<style>
.timelineProgress {
  top: -23px !important;
  left: -20px !important;
  right: -20px !important;
}
.example-modal .modal {
  position: relative;
  top: auto;
  bottom: auto;
  right: auto;
  left: auto;
  display: block;
  z-index: 1;
}
.example-modal .modal {
  background: transparent !important;
}

@import url('https://fonts.googleapis.com/css?family=Anton&display=swap" rel="stylesheet');

/* html {
  scroll-behavior: smooth;
}

@media (prefers-reduced-motion: reduce) {
  html {
    scroll-behavior: auto;
  }
} */
.scroll {
  cursor: pointer;
  width: 70px;
  height: 70px;
  position: fixed;
  bottom: 40px;
  right: -80px;
  border-radius: 100%;
  background-color:#00a65a; 
  color: #fff;
  font-size: 44px;
  font-weight: bold;
  text-align: center;
  transition: 300ms;
}

.scroll i {
  margin-top: 10px;
  text-shadow: 0 0 2px #fff;
}

.scroll:hover i {
  animation-name: rotate;
  animation-duration: 300ms;
  animation-iteration-count: infinite;
  animation-direction: alternate;
}

@keyframes rotate {
  from {margin-top: 15px}
  to {margin-top: 5px}
}

.visible {
  right: 30px;
  transition: all 400ms;
  transform: rotate(360deg)
}


@import url('https://fonts.googleapis.com/css?family=Quicksand:300,400,500,600,700&display=swap');
/*LIGHT THEME*/
@media print {
	* {
		color-adjust: exact!important;
		-webkit-print-color-adjust: exact!important;
		print-color-adjust: exact!important;
		/*In FireFox it's in Page Setup -> [Format & Options] Tab under Options.*/
	}
	// If Table Issue with BG
	th,tr,td {
        color-adjust: exact!important;
		-webkit-print-color-adjust: exact!important;
		print-color-adjust: exact!important;
	}
	tr:nth-child(even) td {
        background: light !important;
    }
}

/*DARK THEME*/
.dark-theme {background: #23252b;}
.dark-theme .table-dark {box-shadow: 0 0 10px 0 rgba(0,0,0,.1);}
.dark-theme .table-dark,
.dark-theme .table-dark tr>th .users {color: #a9b5cb;}
.dark-theme .table-dark,
.dark-theme .table-dark tr {background: #2c2e37;}
.dark-theme .table-dark tr th {background: #161821;}
.dark-theme .table-dark tr th {border:0}
.dark-theme .table-dark tbody tr:hover {background: #2c2e37;}
.dark-theme .table-dark tr>th,
.dark-theme .table-dark .project,
.dark-theme .table-dark .date {color: white;}

.assigned {color: #8BC34A;}
.rejected {color: #ffb533;}
.outdated {color: #03A9F4;}
.dot-online {background: #8BC34A;}
.dot-offline {background: #e91e63;}

.strong,strong {font-weight: 600;}
.table-box {padding: 2px;}
.table-dark thead th {
    padding-top: 10px !important;
	padding-bottom: 10px !important;
	text-align: left !important;
}
.table-dark {
    border: 1px solid #444444;
    font-family: 'Quicksand';
    font-size: 13px;
}
.table-dark tr {
    border-width: 1px 0;
    border-style: solid;
}
.table-dark th,
.table-dark td {vertical-align: middle;}
.table-dark tr th,
.table-dark td {
    padding-left: 10px !important;
	padding-right: 10px;
	border-top: 1px solid #474749 !important
}
.table-dark thead th {
    font-size: 16px;
	font-weight: 600;
	box-shadow: inset 0px 0px 0px 0px #ffffff !important
}
.table-dark thead th,
.table-dark td, .table-dark th {border: 0;}
.table-dark .time {font-size: 80%;}
.status-indicator {
    width: 5px;
    height: 5px;
    border-radius: 50px;
}
.project-description {
    width: 300px;
    white-space: initial !important;
}
.table-dark.table-striped>tbody>tr:nth-of-type(odd) {
	background-color: #23252c !important;
}
.select2-container--default .select2-selection--multiple {
	background-color: transparent !important;
	border:1px solid #5d5959 !important;
}
.content-wrapper {
	background: #000;
}
.transparente {
	background-color: transparent !important;
	color:#fff !important;
}
option {
	color:#666
}
.no-border {
    border-top:0;    
}
.text-white {
    color:#fff
}
.form-control {
	border:1px solid #5d5959;
}
fieldset {
	border: 1px solid #5d5959 !important;
    border-radius: 10px;
    padding: 10px 20px;
}
</style>
@stop
<!--includes de la cabecera-->

<!--menu lateral izquierdo-->
@section('menuLateral') 
@include('includes.menuLateral')
@stop
<!-- #menu lateral izquierdo-->

@section('contenido')
<div class="row" style="position: fixed;top: 40px;z-index: 99;left: 70px;right: 20px;padding-top: 20px;background: #161821;">
  <div class="col-md-12">
		<div class="box box-info widget-user-2 dark-theme text-white no-border">
      <fieldset style="margin:6px">
        <div class="row">
          <div class="col-sm-3">
            <div class="widget-user-header bg-white-light">
              <div class="widget-user-image">
                <img src="{{ asset('img/SUID_transp3.png')}}" class="img-circle desaturada">
              </div>
              <h3 class="widget-user-username">Módulo de Búsquedas</h3>
              <h5 class="widget-user-desc">Oficina de Control Disciplinario Interno</h5>
              <div id="loader" style="height:100px;width:120px;position: absolute;top:4px;right:50%"></div>
            </div>
          </div>
          <div class="col-sm-2">
            <label className="col-form-label" >Número de Proceso:</label>
            <input type="text" class="form-control transparente" id="numeroProcesoBuscar" placeholder="AAAA-0000" autoFocus/>
            <span className="help-block">Vigencia-Número <i>Ej: 2020-0044</i></span>
          </div>  
          <div class="col-sm-2">
            <label className="col-form-label" >Quejoso:</label>
            <input type="text" class="form-control transparente" id="quejosoBuscar" placeholder="Nombre o Documento"/>
          </div>  
          <div class="col-sm-2">
            <label className="col-form-label" >Presunto Responsable:</label>
            <input type="text" class="form-control transparente" id="presuntoBuscar" placeholder="Nombre o Documento"/>
          </div>  
          <div class="col-sm-2">
            <label className="col-form-label" >Palabra Clave:</label>
            <input type="search" class="form-control transparente" id="palabraClave" placeholder="Palabra Clave" readonly  
            onfocus="$(this).removeAttr('readonly');" autocomplete="NoAutocomplete"/>
          </div>            
        </div>
      </fieldset>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="box box-gray no-border">
			<div class="box-body dark-theme" style="min-height: 100vh">
        <div id="ajax-buscar" style="padding-top: 138px">
          <!--ajax-->
          <div style="margin-top: 140px; left:10px; padding-left:40px; z-index: 999999; text-align:center">
              <img src="{{asset('img/logo-2024-b.png')}}" height="100" style="margin-top: 100px">
          </div>
        </div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade in" id="modalPdfGenerado" tabindex="-1" role="dialog" aria-labelledby="defModalHead"
        aria-hidden="true">
    <div class="modal-dialog" style="width:96%; margin-top: 4px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span
                        aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="defModalHead">EXPEDIENTE DIGITAL PROCESO</h4>
            </div>
            <div class="modal-body">
                <iframe id="framePdf" style="width: 100%;height: 80vh;"></iframe>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-xs-12">
                        <button type="button" class="btn btn-primary pull-right" data-dismiss="modal">Cerrar esta
                            ventana</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="jsScroll" class="scroll" onclick="scrollToTop();">
	<i class="fa fa-angle-up"></i>
</div>

@stop

<!--scriptsFin-->
@section('scriptsFin') 
<script type="text/javascript">

$(document).ready(function(){
    $('.sidebar-toggle').click()
    $('#numeroProcesoBuscar').inputmask("9999-9999"); 
    $(document).on('focus', ':input', function() {
        $(this).attr('autocomplete', 'new-password')
    })
    $("input.select2-input").attr('autocomplete','new-password')
});

function verArchivo(idArchivo) {
    //--------------GENERA PDF ------------------------------------------------
    //Carga el pdf generado
    //Lanza la modal
    $('#modalPdfGenerado').modal('show');
    //Carga el pdf en el iframe
    var rutaRedirect = "<?php echo URL::to('procesos/verArchivo/'); ?>";
    document.getElementById("framePdf").src = rutaRedirect + "/" + idArchivo;
    //--------------------------------------------------------------------------		
}

</script>
@stop
<!--# scriptsFin -->