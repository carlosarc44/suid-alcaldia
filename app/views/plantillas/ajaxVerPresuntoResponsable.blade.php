@if (count($presuntoresponsable) > 0)
<div class="row">
    <div class="col-sm-9">
        <br>
        <div class="box">
            <div class="box-body box-profile">
                <input type="hidden" id="presuntoResponsable" value='{{json_encode($presuntoresponsable)}}'>

                <h3 class="profile-username text-center">{{$presuntoresponsable->nombre}}</h3>

                <p class="text-muted text-center">{{$presuntoresponsable->documentoPersona}}</p>

                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-2">
                                <b>Documento:</b> 
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="documentoPersona" style="margin-top: 4px" value="{{$presuntoresponsable->documentoPersona}}" autofocus>           
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
                                <input type="text" class="form-control" id="nombre" style="margin-top: 4px" value="{{$presuntoresponsable->nombre}}">                          
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
                                <input type="text" class="form-control" id="direccionCorrespondencia" style="margin-top: 4px" value="{{$presuntoresponsable->direccionCorrespondencia}}">  
                            </div>
                            <div class="col-sm-4">
                                <label for="chk-direccionCorrespondencia" class="checkbox-slider">
                                    <input class="input" id="chk-direccionCorrespondencia" type="checkbox" checked onclick="verifyChk(this.id)"/>
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
                                                    @if ($ciudad->idCiudad == $presuntoresponsable->ciudadCorrespondencia)
                                                        <option value="{{$ciudad->idCiudad}}" selected>{{$ciudad->nombreCiudad}}</option>                                                
                                                    @else
                                                        <option value="{{$ciudad->idCiudad}}">{{$ciudad->nombreCiudad}}</option>
                                                    @endif
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
                                <input type="text" class="form-control" id="telefono" style="margin-top: 4px" value="{{$presuntoresponsable->telefono}}">
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
                                <input type="text" class="form-control" id="telefono2" style="margin-top: 4px" value="{{$presuntoresponsable->telefono2}}">
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
                                <input type="text" class="form-control" id="email" style="margin-top: 4px" value="{{$presuntoresponsable->email}}">   
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
                                            @if ($dependencia->idDependencia == $presuntoresponsable->Dependencia_idDependencia)
                                                <option value="{{$dependencia->idDependencia}}" selected>{{$dependencia->nombreDependencia}}</option>                                                
                                            @else
                                                <option value="{{$dependencia->idDependencia}}">{{$dependencia->nombreDependencia}}</option>
                                            @endif
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
                                            @if ($cargo->idCargo == $presuntoresponsable->Cargo_idCargo)
                                                <option value="{{$cargo->idCargo}}" selected>{{$cargo->nombreCargo}}</option>                                                
                                            @else
                                                <option value="{{$cargo->idCargo}}">{{$cargo->nombreCargo}}</option>
                                            @endif
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

                <a href="javascript: void(0)" class="btn btn-info btn-block" onclick="modificarPresuntoResponsable('{{$presuntoresponsable->documentoPersona}}', 0)">
                    <b><i class="fa fa-edit"></i> Modificar datos del Presunto Responsable</b>
                </a>
                <hr>
                <a href="javascript: void(0)" class="btn btn-danger btn-block" onclick="quitarPresuntoResponsable('{{$presuntoresponsable->documentoPersona}}')">
                    <b><i class="fa fa-trash"></i> Quitar presunto responsable de la queja</b>
                </a>
            </div>
        </div>
    </div>
</div>
@else
    No se encontró el presunto responsable
@endif