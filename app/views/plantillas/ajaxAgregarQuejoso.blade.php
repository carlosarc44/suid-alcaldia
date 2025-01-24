<div class="row">
    <div class="col-xs-3" style="background: #f1f1f1; padding:10px">	
        <div class="box box-info">
            <div class="box-body box-profile">
            <h3 class="profile-username text-center">Queja {{$idQueja}}</h3>
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>Documento o nombre del Quejoso:</b> 
                        <input type="hidden" id="idQuejaAgregarQuejoso" value="{{$idQueja}}">
                        <input type="text" class="form-control" id="docQuejoso" name="docQuejoso" style="width:100%; margin-top:6px" autofocus autocomplete="new-password" placeholder="Busque por documento o nombre"/>                  
                    </li>
                </ul>
            </div>
            <!-- /.box-body -->
        </div>
        
        <div class="ajax-listaQuejosos_1_{{$idQueja}}">
            <!--ajax -->
        </div>
    </div> 
    <div class="col-xs-9">
        <div class="form-group">            
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title"> <i class="fa fa-arrow-left"></i> Para agregar un quejoso escriba su documento o nombre en el campo de búsqueda</h3>
                        </div>
                        <div class="box-body">
                            <div id="ajax-docQuejoso">
                                <!-- ajax -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>