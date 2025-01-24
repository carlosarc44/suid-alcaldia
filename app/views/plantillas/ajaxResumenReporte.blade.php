<div class="row">
    <div class="col-sm-10">
        <span style="color:#00a65a">
            {{$texto}}
        </span>
    </div>
    <div class="col-sm-2">
        @if ($totalQuejas > 0)
            <a href="{{ asset('procesos/reporte/excel/'.$cadenaVectorDependencias.'/'.$cadenaVectorFaltas.'/'.$estado.'/'.$idAbogado.'/'.$cadenaVectorVigencias.'/'.$cadenaVectorEtapas) }}" class="btn btn-success pull-right" style="margin-top:0px; margin-bottom:10px; color:#fff">
                <i class="fa fa-cloud-download"></i>  Generar Excel
            </a>
        @endif
    </div>
</div>