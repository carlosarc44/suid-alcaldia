<style>
	fieldset *{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}
</style>
<h2 id="modalTitle">{{$fechaSeleccionada}}</h2>
<br>
<fieldset>
	<div class="row" style="margin-bottom: 8px;">
		<div class="col-xs-2">
			<label class="pull-left"><b>Proceso:</b></label>		
		</div>
		<div class="col-xs-3">
			{{""; $vigenciaActual = date("Y")}}
			<select class="form-control pull-left" id="vigProceso" onchange="vigenciaProceso(this.value);" style="width:80%;">
                <option value='{{ $vigenciaActual }}'>{{ $vigenciaActual }}</option>
                <?php 
                    for ($i=2014; $i<=$vigenciaActual; $i++) 
                    {
                      echo "<option value='$i'>$i</option>";
                    }  
                ?>
            </select>
		</div>
		<!-- resultadoProceso -->
		<div class="col-xs-3" id="resultadoProceso">
			<!-- CARGA AJAX -->
			{{ Form::select('radProceso', array('default' => 'Número..') + $lista_procesos, 
				null, array('class' => 'form-control select2 select2-hidden-accessible pull-left', 'id'=>'radProceso', 'style'=>'width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true', 'style'=>'width:100%;')) 
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
						          <input type="radio" name="cor" id="cor1" value="1" />
						          <label for="cor1" class="cor1"></label>
						          <input type="radio" name="cor" id="cor2" value="2" />
						          <label for="cor2" class="cor2"></label>
						          <input type="radio" name="cor" id="cor3" value="3" checked/>
						          <label for="cor3" class="cor3"></label>
						          <input type="radio" name="cor" id="cor4" value="4" />
						          <label for="cor4" class="cor4"></label>
						          <input type="radio" name="cor" id="cor5" value="5" />
						          <label for="cor5" class="cor5"></label>
						          <input type="radio" name="cor" id="cor6" value="6" />
						          <label for="cor6" class="cor6"></label>
						          <input type="radio" name="cor" id="cor7" value="7" />
						          <label for="cor7" class="cor7"></label>
						          <input type="radio" name="cor" id="cor8" value="8" />
						          <label for="cor8" class="cor8"></label>
						          <input type="radio" name="cor" id="cor9" value="9" />
						          <label for="cor9" class="cor9"></label>
						          <input type="radio" name="cor" id="cor10" value="10" />
						          <label for="cor10" class="cor10"></label>
						          <input type="radio" name="cor" id="cor11" value="11" />
						          <label for="cor11" class="cor11"></label>
						          <input type="radio" name="cor" id="cor12" value="12" />
						          <label for="cor12" class="cor12"></label>
						          <input type="radio" name="cor" id="cor13" value="13" />
						          <label for="cor13" class="cor13"></label>
						          <input type="radio" name="cor" id="cor14" value="14" />
						          <label for="cor14" class="cor14"></label>
						          <input type="radio" name="cor" id="cor15" value="15" />
						          <label for="cor15" class="cor15"></label>
						          <input type="radio" name="cor" id="cor16" value="16" />
						          <label for="cor16" class="cor16"></label>
						          <input type="radio" name="cor" id="cor17" value="17" />
						          <label for="cor17" class="cor17"></label>
						          <input type="radio" name="cor" id="cor18" value="18" />
						          <label for="cor18" class="cor18"></label>
						          <input type="radio" name="cor" id="cor19" value="19" />
						          <label for="cor19" class="cor19"></label>
						          <input type="radio" name="cor" id="cor20" value="20" />
						          <label for="cor20" class="cor20"></label>
						          <input type="radio" name="cor" id="cor21" value="21" />
						          <label for="cor21" class="cor21"></label>
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
	<input type="hidden" id="fechaInicioAg" value="{{$fechaInicio}}">
	<input type="hidden" id="fechaFinalAg" value="{{$fechaFinal}}">	
    
    <div class="form-group">
      <label><b>Asunto:</b></label>
       <input type="text" class="form-control" id="asuntoTarea">
    </div>  
    <div class="form-group">
      <label><b>Descripción:</b></label>
      <textarea class="form-control" rows="3" id="descripcionTarea"></textarea>
    </div>   
    <div class="form-group">
      <label><b>Lugar:</b></label>
       <input type="text" class="form-control" value="CDI" id="lugarTarea">
    </div>    
</fieldset>	
<br>
<p>
<button type="button" class="btn btn-info btn-sm pull-right" style="color:#fff; padding: 10px; cursor:pointer;" onclick="validarAdicionarEvento();"><i class="fa fa-clock-o"></i> Programar</button>
</p>