<div class="row">
	<div class="col-sm-12">
		<button type="button" class="btn btn-default pull-left" onclick="verArchivosGenerados()"><li class="fa fa-arrow-left" style="color:#0082ff;"></li> Volver a los Archivos</button>
	</div>
</div>
<br>
<fieldset>
	<h3><i class="fa fa-file-pdf-o"></i> {{$archivo[0]->idArchivoGenerado}}</h3>
    <p><code>{{$archivo[0]->nombreArchivoGenerado}}</code></p> 
    <form action = "javascript:;" enctype="multipart/form-data" id="formulario" class="form-horizontal">
        <div class="form-group">
          	<div class="col-md-12">  
            	<input type="file" class="file" data-preview-file-type="any" id="archivoImportar" name="archivoImportar" accept="application/pdf">
          	</div>                                           
        </div>
    </form>
    <button type="button" class="btn btn-info pull-right" style="margin:12px 0 4px 0;" onclick="validarSubirArchivo('{{$archivo[0]->idArchivoGenerado}}')"><li class="fa fa-cloud-upload" style="color:#fff;"></li> Confirmar</button>
</fieldset>