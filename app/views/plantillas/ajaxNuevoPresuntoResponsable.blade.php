<div class="row">
    <div class="col-sm-9">
        <br>
        <div class="box">
            <div class="box-body box-profile">
                <h3 class="profile-username text-center">Crear Presunto Responsable</h3>
                <p class="text-muted text-center">Queja</p>
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-2">
                                <b>Documento:</b> 
                            </div>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" id="documentoPersona" style="margin-top: 4px" autofocus>           
                            </div>
                            <div class="col-sm-4">
                                <label for="chk-documentoPersona" class="checkbox-slider">
                                    <input class="input" id="chk-documentoPersona" type="checkbox" checked onclick="verifyChk(this.id)" />
                                    <div class="toggle-wrapper"><span class="selector"></span></div>
                                    <p class="notification">Documento: <span class="selected"></span></p>
                                </label>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">                        
                        <div class="row">
                            <div class="col-sm-2">
                                <b>Nombre:</b> 
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="nombre" style="margin-top: 4px">                          
                            </div>
                            <div class="col-sm-4">
                                <label class="checkbox-slider">
                                    * El Nombre es obligatorio 
                                </label>
                            </div>
                        </div>                        
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-2">
                                <b>Dirección:</b> 
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="direccionCorrespondencia" style="margin-top: 4px">  
                            </div>
                            <div class="col-sm-4">
                                <label for="chk-direccionCorrespondencia" class="checkbox-slider">
                                    <input class="input" id="chk-direccionCorrespondencia" type="checkbox" checked onclick="verifyChk(this.id)" />
                                    <div class="toggle-wrapper"><span class="selector"></span></div>
                                    <p class="notification">Dirección: <span class="selected"></span></p>
                                </label>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-2">
                                <b>Ciudad:</b> 
                            </div>
                            <div class="col-sm-6">
                                <select data-placeholder="Seleccione un municipio.." class="js-example-basic-single" id="ciudadCorrespondencia">
                                    <option value=""></option>
                                    @if(count($departamentos) > 0)
                                        @foreach ($departamentos as $dep)                  
                                            <optgroup label="{{$dep->nombreDepartamento}}">                    
                                                {{'';
                                                    $ciudades = DB::table('ciudad')
                                                                ->where('departamento_idDepartamento', '=', $dep->idDepartamento)  
                                                                ->get();
                                                }}
                                                @foreach ($ciudades as $ciudad)
                                                    <option value="{{$ciudad->idCiudad}}">{{$ciudad->nombreCiudad}}</option>
                                                @endforeach                                
                                            </optgroup>                              
                                        @endforeach               
                                    @else                
                                        No hay departamentos              
                                    @endif             
                                </select>  
                            </div>
                            <div class="col-sm-4">
                                <label for="chk-ciudadCorrespondencia" class="checkbox-slider">
                                    <input class="input" id="chk-ciudadCorrespondencia" type="checkbox" checked onclick="verifyChk(this.id)"/>
                                    <div class="toggle-wrapper"><span class="selector"></span></div>
                                    <p class="notification">Ciudad: <span class="selected"></span></p>
                                </label>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-2">
                                <b>Teléfono:</b> 
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="telefono" style="margin-top: 4px">
                            </div>
                            <div class="col-sm-4">
                                <label for="chk-telefono" class="checkbox-slider">
                                    <input class="input" id="chk-telefono" type="checkbox" checked onclick="verifyChk(this.id)" />
                                    <div class="toggle-wrapper"><span class="selector"></span></div>
                                    <p class="notification">Teléfono: <span class="selected"></span></p>
                                </label>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-2">
                                <b>Celular:</b> 
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="telefono2" style="margin-top: 4px">
                            </div>
                            <div class="col-sm-4">
                                <label for="chk-telefono2" class="checkbox-slider">
                                    <input class="input" id="chk-telefono2" type="checkbox" checked onclick="verifyChk(this.id)"/>
                                    <div class="toggle-wrapper"><span class="selector"></span></div>
                                    <p class="notification">Celular: <span class="selected"></span></p>
                                </label>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-2">
                                <b>Correo Electrónico:</b> 
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="email" style="margin-top: 4px">   
                            </div>
                            <div class="col-sm-4">
                                <label for="chk-email" class="checkbox-slider">
                                    <input class="input" id="chk-email" type="checkbox" checked onclick="verifyChk(this.id)" />
                                    <div class="toggle-wrapper"><span class="selector"></span></div>
                                    <p class="notification">Correo Electrónico: <span class="selected"></span></p>
                                </label>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-2">
                                <b>Dependencia:</b> 
                            </div>
                            <div class="col-sm-6">
                                <select data-placeholder="Seleccione una dependencia.." class="js-example-basic-single" id="dependencia">
                                    <option value=""></option>
                                    @if(count($dependencias) > 0)
                                        @foreach ($dependencias as $dependencia)
                                            <option value="{{$dependencia->idDependencia}}">{{$dependencia->nombreDependencia}}</option>
                                        @endforeach                                
                                    @else                
                                        No hay dependencias              
                                    @endif             
                                </select> 
                            </div>
                            <div class="col-sm-4">
                                <label for="chk-dependencia" class="checkbox-slider">
                                    <input class="input" id="chk-dependencia" type="checkbox" checked onclick="verifyChk(this.id)"/>
                                    <div class="toggle-wrapper"><span class="selector"></span></div>
                                    <p class="notification">Dependencia: <span class="selected"></span></p>
                                </label>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-2">
                                <b>Cargo:</b> 
                            </div>
                            <div class="col-sm-6">
                                <select data-placeholder="Seleccione un cargo.." class="js-example-basic-single" id="cargo">
                                    <option value=""></option>
                                    @if(count($cargos) > 0)
                                        @foreach ($cargos as $cargo)
                                            <option value="{{$cargo->idCargo}}">{{$cargo->nombreCargo}}</option>
                                        @endforeach                                
                                    @else                
                                        No hay cargos              
                                    @endif             
                                </select> 
                            </div>
                            <div class="col-sm-4">
                                <label for="chk-cargo" class="checkbox-slider">
                                    <input class="input" id="chk-cargo" type="checkbox" checked onclick="verifyChk(this.id)"/>
                                    <div class="toggle-wrapper"><span class="selector"></span></div>
                                    <p class="notification">Cargo: <span class="selected"></span></p>
                                </label>
                            </div>
                        </div>
                    </li>
                </ul>
                <a href="javascript: void(0)" class="btn btn-info btn-block" onclick="modificarPresuntoResponsable(0, 1)">
                    <b><i class="fa fa-save"></i> Guardar Presunto Responsable</b>
                </a>
            </div>
        </div>
    </div>
</div>
