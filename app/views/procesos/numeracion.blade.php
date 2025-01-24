<div class="box box-widget widget-user-2">
	<!-- Add the bg color to the header using any of the bg-* classes -->
	<div class="widget-user-header bg-gray-light">
		<div class="widget-user-image">
			<img src="{{ asset('img/SUID_transp2.png')}}" class="img-circle desaturada">
		</div>
		<!-- /.widget-user-image -->
		<h3 class="widget-user-username">Fase de Instrucción</h3>
		<h5 class="widget-user-desc">Vigencia {{date('Y')}}</h5>
	</div>
	<div class="box-footer no-padding">
		<ul class="nav nav-stacked">
			@foreach ($etapasInstruccion as $etapa)
				<li>
					<a href="javascript: void(0)" style="cursor:no-drop">
						{{mb_convert_case($etapa->nombreEtapa, MB_CASE_TITLE, "UTF-8")}}
						<span class="pull-right badge bg-aqua">{{Util::traerUltimoAuto($etapa->idEtapa)}}</span>
					</a>
				</li>				
			@endforeach
		</ul>
	</div>
</div>
<br>
<div class="box box-widget widget-user-2">
	<!-- Add the bg color to the header using any of the bg-* classes -->
	<div class="widget-user-header bg-gray-light">
		<div class="widget-user-image">
			<img src="{{ asset('img/SUID_transp2.png')}}" class="img-circle desaturada">
		</div>
		<!-- /.widget-user-image -->
		<h3 class="widget-user-username">Fase de Juzgamiento</h3>
		<h5 class="widget-user-desc">Vigencia {{date('Y')}}</h5>
	</div>
	<div class="box-footer no-padding">
		<ul class="nav nav-stacked">
			@foreach ($etapasJuzgamiento as $etapa)
				<li>
					<a href="javascript: void(0)" style="cursor:no-drop">
						{{mb_convert_case($etapa->nombreEtapa, MB_CASE_TITLE, "UTF-8")}}
						<span class="pull-right badge bg-aqua">{{Util::traerUltimoAuto($etapa->idEtapa)}}</span>
					</a>
				</li>				
			@endforeach
		</ul>
	</div>
</div>