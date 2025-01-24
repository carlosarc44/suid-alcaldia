@if(count($procesos) > 0)	
	<div class="row">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-sm-10">
					<h3>Procesos de {{Util::traerNombreAbogadoId($idAbogado)}}</h3>
				</div>
				<div class="col-sm-2">
					<small class="label pull-right bg-blue">{{count($procesos)}} 
						@if(count($procesos)==1)
							proceso
						@else
							procesos
						@endif
					</small>
				</div>
			</div>			

            <div class="row">
            	<div class="col-sm-11">
            		<button type="button" class="btn btn-info btn-sm pull-right" style="margin-bottom: 6px;" onclick="marcarTodo();"><i class="fa fa-file-check"></i> Todo</button>
            	</div>
            	<div class="col-sm-1">
            		<button type="button" class="btn btn-info btn-sm pull-right" style="margin-bottom: 6px;" onclick="desmarcarTodo();"><i class="fa fa-file-check-o"></i> Nada</button>
            	</div>
            </div>

			<form name="f1" id="f1">
		      	<table id="tablaProcesos" class="table table-bordered table-hover table-striped">
		            <thead>
		                <tr>
		                 	<th width="80">Proceso</th>
							<th width="110">Queja</th>	
							<th>Quejoso</th>
							<th>Presunto Responsable</th>
							<th width="20"></th>
		                </tr>
		            </thead>
		            <tbody>
		           		@foreach ($procesos as $proceso)							
							<tr>
								<td class="text-center" style="vertical-align:middle;">
									<a href="{{asset('/procesos/ver/'.$proceso->vigencia."/".$proceso->idRadicado)}}">
										<span class="label label-info" style="min-width:100px !important; font-size:0.9em;">
											{{ $proceso->vigencia."-".$proceso->idRadicado }}
										</span>
									</a>
								</td>
								<td>
									<ul style="list-style-type:circle;">
										{{Util::traerNumeroQueja($proceso->vigencia, $proceso->idRadicado)}}</td>
									</ul>
								<td>{{Util::traerQuejosos($proceso->vigencia, $proceso->idRadicado)}}</td>
								<td>{{Util::traerPresuntosResponsables($proceso->vigencia, $proceso->idRadicado)}}</td>
								<td>
									<label class="cb-checkbox_xxx">
									  	<input type="checkbox" class="check" name='check_{{$proceso->vigencia."-".$proceso->idRadicado}}'  value="{{$proceso->vigencia.'-'.$proceso->idRadicado}}">	
									</label>										
								</td>
							</tr>

						@endforeach  
		            </tbody>
		      	</table>
		    </form>	

		    <form>
    <input type="checkbox" class="check" name="check1">
    <br><input type="checkbox" class="check" name="check2">
    <br><input type="checkbox" class="check" name="check3">
    <br><input type="button" onclick="checkTodos()" value="check todos">
</form>

		</div>
	</div>
@else
	<div class="alert alert-white alert-dismissible">
        <h4><i class="icon fa fa-info"></i><b>Atención</b></h4>
        No se encontraron procesos asociados a este profesional.
    </div>
@endif