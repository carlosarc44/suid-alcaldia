<fieldset style="min-width:100%;">
	<!-- row -->
	<div class="row">
		<!-- col-md-3 -->
		<div class="col-md-3">
			<div class="box box-widget widget-user-2" style="width: 80%;">
				<!-- Add the bg color to the header using any of the bg-* classes -->
				<div class="widget-user-header bg-gray-light" style="border: 1px solid #ddd;">
					<div class="widget-user-image">
						<img src="{{ asset('img/SUID_transp2.png')}}" class="img-circle">
					</div>
					<!-- /.widget-user-image -->
					<h3 class="widget-user-username" style="font-size: 1.3em;"><b>{{$vigencia."-".$idRadicado}}</b></h3>
					<h5 class="widget-user-desc">Tareas</h5>
				</div>
				<div class="box-footer no-padding" style="border: 1px solid #ddd; padding:0;">
					<ul class="nav nav-stacked">
						<li>
                    		<div id="fechaTareas" style="margin-top:10px;"></div>   
						</li>
					</ul>
				</div>
			</div>
				
		</div>
		<!-- # col-md-3 -->

		<!--  col-md-9 -->
		<div class="col-md-9" style="padding: 0;">
			<fieldset>	
				<!-- resultadoCargarHoras -->
				<div class="row" id="resultadoCargarHoras">
					<!-- CARGA AJAX -->
				</div>			
				<!-- # resultadoCargarHoras -->
			</fieldset>
		</div>
		<!-- # col-md-9 -->
	</div>
	<!-- # row -->
</fieldset>