@if ($cantTareas > 0)    
    <div class="progress-group" style="margin: 0;">
        <span class="progress-text">Cumplimiento</span>
        <span class="progress-number">{{round($porcentaje, 1)}}%</span>
        <div class="progress sm" style="margin: 0;">
				@if($porcentaje < 70)							
				<div class="progress-bar progress-bar-red" style="width: {{round($porcentaje, 1)}}%"></div>
			@elseif($porcentaje > 69 && $porcentaje < 100)
				<div class="progress-bar progress-bar-yellow" style="width: {{round($porcentaje, 1)}}%"></div>
			@elseif($porcentaje >= 100)
				<div class="progress-bar progress-bar-green" style="width: {{round($porcentaje, 1)}}%"></div>
			@endif
        </div>
    </div>
@endif