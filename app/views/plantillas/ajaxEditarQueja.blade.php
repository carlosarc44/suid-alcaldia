<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title"></h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<!-- /.box-header -->
	<div class="box-body" style="display: block;">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
                    <label>Origen</label>
                    {{ Form::select('origenQueja', array('default' => 'Seleccione..') + $lista_origenes, 
                    $queja->OrigenQueja_idOrigenQueja, array('class' => 'form-control select2 select2-hidden-accessible', 'id'=>'origenQueja', 'style'=>'width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true')) }}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Tipo de recepción</label>
					{{ Form::select('tipoRecepcion', array('default' => 'Seleccione..') + $lista_tiposRecepcion, 
					$queja->TipoRecepcionQueja_idTipoQueja, array('class' => 'form-control select2 select2-hidden-accessible', 'id'=>'tipoRecepcion', 'style'=>'width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true')) }}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Fecha de la Queja</label>
					<div class="input-group date">
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</div>
                        <input type="text" class="form-control pull-right" id="fechaQueja" value="{{$queja->fechaQueja}}">
					</div>
				</div>
			</div>

			<div class="col-md-6">	
				<div class="form-group">
					<label>Fecha de recepción</label>
					<div class="input-group date">
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</div>
						<input type="text" class="form-control pull-right" id="fechaRecepcion" value="{{$queja->fechaRecepcionQueja}}">
					</div>
				</div>
			</div>			
		</div>

		<div class="row">
			<div class="col-xs-6">	
				<div class="form-group">
					<label>Oficio</label>
					<input type="text" class="form-control pull-right" id="numeroOficio" placeholder="Número de Oficio" style="width:100%" value="{{$queja->numeroOficio}}">
				</div>
			</div> 
			<div class="col-xs-6">
				<div class="form-group">
					<label>Dependencia del presunto responsable</label>
					{{ Form::select('dependenciaQueja', array('default' => 'Seleccione..') + $lista_dependencias, 
					$queja->dependencia_idDependencia, array('class' => 'form-control select2 select2-hidden-accessible', 'id'=>'dependenciaQueja', 'style'=>'width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true')) }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">	
				<div class="form-group">
					<label>Presunto Lugar</label>
					<input type="text" class="form-control pull-right" id="presuntoLugar" autocomplete="off" value="{{$queja->presuntoLugar}}">
				</div>
				<!-- /.form-group -->
			</div>
		</div>
		<br>
		<!-- /.row -->
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label>Presuntos hechos</label>
					<textarea id="presuntosHechos" class="form-control" rows="4" placeholder="Hacer una breve descripción de los presuntos hechos...">{{$queja->presuntosHechos}}</textarea>
				</div>
				<!-- /.form-group -->
			</div>			
		</div>
		<!-- /.row -->
	</div>	
	<!-- /.box-body -->
</div>

<div class="row">
	<div class="col-md-6">
		<button type="button" class="btn btn-default btn-sm pull-left" onclick="verQueja('{{$queja->idQueja}}', '{{$multiples}}');"><i class="fa fa-arrow-left"></i> Cancelar</button>
	</div>
	<div class="col-md-6">
		<button type="button" class="btn btn-success btn-sm pull-right" onclick="validarEditarQueja('{{$queja->idQueja}}', '{{$multiples}}');"><i class="fa fa-save"></i> Modificar Queja</button>
	</div>
</div>