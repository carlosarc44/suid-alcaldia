{{ Form::select('radProceso', array('default' => 'Número..') + $lista_procesos, 
	null, array('class' => 'form-control select2 select2-hidden-accessible pull-left', 'id'=>'radProceso', 'style'=>'width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true', 'style'=>'width:100%;')) }}
