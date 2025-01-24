<div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title">Programar tarea para el {{Util::formatearFechaCorta($fechaTarea)}} ({{$vigencia."-".$idRadicado}})</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <!-- text input -->
      <div class="form-group row">
        <div class="col-md-4">
          <label class="pull-right">Desde las <i class="fa fa-clock-o"></i> {{date("g:i a", strtotime($horaTarea))}} hasta las:</label>
        </div>
        <div class="col-sm-8">
          <!-- time Picker -->
          <div class="bootstrap-timepicker pull-left" style="width: 25%">
            <div class="form-group">
              <div class="input-group">
                <input type="text" class="form-control timepicker" value='{{date("g:i a", strtotime('+1 hour', strtotime($horaTarea)))}}' id="horaTareaFin" autofocus>
                <div class="input-group-addon">
                  <i class="fa fa-clock-o"></i>
                </div>
              </div>
              <!-- /.input group -->
            </div>
            <!-- /.form group -->
          </div>
          <!-- #time Picker -->
        </div>          
      </div>
      
        <div class="form-group">
          <label>Asunto</label>
           <input type="text" class="form-control" id="asuntoTarea">
        </div>  
        <div class="form-group">
          <label>Descripción</label>
          <textarea class="form-control" rows="3" placeholder="Descripción de la tarea" id="descripcionTarea"></textarea>
        </div>   
        <div class="form-group">
          <label>Lugar</label>
           <input type="text" class="form-control" value="Oficina de Control Disciplinario" id="lugarTarea">
        </div>    

     	<button type="button" class="btn btn-info btn-sm pull-right" onclick="guardarTarea('{{$fechaTarea}}', '{{$horaTarea}}');"><i class="fa fa-clock-o"></i> Programar</button> 
     
    </div>
    <!-- /.box-body -->
  </div>