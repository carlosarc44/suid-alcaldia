<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Remisión Proceso: {{$vigencia."-".$idRadicado}}</label>
                <br>
                <style>
                    #hoja{
                        border: 1px solid #ddd;
                        border-radius: 5px;
                        background: #fff;
                        padding: 10px;
                        min-height: 440px;
                    }
                    .tituloAyuda{
                        color:#c4c4c4;	
                    }
                </style>
                <div class="row">
                    <div class="col-xs-12">
                        <div id="hoja">
                            <div class="row">
                                <img src="{{ asset('img/banner-oficio.png')}}" style="height:auto; width:99%; margin:-10px 0px 5px 5px"/>	        
                                <div class="col-xs-10 col-xs-offset-1">
                                        <strong>CDI {{$numeroOficio."/".date("Y")." - ".$iniciales}}</strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-10 col-xs-offset-1">
                                    Manizales, 	{{Util::formatearFecha(date("Y-m-d"))}}
                                </div>
                            </div>
                            <br><br>
                            <div class="row">
                                <div class="col-xs-1 col-xs-offset-1">
                                <strong>Señor:</strong>
                                </div>
                            </div>
                            
                            <!-- resultadoDestino -->
                            <div id="resultadoDestino">    
                                <div class="row">
                                        <div class="col-xs-5 col-xs-offset-1">
                                        <strong><input type="text" class="form-control no-border" id="destinatario" placeholder="Escriba aquí el destinatario" style="padding-left:2px; text-transform: uppercase;" autocomplete="off" autofocus/></strong>
                                        </div>
                                        <div class="col-xs-3">
                                        <span class="tituloAyuda">Destinatario</span>
                                        </div>
                                </div>       
                            
                                    <div class="row">
                                    <div class="col-xs-5 col-xs-offset-1">
                                            <input type="text" class="form-control no-border" id="entidad" placeholder="Escriba aquí la entidad" style="padding-left:2px;" autocomplete="off"/>
                                    </div>
                                    <div class="col-xs-3">
                                            <span class="tituloAyuda">Entidad de destino</span>
                                    </div>
                                    </div>
                            
                                    <div class="row">
                                    <div class="col-md-5 col-xs-12 col-xs-offset-1">
                                            <input type="text" class="form-control no-border" id="direccion" placeholder="Escriba aquí la dirección" style="padding-left:2px;" autocomplete="new-password"/>
                                    </div> 
                                    <div class="col-xs-5">
                                            <span class="tituloAyuda">Dirección del destinatario</span>
                                    </div>  
                                    </div>
                        
                                    <div class="row">
                                    <div class="col-md-5 col-xs-offset-1" style="padding-bottom: 5px">   
                                        {{ Form::select('departamento', array('default' => 'Departamento') + $lista_departamentos, 
                                            Input::old('departamento'), array('class' => 'form-control select2 select2-hidden-accessible', 'id'=>'departamento', 'onchange' => 'cargarCiudad(this.value)', 'style'=>'color:#696969; padding-left:0;width:100%;', 'tabindex'=>'-1', 'aria-hidden'=>'true', 'onchange' => 'cargarCiudad(this.value)')) }}
                                    </div>
                                    <div class="col-xs-4">
                                            <span class="tituloAyuda">Departamento de destino</span>
                                    </div>
                                    </div>
                        
                                    <div id="resultadoCargarCiudad" class="row">              
                                    <!-- CARGA AJAX -->              
                                    </div>	          
                                </div>
                                <!-- # resultadoDestino -->
                                <div class="row" style="margin: 40px 0;">
                                    <div class="col-xs-2 col-xs-offset-1" style="padding-top:6px;">
                                        <label class="pull-right">ASUNTO: </label>
                                </div>
                                <div class="col-xs-9">
                                    <span>Remisión por competencia N° <strong>RXC {{$remisionCompetencia."/".date('Y')}}</strong>  -  Proceso <strong>{{$vigencia."-".$idRadicado}}</strong></span>
			            		<br>
                                </div>                                      
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="pull-right">Motivo de la remisión:</label>
                                </div>
                                <div class="col-md-8">
                                    <textarea id="motivo" rows="3" class="form-control" autocomplete="off" placeholder="Escriba aquí el motivo por el cuál se va a realizar la remisión por competencia"></textarea>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="pull-right">Al generar el oficio utilizar:</label>
                                </div>
                                <div class="col-md-8">
                                    <select id="tipoRemision" class="form-control pull-left">
                                        <option value="1">Plantilla para remitir a una Entidad</option>
                                        <option value="2">Plantilla para remitir al Comité de Convivencia Laboral</option>
                                        <option value="3">Plantilla para realizar una Devolución</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <button type="button" class="btn btn-success btn-sm pull-right" style="margin-top: 6px;" onclick="remitirPorCompetencia();"><i class="fa fa-save"></i> Remitir Proceso por Competencia</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
