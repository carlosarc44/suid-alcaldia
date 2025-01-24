<fieldset>
	<h3><i class="fa fa-file-pdf-o"></i> Agregar otros archivos </h3>
    <div class="row" style="margin-bottom: 20px;">
      <div class="col-xs-12">
        <p><code>Seleccione un archivo en formato pdf</code></p> 
      </div>
    </div>
    
    <div class="row" style="margin-bottom: 20px;">
      <div class="col-xs-2">
        <label class="pull-right">Etapa:</label>
      </div>
      <div class="col-xs-4" style="padding:0px;">              
       {{ Form::select('etapas', array('default' => 'Seleccione..') + $lista_etapas, 
              $idEtapa, array('class' => 'form-control', 'id'=>'etapaOtro', 'style'=>'width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true')) }}
      </div>

      <div class="col-xs-2">
        <label class="pull-right">Tipo:</label>
      </div>
      <div class="col-xs-4" style="padding:0px;">              
        {{ Form::select('etapas', array('default' => 'Seleccione..') + $lista_tiposArchivos, 
  null, array('class' => 'form-control', 'id'=>'tipoArchivoOtro', 'style'=>'width:94%;', 'tabindex'=>'-1', 'aria-hidden'=>'true')) }}
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">      
        <form action = "javascript:;" enctype="multipart/form-data" id="formularioOtro" class="form-horizontal">
            <div class="form-group">
              	<div class="col-md-12">  
                	<input type="file" class="file" data-preview-file-type="any" id="archivoImportarOtro" name="archivoImportarOtro" accept="application/pdf">
              	</div>                                           
            </div>
        </form>
        <button type="button" class="btn btn-info pull-right" style="margin:12px 0 4px 0;" onclick="validarSubirOtroArchivo()"><li class="fa fa-cloud-upload" style="color:#fff;"></li> Confirmar</button>
      </div>
    </div>
</fieldset>