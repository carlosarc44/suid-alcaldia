{{''; $completa = Util::verificarProgresoEtapas($vigencia, $idRadicado, 1)}}
<li class="li {{$completa}}">
    <div style="position:relative">
        <div class="timestamp">
            <span class="date">
                {{''; $fechaInicio = Util::traerFechaEtapa($vigencia, $idRadicado, 1)}}
                @if ($fechaInicio != '')
                    <i class="fa fa-calendar"></i> Inició el <br>
                    {{$fechaInicio}}
                @endif
            <span>
        </div>
        @if ($completa == 'complete')
            <span class="check">
                <i class="fa fa-check"></i>
            </span>
        @else
            <span class="check">
                <i class="fa fa-clock-o faa-flash animated faa-slow"></i>
            </span>
        @endif
        <div class="status">
            <h4>Indagación Previa</h4>						
        </div>
        @if ($fechaInicio != '')
            <button class="btn btn-success btn-xs" onclick="modalCambiarFecha(1)"><i class="fa fa-random"></i> Cambiar Fecha</button>
        @endif
    </div>
    
</li>
{{''; $completa = Util::verificarProgresoEtapas($vigencia, $idRadicado, 2)}}
<li class="li {{$completa}}">
    <div style="position:relative">
        <div class="timestamp">
            <span class="date">
                {{''; $fechaInicio = Util::traerFechaEtapa($vigencia, $idRadicado, 2)}}
                @if ($fechaInicio != '')
                    <i class="fa fa-calendar"></i> Inició el <br>
                    {{$fechaInicio}}
                @endif
            </span>							
        </div>
        @if ($completa == 'complete')
            <span class="check">
                <i class="fa fa-check"></i>
            </span>
        @else
            <span class="check">
                <i class="fa fa-clock-o faa-flash animated faa-slow"></i>
            </span>
        @endif
        <div class="status">
            <h4> Investigación Discip.. </h4>
        </div>
        @if ($fechaInicio != '')
            <button class="btn btn-success btn-xs" onclick="modalCambiarFecha(2)"><i class="fa fa-random"></i> Cambiar Fecha</button>
        @endif
    </div>
</li>
{{''; $completa = Util::verificarProgresoEtapas($vigencia, $idRadicado, 3)}}
<li class="li {{$completa}}">
    <div style="position:relative">
        <div class="timestamp">
            <span class="date">
                {{''; $fechaInicio = Util::traerFechaEtapa($vigencia, $idRadicado, 3)}}
                @if ($fechaInicio != '')
                    <i class="fa fa-calendar"></i> Inició el <br>
                    {{$fechaInicio}}
                @endif
            <span>							
        </div>
        @if ($completa == 'complete')
            <span class="check">
                <i class="fa fa-check"></i>
            </span>
        @else
            <span class="check">
                <i class="fa fa-clock-o faa-flash animated faa-slow"></i>
            </span>
        @endif
    </div>
    <div class="status">
            <h4> Prórroga Investig.. </h4>
    </div>
    @if ($fechaInicio != '')
        <button class="btn btn-success btn-xs" onclick="modalCambiarFecha(3)"><i class="fa fa-random"></i> Cambiar Fecha</button>
    @endif
</li>
{{''; $completa = Util::verificarProgresoEtapas($vigencia, $idRadicado, 4)}}
<li class="li {{$completa}}">
    <div style="position:relative">
        <div class="timestamp">
            <span class="date">
                {{''; $fechaInicio = Util::traerFechaEtapa($vigencia, $idRadicado, 4)}}
                @if ($fechaInicio != '')
                    <i class="fa fa-calendar"></i> Inició el <br>
                    {{$fechaInicio}}
                @endif
            </span>
        </div>
        @if ($completa == 'complete')
            <span class="check">
                <i class="fa fa-check"></i>
            </span>
        @else
            <span class="check">
                <i class="fa fa-clock-o faa-flash animated faa-slow"></i>
            </span>
        @endif
        <div class="status">
            <h4> Evaluación </h4>
        </div>
        @if ($fechaInicio != '')
            <button class="btn btn-success btn-xs" onclick="modalCambiarFecha(4)"><i class="fa fa-random"></i> Cambiar Fecha</button>
        @endif
    </div>
</li>
{{''; $completa = Util::verificarProgresoEtapas($vigencia, $idRadicado, 5)}}
<li class="li {{$completa}}">
    <div style="position:relative">
        <div class="timestamp">
            <span class="date">
                {{''; $fechaInicio = Util::traerFechaEtapa($vigencia, $idRadicado, 5)}}
                @if ($fechaInicio != '')
                    <i class="fa fa-calendar"></i> Inició el <br>
                    {{$fechaInicio}}
                @endif
            </span>
        </div>
        @if ($completa == 'complete')
            <span class="check">
                <i class="fa fa-check"></i>
            </span>
        @else
            <span class="check">
                <i class="fa fa-clock-o faa-flash animated faa-slow"></i>
            </span>
        @endif
        <div class="status">
            <h4> Pliego Cargos </h4>
        </div>
        @if ($fechaInicio != '')
            <button class="btn btn-success btn-xs" onclick="modalCambiarFecha(5)"><i class="fa fa-random"></i> Cambiar Fecha</button>
        @endif
    </div>
</li>
{{''; $completa = Util::verificarProgresoEtapas($vigencia, $idRadicado, 6)}}
<li class="li {{$completa}}">
    <div style="position:relative">
        <div class="timestamp">
            <span class="date">
                {{''; $fechaInicio = Util::traerFechaEtapa($vigencia, $idRadicado, 6)}}
                @if ($fechaInicio != '')
                    <i class="fa fa-calendar"></i> Inició el <br>
                    {{$fechaInicio}}
                @endif
            </span>
        </div>
        @if ($completa == 'complete')
            <span class="check">
                <i class="fa fa-check"></i>
            </span>
        @else
            <span class="check">
                <i class="fa fa-clock-o faa-flash animated faa-slow"></i>
            </span>
        @endif
        <div class="status">
            <h4> Prueba Descargos </h4>
        </div>
        @if ($fechaInicio != '')
            <button class="btn btn-success btn-xs" onclick="modalCambiarFecha(6)"><i class="fa fa-random"></i> Cambiar Fecha</button>
        @endif
    </div>
</li>
{{''; $completa = Util::verificarProgresoEtapas($vigencia, $idRadicado, 7)}}
<li class="li {{$completa}}">
    <div style="position:relative">
        <div class="timestamp">
            <span class="date">
                {{''; $fechaInicio = Util::traerFechaEtapa($vigencia, $idRadicado, 7)}}
                @if ($fechaInicio != '')
                    <i class="fa fa-calendar"></i> Inició el <br>
                    {{$fechaInicio}}
                @endif
            </span>
        </div>
        @if ($completa == 'complete')
            <span class="check">
                <i class="fa fa-check"></i>
            </span>
        @else
            <span class="check">
                <i class="fa fa-clock-o faa-flash animated faa-slow"></i>
            </span>
        @endif
        <div class="status">
            <h4> Alegatos Conclusión </h4>
        </div>
        @if ($fechaInicio != '')
            <button class="btn btn-success btn-xs" onclick="modalCambiarFecha(7)"><i class="fa fa-random"></i> Cambiar Fecha</button>
        @endif
    </div>
</li>
{{''; $completa = Util::verificarProgresoEtapas($vigencia, $idRadicado, 8)}}
<li class="li {{$completa}}">
    <div style="position:relative">
        <div class="timestamp">
            <span class="date">
                {{''; $fechaInicio = Util::traerFechaEtapa($vigencia, $idRadicado, 8)}}
                @if ($fechaInicio != '')
                    <i class="fa fa-calendar"></i> Inició el <br>
                    {{$fechaInicio}}
                @endif
            </span>
        </div>
        @if ($completa == 'complete')
            <span class="check">
                <i class="fa fa-check"></i>
            </span>
        @else
            <span class="check">
                <i class="fa fa-clock-o faa-flash animated faa-slow"></i>
            </span>
        @endif
        <div class="status">
            <h4> Fallo</h4>
        </div>
        @if ($fechaInicio != '')
            <button class="btn btn-success btn-xs" onclick="modalCambiarFecha(8)"><i class="fa fa-random"></i> Cambiar Fecha</button>
        @endif
    </div>
</li>
{{''; $completa = Util::verificarProgresoEtapas($vigencia, $idRadicado, 10)}}
<li class="li {{$completa}}">
    <div style="position:relative">
        <div class="timestamp">
            <span class="date">
                {{''; $fechaInicio = Util::traerFechaEtapa($vigencia, $idRadicado, 10)}}
                @if ($fechaInicio != '')
                    <i class="fa fa-calendar"></i> Inició el <br>
                    {{$fechaInicio}}
                @endif
            </span>
        </div>
        @if ($completa == 'complete')
            <span class="check">
                <i class="fa fa-check"></i>
            </span>
        @else
            <span class="check">
                <i class="fa fa-clock-o faa-flash animated faa-slow"></i>
            </span>
        @endif
        <div class="status">
            <h4> Archivo </h4>
        </div>
        @if ($fechaInicio != '')
            <button class="btn btn-success btn-xs" onclick="modalCambiarFecha(10)"><i class="fa fa-random"></i> Cambiar Fecha</button>
        @endif
    </div>
</li>
{{''; $completa = Util::verificarProgresoEtapas($vigencia, $idRadicado, 9)}}
<li class="li {{$completa}}">
    <div style="position:relative">
        <div class="timestamp">
            <span class="date">
                {{''; $fechaInicio = Util::traerFechaEtapa($vigencia, $idRadicado, 9)}}
                @if ($fechaInicio != '')
                    <i class="fa fa-calendar"></i> Inició el <br>
                    {{$fechaInicio}}
                @endif
            </span>
        </div>
        @if ($completa == 'complete')
            <span class="check">
                <i class="fa fa-check"></i>
            </span>
        @else
            <span class="check">
                <i class="fa fa-clock-o faa-flash animated faa-slow"></i>
            </span>
        @endif
        <div class="status">
            <h4> Inhibitorio </h4>
        </div>
        @if ($fechaInicio != '')
            <button class="btn btn-success btn-xs" onclick="modalCambiarFecha(9)"><i class="fa fa-random"></i> Cambiar Fecha</button>
        @endif
    </div>
</li>