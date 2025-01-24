@if(count($quejas) > 0)	
	<div class="row">
		<div class="col-xs-12">
			<small class="label pull-right bg-blue">{{count($quejas)}} 
				@if(count($quejas)==1)
					queja
				@else
					quejas
				@endif
			</small>
			<form name="f1" id="f1">
		      	<table id="tablaQuejas" class="table table-bordered table-hover table-striped">
		            <thead>
		                <tr>
		                 	<th width="80">Queja</th>
							<th width="110">Fecha Queja</th>													
							<th>Número Oficio</th>
							<th>Quejoso</th>
							<th>Presunto Responsable</th>
							<th width="30"></th>
		                </tr>
		            </thead>
		            <tbody>
		           		@foreach ($quejas as $queja)							
							<tr>
								<td class="text-center">
									<a href="javascript: void(0)" onclick="verQueja({{$queja->idQueja}})">{{ $queja->idQueja }}</a></td>
								<td>{{ $queja->fechaQueja }}</td>
								<td>{{ $queja->numeroOficio }}</td>
								<td>
									{{""; 
										$quejosos = Util::traerQuejososPorQueja($queja->idQueja);							
									}}

									@if(count($quejosos) > 0)
										@foreach($quejosos as $quejoso)
											<li>{{ $quejoso->nombre }}</li>
										@endforeach
									@else
										No se encontraron quejosos
									@endif
								</td>
								<td>
									{{""; 
										$presuntos = Util::traerPresuntosResponsablesPorQueja($queja->idQueja);
									}}

									@if(count($presuntos) > 0)
										@foreach($presuntos as $presunto)
											<li>{{ $presunto->nombre }}</li>
										@endforeach
									@else
										No se encontraron presuntos responsables
									@endif
								</td>
								<td>
									<label class="cb-checkbox">
									  	<input type="checkbox" name='seleccion[]'  value="{{$queja->idQueja}}">	
									</label>										
								</td>
							</tr>
						@endforeach  
		            </tbody>
		      	</table>
		    </form>	
		</div>
	</div>
	<button type="button" class="btn btn-success btn-sm pull-right" onclick="enviarSeleccionadas();"><i class="fa fa-external-link"></i> Enviar Seleccionadas</button>
@else
	<div class="alert alert-white alert-dismissible">
        <h4><i class="icon fa fa-info"></i><b>Atención</b></h4>
        No se encontraron quejas radicadas para enviar a reparto.
    </div>
@endif