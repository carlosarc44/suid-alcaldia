<style>
	fieldset *{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}
</style>
<h2 id="modalTitle">{{$tarea->fechaInicioTarea}}</h2>
<br>
<fieldset>
	<div class="row" style="margin-bottom: 8px;">
		<div class="col-xs-2">
			<label class="pull-left"><b>Proceso:</b></label>		
		</div>
		<div class="col-xs-3">
			{{""; $vigenciaActual = date("Y")}}
			<select class="form-control pull-left" id="vigProcesoEdit" onchange="vigenciaProceso(this.value);" style="width:80%;">
          <?php 
              for ($i=2014; $i<=$vigenciaActual; $i++) 
              {
                if($i == $tarea->Radicado_vigenciaRadicado)
                  {
                    echo "<option value='$i' selected>$i</option>";
                  }
                  else
                  {
                    echo "<option value='$i'>$i</option>";
                  }
              }  
          ?>
      </select>
		</div>
		<!-- resultadoProceso -->
		<div class="col-xs-3" id="resultadoProceso">
			<!-- CARGA AJAX -->
			{{ Form::select('radProceso', array('default' => 'Número..') + $lista_procesos, 
				$tarea->Radicado_idRadicado, array('class' => 'form-control select2 select2-hidden-accessible pull-left', 'id'=>'radProcesoEdit', 'style'=>'width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true', 'style'=>'width:100%;')) 
			}}
		</div>
		<!-- # resultadoProceso -->
    </div> 
</fieldset>
<br>
<fieldset>
<style>

input[id*="cor"] {
  display: none;
}
input[id*="cor"]:checked + label:before {
  content: '\f00c';
  display: block;
  position: absolute;
  font-family: 'fontawesome';
  top: 0px;
  left: 1px;
  font-size: 12px;
  color: #000;
}

label[class*="cor"] {
  display: inline-block;
  height: 16px;
  width: 16px;
  cursor: pointer;
  border: 1px solid transparent;
  position: relative;
  margin-right: 5px;
}
label[class*="cor"]:hover {
  border-color: #000;
}
.cor1{
    background: #d96666;
    border-color: #d96666;
}
.cor2{
    background: #993355;
    border-color: #993355;
}
.cor3{
    background: #b373b3;
    border-color: #b373b3;
}
.cor4{
    background: #8c66d9;
    border-color: #8c66d9;
}
.cor5{
    background: #668cb3;
    border-color: #668cb3;
}
.cor6{
    background: #668cd9;
    border-color: #668cd9;
}
.cor7{
    background: #59bfb3;
    border-color: #59bfb3;
}
.cor8{
    background: #65ad89;
    border-color: #65ad89;
}
.cor9{
    background: #4cb052;
    border-color: #4cb052;
}
.cor10{
    background: #8cbf40;
    border-color: #8cbf40;
}
.cor11{
    background: #bfbf4d;
    border-color: #bfbf4d;
}
.cor12{
    background: #e0c240;
    border-color: #e0c240;
}
.cor13{
    background: #f2a640;
    border-color: #f2a640;
}
.cor14{
    background: #e6804d;
    border-color: #e6804d;
}
.cor15{
    background: #be9494;
    border-color: #be9494;
}
.cor16{
    background: #a992a9;
    border-color: #a992a9;
}
.cor17{
    background: #8997a5;
    border-color: #8997a5;
}
.cor18{
    background: #94a2be;
    border-color: #94a2be;
}
.cor19{
    background: #85aaa5;
    border-color: #85aaa5;
}
.cor20{
    background: #a7a77d;
    border-color: #a7a77d;
}
.cor21{
    background: #c4a883;
    border-color: #c4a883;
}
</style>
	<div class="row" style="margin-bottom: 8px;">
		<div class="col-xs-12">
      <div class="panel panel-default">
				<div class="panel-body">
				    <form role="form">
				      	<div class="form-group">
					        <label>Color de fondo</label>
					        <div>
                      @for ($i = 1; $i <= 21; $i++)
                        @if ($tarea->color == $i)
                          <input type="radio" name="corEdit" id="cor{{$i}}" value="{{$i}}" checked/>
                          <label for="cor{{$i}}" class="cor{{$i}}"></label>
                        @else
                          <input type="radio" name="corEdit" id="cor{{$i}}" value="{{$i}}"/>
                          <label for="cor{{$i}}" class="cor{{$i}}"></label>
                        @endif
                      @endfor
					        	</div>
					      	</div>
					    </form>
				  	</div>
				</div>
			</div>
    	</div> 
</fieldset>

<br>
<fieldset>	
	<input type="hidden" id="idTareaEdit" value="{{$tarea->Id}}">    
  <div class="form-group">
    <label><b>Asunto:</b></label>
     <input type="text" class="form-control" id="asuntoTareaEdit" value="{{$tarea->asuntoTarea}}">
  </div>  
  <div class="form-group">
    <label><b>Descripción:</b></label>
    <textarea class="form-control" rows="3" id="descripcionTareaEdit">{{$tarea->descripcionTarea}}</textarea>
  </div>   
  <div class="form-group">
    <label><b>Lugar:</b></label>
     <input type="text" class="form-control" value="{{$tarea->lugarTarea}}" id="lugarTareaEdit">
  </div>    
</fieldset>	
<br>
<p>
<button type="button" class="btn btn-info btn-sm pull-right" style="color:#fff; padding: 10px; cursor:pointer;" onclick="validarEditarEvento();"><i class="fa fa-clock-o"></i> Modificar</button>
</p>


