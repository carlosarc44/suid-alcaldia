<?php
class Util
{
	//método estático privado
	public static function verificarNoHabil($fecha)
	{
		$festivo = NoHabil::where('fechaNoHabil', '=', $fecha)->get();

		if(count($festivo) > 0)
		{
			return 1;
		}
		else
		{
			$suspensionInicio = '2020-03-24';
			$suspensionFin = '2020-07-15';

			if($fecha >= $suspensionInicio && $fecha <= $suspensionFin) 
			{
				return 1;
			} 
			else 
			{
				return 0;
			}  
		}
	}

	public static function calcularFechaFinalEtapa($idEtapa)
	{
		//Busca la etapa
		$etapa = Etapa::find($idEtapa);

		//Almacena el plazo de la etapa
		$plazo = $etapa->plazoEtapa;
		//Almacena si es hábil el plazo
		$habil = $etapa->habil;

		if ($habil == 1) //1 Si el plazo es en días hábiles
        {
        	$contador = 0;
        	$fecha = date("Y-m-d");
            $fecha2 = "";

            while ($contador < $plazo)
            {
                $fecha = date('Y-m-d', strtotime("$fecha + 1 day"));
                $fecha2 = $fecha;

                //Verifica si la fecha es festiva
                if (Util::verificarNoHabil($fecha2) == 0)
                {
                    $contador++;
                }
            }

            return $fecha;
        }
        else
        {
			$suspensionInicio = '2020-03-24';
			$suspensionFin = '2020-07-15';

			$contador = 0;
        	$fecha = date("Y-m-d");
            $fecha2 = "";

            while ($contador < $plazo)
            {
                $fecha = date('Y-m-d', strtotime("$fecha + 1 day"));
				$fecha2 = $fecha;
				
				if(($fecha2 >= $suspensionInicio) && ($fecha2 <= $suspensionFin)) 
				{

				}
				else
                {
                    $contador++;
                }
            }

            return $fecha;
        }
	}

	public static function calcularFechaFinalEtapaModif($fecha, $idEtapa)
	{
		//Busca la etapa
		$etapa = Etapa::find($idEtapa);

		//Almacena el plazo de la etapa
		$plazo = $etapa->plazoEtapa;
		//Almacena si es hábil el plazo
		$habil = $etapa->habil;

		if ($habil == 1) //1 Si el plazo es en días hábiles
        {
        	$contador = 0;
            $fecha2 = "";

            while ($contador < $plazo)
            {
                $fecha = date('Y-m-d', strtotime("$fecha + 1 day"));
                $fecha2 = $fecha;

                //Verifica si la fecha es festiva
                if (Util::verificarNoHabil($fecha2) == 0)
                {
                    $contador++;
                }
            }

            return $fecha;
        }
        else
        {
			$suspensionInicio = '2020-03-24';
			$suspensionFin = '2020-07-15';

			$contador = 0;
            $fecha2 = "";

            while ($contador < $plazo)
            {
                $fecha = date('Y-m-d', strtotime("$fecha + 1 day"));
				$fecha2 = $fecha;
				
				if(($fecha2 >= $suspensionInicio) && ($fecha2 <= $suspensionFin)) 
				{

				}
				else
                {
                    $contador++;
                }
            }

            return $fecha;
        }
	}


	public static function obtenerFechaFinalHabiles($fecha, $dias, $habil)
	{
		if ($habil == 1) //1 Si el plazo es en días hábiles
        {
        	$contador = 0;
            $fecha2 = "";

            while ($contador < $dias)
            {
                $fecha = date('Y-m-d', strtotime("$fecha + 1 day"));
                $fecha2 = $fecha;

                //Verifica si la fecha es festiva
                if (Util::verificarNoHabil($fecha2) == 0)
                {
                    $contador++;
                }
            }

            return $fecha;
        }
        else
        {
			$suspensionInicio = '2020-03-24';
			$suspensionFin = '2020-07-15';

			$contador = 0;
            $fecha2 = "";

            while ($contador < $dias)
            {
                $fecha = date('Y-m-d', strtotime("$fecha + 1 day"));
				$fecha2 = $fecha;
				
				if(($fecha2 >= $suspensionInicio) && ($fecha2 <= $suspensionFin)) 
				{

				}
				else
                {
                    $contador++;
                }
            }

            return $fecha;
        }
	}

	public static function formatearFecha($fecha)
	{
		$dias = array('','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
		$meses = array('','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre');
		$dia = $dias[date('N', strtotime($fecha))];
		$mes = $meses[(int)substr($fecha, 5,2)];
		$numDia = substr($fecha, 8,2);
		$anio = substr($fecha, 0,4);

		$final = $dia." ".$numDia." de ".$mes." de ".$anio;

		return $final;
	}

	public static function formatearFechaCorta($fecha)
	{
		$meses = array('','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic');
		$mes = $meses[(int)substr($fecha, 5,2)];
		$numDia = substr($fecha, 8,2);
		$anio = substr($fecha, 0,4);

		$final = $numDia." ".$mes." ".$anio;

		return $final;
	}

	public static function traerNumeroQueja($vigencia, $idRadicado)
	{
		$quejas = DB::table('acumulaqueja')
				  ->where('Radicado_idRadicado', '=', $idRadicado)
				  ->where('Radicado_vigencia', '=', $vigencia)
				  ->get();
		if(count($quejas) > 0)
		{
			$cadena = "";
			foreach ($quejas as $queja)
			{
				$cadena = $cadena."<li>".$queja->Queja_idQueja."</li>";
			}
		}
		else
		{
			$cadena = "<li>No encontrada</li>";
		}

		return $cadena;
	}

	public static function traerFechaQuejaPortada($vigencia, $idRadicado)
	{
		$quejas = DB::table('acumulaqueja')
				  ->join('queja', 'acumulaqueja.Queja_idQueja', '=', 'queja.idQueja')
				  ->where('Radicado_idRadicado', '=', $idRadicado)
				  ->where('Radicado_vigencia', '=', $vigencia)
				  ->get();
		if(count($quejas) > 0)
		{
			$cadena = "";
			foreach ($quejas as $queja)
			{
				$cadena = $queja->fechaQueja;
			}
		}
		else
		{
			$cadena = "<li>No encontrada</li>";
		}

		return $cadena;
	}

	public static function traerFechaRecepQuejaPortada($vigencia, $idRadicado)
	{
		$quejas = DB::table('acumulaqueja')
				  ->join('queja', 'acumulaqueja.Queja_idQueja', '=', 'queja.idQueja')
				  ->where('Radicado_idRadicado', '=', $idRadicado)
				  ->where('Radicado_vigencia', '=', $vigencia)
				  ->get();
		if(count($quejas) > 0)
		{
			$cadena = "";
			foreach ($quejas as $queja)
			{
				$cadena = $queja->fechaRecepcionQueja;
			}
		}
		else
		{
			$cadena = "<li>No encontrada</li>";
		}

		return $cadena;
	}

	public static function traerQuejasProceso($vigencia, $idRadicado)
	{
		$quejas = DB::table('acumulaqueja')
				  ->where('Radicado_idRadicado', '=', $idRadicado)
				  ->where('Radicado_vigencia', '=', $vigencia)
				  ->get();

	    if(count($quejas)> 0)
		{
			foreach($quejas as $queja)
			{
			  $numerosQuejas[] = $queja->Queja_idQueja;
			}
		}
		else
		{
			$numerosQuejas[] = null;
		}

		return $numerosQuejas;
	}

	public static function traerPrimeraQuejaProceso($vigencia, $idRadicado)
	{
		$queja = DB::table('acumulaqueja')
			      ->select('Queja_idQueja')
				   ->where('Radicado_idRadicado', '=', $idRadicado)
				   ->where('Radicado_vigencia', '=', $vigencia)
				   ->first();

		return $queja->Queja_idQueja;
	}

	public static function traerQuejosos($vigencia, $idRadicado)
	{
		$quejas = DB::table('acumulaqueja')
				  ->join('queja', 'acumulaqueja.Queja_idQueja', '=', 'queja.idQueja')
				  ->where('Radicado_idRadicado', '=', $idRadicado)
				  ->where('Radicado_vigencia', '=', $vigencia)
				  ->get();

		if(count($quejas) > 0)
		{
			$cadena = "";
			foreach ($quejas as $queja)
			{
				if($queja->OrigenQueja_idOrigenQueja == 1)//1 Queja
	   			{
					$quejosos = Util::traerQuejososPorQueja($queja->Queja_idQueja);					

					if(count($quejosos) > 0)
					{
						foreach ($quejosos as $quejoso)
						{
							$cadena = $cadena."<li>".$quejoso->nombre."</li>";
						}
					}
					else
					{
						//$cadena = $cadena."<li>Anónimo</li>";
						$cadena = $cadena.'<div class="alert alert-white alert-dismissible" style="padding:4px; margin:0; text-align: center;">
									                Anónimo / desconocido.
									            </div>';
					}
				}
				else//2 Informe
				{
					$informantes = DB::table('informante')
							->join('entidad', 'informante.Entidad_idEntidad', '=', 'entidad.idEntidad')
							->where('Queja_idQueja', '=', $queja->Queja_idQueja)
							->get();

					if(count($informantes) > 0)
					{
						foreach ($informantes as $informante)
						{
							$cadena = $cadena."<li>".$informante->nombreEntidad."</li>";
						}
					}
				}
			}
		}
		else
		{
			$cadena = "<li>Queja no encontrada</li>";
		}

		return $cadena;
	}

	public static function traerQuejososPortada($vigencia, $idRadicado)
	{
		$quejas = DB::table('acumulaqueja')
				  ->where('Radicado_idRadicado', '=', $idRadicado)
				  ->where('Radicado_vigencia', '=', $vigencia)
				  ->orderBy('Queja_idQueja', 'desc')
				  ->get();

		if(count($quejas) > 0)
		{
			$quejoso = "";
			foreach ($quejas as $queja)
			{
				$quejosos = Util::traerQuejososPorQueja($queja->Queja_idQueja);

				//Quejoso
				if (count($quejosos) > 0)
				{
					if (count($quejosos) == 1)
					{
						$quejoso = $quejosos[0]->nombre;
					}
					else
					{
						$quejoso = $quejosos[0]->nombre." y ".(count($quejosos)-1)." más.";
					}
				}
				else
				{
					$quejoso = "Anónimo";
				}
				//---------------------------------------------------------------------
			}
		}
		else
		{
			$quejoso = "<li>Queja no encontrada</li>";
		}

		return $quejoso;
	}

	public static function traerDocumentoQuejoso($vigencia, $idRadicado)
	{
		$quejas = DB::table('acumulaqueja')
				  ->where('Radicado_idRadicado', '=', $idRadicado)
				  ->where('Radicado_vigencia', '=', $vigencia)
				  ->orderBy('Queja_idQueja', 'desc')
				  ->get();

		if(count($quejas) > 0)
		{
			$docQuejoso = "";
			foreach ($quejas as $queja)
			{
				$quejosos = Util::traerQuejososPorQueja($queja->Queja_idQueja);
	
				//Quejoso
				if (count($quejosos) > 0)
				{

					$docQuejoso = $quejosos[0]->documentoPersona;

				}
				else
				{
					$docQuejoso = "Anónimo";
				}
				//---------------------------------------------------------------------
			}
		}
		else
		{
			$docQuejoso = "<li>Queja no encontrada</li>";
		}

		return $docQuejoso;
	}


	public static function traerPresuntosResponsablesPortada($vigencia, $idRadicado)
	{
		$quejas = DB::table('acumulaqueja')
				  ->where('Radicado_idRadicado', '=', $idRadicado)
				  ->where('Radicado_vigencia', '=', $vigencia)
				  ->orderBy('Queja_idQueja', 'desc')
				  ->get();

		if(count($quejas) > 0)
		{
			$presuntoResponsable = "";


			foreach ($quejas as $queja)
			{
				$presuntos = Util::traerPresuntosResponsablesPorQueja($queja->Queja_idQueja);			

				//Presuntos Responsables
				if (count($presuntos) > 0)
				{
					if (count($presuntos) == 1)
					{
						$presuntoResponsable = $presuntos[0]->nombre;
					}
					else
					{
						$presuntoResponsable = $presuntos[0]->nombre." y ".(count($presuntos)-1)." más.";
					}
				}
				else
				{
					$presuntoResponsable = "Por determinar";
				}
			}
		}
		else
		{
			$presuntoResponsable = "<li>Queja no encontrada</li>";
		}

		return $presuntoResponsable;
	}

	public static function verificarProgresoEtapas($vigencia, $idRadicado, $idEtapa)
	{
		$etapas = DB::table('etapasproceso')
				   ->select('actual', 'activo')
				     ->join('radicado', function($join) {
						$join->on('etapasproceso.Radicado_idRadicado', '=', 'radicado.idRadicado')
						 ->on('etapasproceso.Radicado_vigencia', '=', 'radicado.vigencia');
					})
				->where('Radicado_idRadicado', '=', $idRadicado)
				->where('Radicado_vigencia', '=', $vigencia)
				->where('etapasproceso.Etapa_idEtapa', '=', $idEtapa)
                ->orderBy('idEtapaProceso', 'desc')
				->first();

  		if(count($etapas) > 0)
  		{
  			if($etapas->actual == 1)
  			{
				if($etapas->activo == 0)
				{
					$estado = "complete";	
				}
				else 
				{
					$estado = "actual";
				}
  			}
  			else
  			{
  				$estado = "complete";
  			}
  		}
  		else
  		{
  			$estado = "";
  		}

  		return $estado;
	}

	public static function verificarPasoEtapas($vigencia, $idRadicado, $idEtapa)
	{
		$paso = 0;

		$etapas = DB::table('etapasproceso')
		           ->select('actual', 'activo')
			         ->join('radicado', function($join) {
						$join->on('etapasproceso.Radicado_idRadicado', '=', 'radicado.idRadicado')
						 ->on('etapasproceso.Radicado_vigencia', '=', 'radicado.vigencia');
					})
			        ->where('Radicado_idRadicado', '=', $idRadicado)
			        ->where('Radicado_vigencia', '=', $vigencia)
			        ->where('etapasproceso.Etapa_idEtapa', '=', $idEtapa)
                  ->orderBy('idEtapaProceso', 'desc')
				    ->first();

  		if(count($etapas) > 0)
  		{
  			if($etapas->actual == 0)
  			{
				$paso = 1;
  			}
  		}
  		
  		return $paso;
	}

	public static function traerFechaEtapa($vigencia, $idRadicado, $idEtapa)
	{
  		$etapas = DB::table('etapasproceso')
				    ->where('Radicado_idRadicado', '=', $idRadicado)
				    ->where('Radicado_vigencia', '=', $vigencia)
				    ->where('Etapa_idEtapa', '=', $idEtapa)
				  ->orderBy('idEtapaProceso', 'desc')
                    ->first();

  		if(count($etapas) > 0)
  		{
  			$fecha = Util::formatearFechaCorta($etapas->fechaEtapa);
  		}
  		else
  		{
  			$fecha = '';
  		}

  		return $fecha;
	}

	public static function traerEtapasExcel($vigencia, $idRadicado)
	{
		  $etapas = DB::table('etapasproceso')
		  		     ->select('Etapa_idEtapa', 'fechaEtapa')
				  	  ->where('Radicado_idRadicado', '=', $idRadicado)
					  ->where('Radicado_vigencia', '=', $vigencia)
					  ->get();

  		return $etapas;
	}

	public static function traerWidgetPrescripcion($vigencia, $idRadicado, $edicion)
	{
		$radicado = DB::table('radicado')
				 	 ->select('fechaHechos', 'activo')
					  ->where('vigencia', $vigencia)
					  ->where('idRadicado', $idRadicado)
					  ->first();

		if ($radicado->fechaHechos == null) 
		{
			$fechaHechos = 'Desconocida';
			$fechaPrescripcion = 'Desconocida';
			$aniosPasaron = '?';
			$diasPasaron = '<span style="color:#ff0000">No ha determinado la fecha de los hechos</span>';
		} 
		else 
		{
			$fechaHechos = Util::formatearFechaCorta($radicado->fechaHechos);
			$fechaPrescripcion = Util::formatearFechaCorta(date("Y-m-d", strtotime("+5 year", strtotime($radicado->fechaHechos))));
			
			$dias = Util::diasPasaronPrescripcion($vigencia, $idRadicado);
			$aniosPasaron = round(($dias*5)/1825, 2);
			$diasPasaron = 'Pasaron '.$dias.' días de 1825 <br/> <span style="color:#3c8dbc">('.$aniosPasaron.' años de 5)</span>';
		}
		
		$porcentaje = Util::porcentajePrescripcion($vigencia, $idRadicado);

		if ($edicion == 1) 
		{
			$display = 'block';
		} 
		else 
		{
			$display = 'none';
		}

		if ($radicado->activo == 1) 
		{
			$display2 = 'block';
		} 
		else 
		{
			$display2 = 'none';
		}

		$widget =   '<div class="box box-info">
						<div class="box-body box-profile" style="text-align:center">  
							<button class="btn btn-danger btn-xs" onclick="modalCambiarFechaHechos()" style="position: absolute;right:4px; font-weight:600; display:'.$display.'">
								<i class="fa fa-edit"></i> Editar fecha hechos
							</button>
							<h3 class="profile-username text-center" style="font-size:19px">Prescripción</h3>
							<div style="width:100%; text-align:center; display:'.$display2.'">
								<div class="progress-circle" data-progress="'.round($porcentaje).'"></div>							
							</div>
							<hr/>
							<p class="text-muted text-center" style="font-size: 12px">
								<div class="row">
									<div class="col-sm-6" style="border-right: 1px solid #ccc;">
										<div class="fh" style="">
											<span class="fhval">
												<i class="fa fa-calendar"></i> 
												'.$fechaHechos.'
											</span>
											<br>
											<span class="fhtxt">
												Fecha hechos
											</span>											
										</div>
									</div>
									<div class="col-sm-6">
										<div class="fh" style="">
											<span class="fhval">
												<i class="fa fa-calendar"></i> 
												'.$fechaPrescripcion.'
											</span>
											<br>
											<span class="fhtxt">
												Fecha prescripción
											</span>											
										</div>
									</div>
								</div>	
								<hr/>
								<div style="width:100%; text-align:center; display:'.$display2.'">
									<span class="completados">'.$diasPasaron.'</span>							
								</div>
							</p>						
						</div>
					</div>';
			
		return $widget;
	}

	public static function traerWidgetFaltas($vigencia, $idRadicado, $edicion)
	{
		$radicado = DB::table('radicado')
				 	 ->select('faltas_idFalta', 'falta', 'activo')
				   ->leftJoin('faltas', 'radicado.faltas_idFalta', '=', 'faltas.idFalta')
					  ->where('vigencia', $vigencia)
					  ->where('idRadicado', $idRadicado)
					  ->first();

		if ($radicado->faltas_idFalta == null) 
		{
			$falta = '<span style="color:#dd4b39;background: rgba(255, 255, 255, 0.88);padding: 4px;border-radius: 6px;text-align: center;font-weight: 600;font-size:0.95em">No ha determinado la falta asociada</span>';
		} 
		else 
		{
			$falta = $radicado->falta;
		}
		
		if ($edicion == 1) 
		{
			$display = 'block';
		} 
		else 
		{
			$display = 'none';
		}

		$widget =  '<div class="small-box bg-aqua">
						<div class="inner">
						<h4 style="text-align:center;font-size: 0.99em;font-weight:600">'.$falta.'</h4>
						<p style="text-align:center">Faltas Comunes</p>
						</div>
						<div class="icon">
						<i class="fa fa-exclamation-circle"></i>
						</div>
						<a href="#" class="small-box-footer" style="display:'.$display.'" onclick="modalCambiarFaltasComunes()">
						Cambiar la falta asociada a este proceso <i class="fa fa-edit"></i>
						</a>
					</div>';

		return $widget;
	}

	public static function traerWidgetProceso($vigencia, $idRadicado)
	{
		$etapa = DB::table('etapasproceso')
		  		->select('nombreEtapa', 'fechaEtapa', 'fechaFinalEtapa', 'Etapa_idEtapa', 'habil')
  				->join('etapa', 'etapasproceso.Etapa_idEtapa', '=', 'etapa.idEtapa')
				->where('Radicado_idRadicado', '=', $idRadicado)
				->where('Radicado_vigencia', '=', $vigencia)
				->where('actual', '=', 1)
                ->orderBy('idEtapaProceso', 'desc')
				->first();

		if (count($etapa) > 0) 
		{
			if(in_array($etapa->Etapa_idEtapa, [9, 10, 14, 12, 18, 19]))//14 Finalizados
			{
				$widget =  '<div class="info-box bg-gray">
								<span class="info-box-icon"><i class="fa fa-calendar"></i></span>
								<div class="info-box-content">
									<span class="info-box-number">FINALIZADO</span>
									<div class="progress">
										<div class="progress-bar" style="width: 100%"></div>
									</div>
									<span class="progress-description">
										Finalizado el '.date_format(date_create($etapa->fechaEtapa),"d/m/Y").'
									</span>
									<span class="progress-description">
										en '.$etapa->nombreEtapa.'
									</span>
								</div>
							</div>';
			}
			else
			{
				if(count($etapa) > 0)
				{
					//Fecha inicial etapa
					$fechaInicial = $etapa->fechaEtapa;
					//Fecha final etapa
					$fechaFinal = $etapa->fechaFinalEtapa;

					//Nombre de la etapa actual
					$nombreEtapa = $etapa->nombreEtapa;

					//Dias totales entre el inicio y el fin de la etapa
					$diasTotal = Util::diasTranscurridos($fechaInicial, $fechaFinal, $etapa->habil);

					//Dias que han transcurrido para llegar a la fecha final de la etapa
					$diasPasaron = Util::diasTranscurridos($fechaInicial, date('Y-m-d'), $etapa->habil);
					
					//Dias que faltan para llegar a la fecha final de la etapa
					$diasRestan = Util::diasTranscurridos(date('Y-m-d'), $fechaFinal, $etapa->habil);

					//Porcentaje de avance
					//$porcentaje = abs(ceil(($diasPasaron/$diasTotal)*100));

					//Porcentaje de avance				
					if($diasTotal > 0)
					{
						$porcentaje = abs(ceil(($diasPasaron/$diasTotal)*100));
					}
					else
					{
						$porcentaje = 100;
					}

					if ($porcentaje > 100) {
						$porcentajeBarra = 100;
					}
					else {
						$porcentajeBarra = $porcentaje;
					}

					//Vencimiento en días
					if ($fechaFinal == date('Y-m-d')) 
					{
						$texto = "vence hoy";
					}
					else if($diasRestan == 0)
					{
						$diasVencidos = $diasTotal - $diasPasaron;
						$texto = "venció hace ".abs($diasVencidos)." días";
					}
					else if($diasRestan == 1)
					{
						$texto = "vence mañana";
					}
					else if($diasRestan > 1)
					{
						if (date('Y-m-d') > $fechaFinal) 
						{
							$texto = "venció hace ".$diasRestan." días";
						}
						else
						{
							$texto = "vence en ".$diasRestan." días";
						}
					}

					if($porcentaje <= 70)
					{
						$bk = '#28a368';
						$bar =  '#1fdb1a';
					}
					else if($porcentaje > 70 && $porcentaje <= 100)
					{
						$bk = '#ff7b26';
						$bar =  '#f39c12';
					}
					else if($porcentaje > 100)
					{
						$bk = '#ff2f3b';
						$bar =  'rgba(255,0,0, 0.5)';
					}

					$widget =  '<div class="info-box" style="background-color:'.$bk.';color:#f0f0f0">
									<span class="info-box-icon" style="height:100%"><i class="fa fa-calendar"></i></span>
									<div class="info-box-content">
										<span class="info-box-number" style="overflow:hidden;white-space:nowrap;text-overflow:ellipsis;width:90%;">'.$nombreEtapa.'</span>
										<div class="progress" style="height:18px">
											<div class="progress-bar" style="background:'.$bar.';font-weight:500;width: '.$porcentajeBarra.'%">'.$porcentaje.'%</div>
										</div>
										<span class="progress-description">
											Etapa '.$texto.' <br> <span style="font-size:0.9em">'.date_format(date_create($fechaInicial),"d/m/Y")." a ".date_format(date_create($fechaFinal),"d/m/Y").'</span>
										</span>
									</div>
								</div>';
				}
				else
				{
					$widget = '<span>---------</span>';
				}
			}
		}
		else
		{
			$widget = '<span>---------</span>';
		}

		return $widget;
	}

	public static function traerVencimientoProceso($vigencia, $idRadicado)
	{
		$etapa = DB::table('etapasproceso')
		  		  ->select('Radicado_idRadicado', 'Radicado_vigencia', 'nombreEtapa', 'fechaEtapa', 'fechaFinalEtapa', 'Etapa_idEtapa', 'habil', 'plazoEtapa', 'tipoEtapa', 'idTipoEtapa')
  				->leftJoin('etapa', 'etapasproceso.Etapa_idEtapa', '=', 'etapa.idEtapa')
				->leftJoin('tiposetapa', 'etapa.tiposetapa_idTipoEtapa', '=', 'tiposetapa.idTipoEtapa')
				   ->where('Radicado_idRadicado', '=', $idRadicado)
				   ->where('Radicado_vigencia', '=', $vigencia)
				   ->where('actual', '=', 1)
                 ->orderBy('idEtapaProceso', 'desc')
				   ->first();				

		if(in_array($etapa->Etapa_idEtapa, [8, 9, 10, 12, 14, 18, 19]))//14 Finalizados
	  	{
	  		$widget =  '<div class="info-box bg-gray" style="background:#9b9da0 !important">
				            <span class="info-box-icon"><i class="fa fa-calendar"></i></span>
				            <div class="info-box-content">
					            <span class="info-box-number">FINALIZADO</span>
		          				<div class="progress">
		            				<div class="progress-bar" style="width: 100%"></div>
		          				</div>
		            			<span class="progress-description">
		                			Finalizado el '.date_format(date_create($etapa->fechaEtapa),"d/m/Y").'
								</span>
								<span class="progress-description">
		                			en '.$etapa->nombreEtapa.'
		                		</span>
	                		</div>
				        </div>';
	  	}
	  	else
	  	{
	  		if(count($etapa) > 0)
	  		{
				  //Fecha inicial etapa
	  			$fechaInicial = $etapa->fechaEtapa;
				
	  			//Fecha final etapa
	  			$fechaFinal = $etapa->fechaFinalEtapa;

	  			//Nombre de la etapa actual
	  			$nombreEtapa = $etapa->nombreEtapa;
				  
				//Nombre de la fase de la etapa actual
	  			$faseEtapa = $etapa->tipoEtapa;
				$idTipoEtapa = $etapa->idTipoEtapa;

	  			//Dias totales entre el inicio y el fin de la etapa
				//$diasTotal = Util::diasTranscurridos($fechaInicial, $fechaFinal, $etapa[0]->habil);
				$diasTotal = $etapa->plazoEtapa;				

				//Dias que han transcurrido para llegar a la fecha final de la etapa
				$diasPasaron = Util::diasTranscurridos($fechaInicial, date('Y-m-d'), $etapa->habil);
				
				//Dias que faltan para llegar a la fecha final de la etapa
				$diasRestan = Util::diasTranscurridos(date('Y-m-d'), $fechaFinal, $etapa->habil);


				//Porcentaje de avance
				//$porcentaje = abs(ceil(($diasPasaron/$diasTotal)*100));

				//Porcentaje de avance				
				if($diasTotal > 0)
				{
					$porcentaje = abs(ceil(($diasPasaron/$diasTotal)*100));
				}
				else
				{
					$porcentaje = 100;
				}

				if ($porcentaje > 100) {
					$porcentajeBarra = 100;
				}
				else {
					$porcentajeBarra = $porcentaje;
				}

				//Vencimiento en días
				if ($fechaFinal == date('Y-m-d')) 
				{
					$texto = "vence hoy";
				}
				else if($diasRestan == 0)
				{
					$diasVencidos = $diasTotal - $diasPasaron;
					$texto = "venció hace ".abs($diasVencidos)." días";
				}
				else if($diasRestan == 1)
				{
					$texto = "vence mañana";
				}
				else if($diasRestan > 1)
				{
					$texto = "vence en ".$diasRestan." días";
				}

				if($porcentaje <= 70)
				{
					$bk =  'rgba(0, 166, 90, 0.4)';
					$bar = '#389643';
				}
				else if($porcentaje > 70 && $porcentaje <= 100)
				{
					$bk =  'rgba(243, 156, 18, 0.6)';
					$bar = '#f2a93a';
				}
				else if($porcentaje > 100)
				{
					$bk =  'rgba(255,0,0, 0.5)';
					$bar = '#ff2f3b';
				}

				if($idTipoEtapa == 1)
				{
					$color = '#fbfeff';
				}
				else
				{
					$color = '#81ff00';
				}

				$widget =  '<div class="info-box" style="background-color:'.$bk.'">
								<div style="background-color: #0000005c;text-align: center;color:'.$color.';text-transform: uppercase;font-weight: 600;">'.$faseEtapa.'</div>
								<span class="info-box-icon" style="height:100%"><i class="fa fa-calendar"></i></span>
								<div class="info-box-content">
									<span class="info-box-number" style="overflow:hidden;white-space:nowrap;text-overflow:ellipsis;width:90%;">'.$nombreEtapa.'</span>
									<div class="progress" style="height:18px">
										<div class="progress-bar" style="background:'.$bar.';font-weight:500;width: '.$porcentajeBarra.'%">'.$porcentaje.'%</div>
									</div>
									<span class="progress-description">
										Etapa '.$texto.' <br> <span style="font-size:0.9em">'.date_format(date_create($fechaInicial),"d/m/Y")." a ".date_format(date_create($fechaFinal),"d/m/Y").'</span>
									</span>
								</div>
							</div>';
	  		}
	  		else
	  		{
	  			$widget = '<span>---------</span>';
	  		}
	  	}

  		return $widget;
	}

	public static function traerAutoWidget($vigencia, $idRadicado, $idEtapa)
	{
		$nombreEtapa = Util::traerNombreEtapaId($idEtapa);
  		$auto = DB::table('auto')
				  ->where('Radicado_idRadicado', '=', $idRadicado)
				  ->where('Radicado_vigencia', '=', $vigencia)
				  ->where('Etapa_idEtapa', '=', $idEtapa)
				  ->where('apertura', '=', 1)
					->get();

  		if(count($auto) > 0)
  		{
  			//Número del auto
  			$numeroAuto = $auto[0]->idAuto;
  			//Fecha del auto
  			$fechaAuto = $auto[0]->fechaAuto;
  			//Hora del auto
  			$horaAuto = $auto[0]->horaAuto;
			
			$widget = 	'<div class="info-box">
				        	<span class="info-box-icon bg-green"><i class="fa fa-hashtag"></i></span>
				        	<div class="info-box-content" style="padding:12px 2px 0px 22px;">
				          		<span class="info-box-text">Auto '.$nombreEtapa.'</span>
				          		<span class="info-box-number">'.$numeroAuto.'</span>
				          		<span class="info-box-text">'.Util::formatearFechaCorta($fechaAuto).' '.$horaAuto.'</span>
				        	</div>
				        	<!-- /.info-box-content -->
				      	</div>';
  		}
  		else
  		{
	  		//Verifica si hay una solicitud de auto pendiente
	  		$solicitudes = DB::table('solicitudauto')
						->where('Radicado_vigencia', '=', $vigencia)
						->where('Radicado_idRadicado', '=', $idRadicado)
						->where('Etapa_idEtapa', '=', $idEtapa)
						->where('asignado', '=', 0)
						->get();

			//Si se encontró una solicitud pendiente
			if(count($solicitudes) > 0)
			{
				$widget = '<div class="info-box">
								<span class="info-box-icon bg-yellow"><i class="fa fa-hashtag"></i></span>
								<div class="info-box-content" style="padding:12px 2px 0px 22px;">
							  		<span class="info-box-text">Auto '.$nombreEtapa.'</span>
							  		<span class="info-box-number">Pendiente</span>
							  		<span class="info-box-text">Solicitud enviada</span>
								</div>
								<!-- /.info-box-content -->
							</div>';
			}
			else
			{
	  			$widget = 	'<div class="info-box">
					        	<span class="info-box-icon bg-gray"><i class="fa fa-hashtag"></i></span>
					        	<div class="info-box-content" style="padding:18px 10px 0px 8px;">
									<span class="info-box-text" style="margin-bottom:10px">Auto '.$nombreEtapa.'</span>
					          		<button type="button" style="font-weight:bold" class="btn btn-default btn-block" onclick="solicitarNumero('.$vigencia.', '.$idRadicado.', '.$idEtapa.')">Solicitar Número</button>
					        	</div>
					        	<!-- /.info-box-content -->
					      	</div>';
			}
  		}

  		return $widget;
	}

	public static function traerAutosWidget($vigencia, $idRadicado)
	{
		$widget = '';

		$autos = DB::table('auto')
				  ->select('idEtapa', 'nombreEtapa', 'idAuto', 'fechaAuto')
					->join('etapa', 'auto.etapa_idEtapa', '=', 'etapa.idEtapa')
				   ->where('Radicado_idRadicado', $idRadicado)
				   ->where('Radicado_vigencia', $vigencia)
					 ->get();

		if(count($autos) > 0)
		{
			foreach ($autos as $auto)
			{
				$widget .= '<li>
								<a href="javascript: void(0)" style="cursor:no-drop">
								'.$auto->nombreEtapa.' <span class="pull-right badge bg-green">N° '.$auto->idAuto.' del '.date_format(date_create($auto->fechaAuto), "d/m/Y").'</span>
								</a>
							</li>';								
			}
		}

		//Verifica si hay solicitudes de auto pendientes
		$solicitudes = DB::table('solicitudauto')
		                  ->join('etapa', 'solicitudauto.etapa_idEtapa', '=', 'etapa.idEtapa')
						 ->where('Radicado_vigencia', '=', $vigencia)
						 ->where('Radicado_idRadicado', '=', $idRadicado)
						 ->where('asignado', '=', 0)
						   ->get();

		//Si se encontraron solicitudes pendientes
		if(count($solicitudes) > 0)
		{
			foreach ($solicitudes as $solicitud)
			{
				$widget .= '<li>
								<a href="javascript: void(0)">
								'.$solicitud->nombreEtapa.' <span class="pull-right badge bg-orange">Solicitud Enviada</span>
								</a>
							</li>';
			}
		}

  		return $widget;
	}

	public static function diasTranscurridos($fechaInicial, $fechaFinal, $habil)
	{
		$contador = 0;
		$fecha2 = "";
		$fecha = $fechaInicial;

		//Dias hábiles
		if ($habil == 1) 
		{
			while ($fecha < $fechaFinal)
			{
				$fecha = date('Y-m-d', strtotime("$fecha + 1 day"));
				$fecha2 = $fecha;

				//Verifica si la fecha es festiva
				//Si no es festivo lo cuenta como día hábil
				if (Util::verificarNoHabil($fecha2) == 0)
				{
					$contador++;
				}
			}
		} 
		else //Días calendario
		{
			$dias = (strtotime($fechaFinal) - strtotime($fechaInicial))/86400;
        	$dias = abs($dias); 
			$dias = floor($dias);
		
			$festivos = 0;
			$nuevaFecha = $fechaInicial;
		
			$suspensionInicio = '2020-03-24';
			$suspensionFin = '2020-07-15';

			for ($i=0; $i < $dias; $i++)  
			{
				$nuevaFecha = strtotime('+1 day', strtotime($nuevaFecha));
				$nuevaFecha = date('Y-m-d', $nuevaFecha);

				if(($nuevaFecha >= $suspensionInicio) && ($nuevaFecha <= $suspensionFin)) 
				{
					$festivos ++;
				}
			}
				
			$contador = $dias - $festivos;
		}

		return $contador;

	}

	public static function traerNombreEtapa($vigencia, $idRadicado)
	{
		$nombreEtapa = "";
		
  		$etapa = DB::table('etapasproceso')
  				->join('etapa', 'etapasproceso.Etapa_idEtapa', '=', 'etapa.idEtapa')
				->where('Radicado_idRadicado', '=', $idRadicado)
				->where('Radicado_vigencia', '=', $vigencia)
				->where('actual', '=', 1)
                ->orderBy('idEtapaProceso', 'desc')
				->first();

  		if(count($etapa) > 0)
  		{
  			//Nombre de la etapa actual
  			$nombreEtapa = $etapa->nombreEtapa;
  		}

  		return $nombreEtapa;
	  }
	  
	public static function traerNombreEtapaId($idEtapa)
	{
  		$etapa = DB::table('etapa')
		          ->select('nombreEtapa')
				   ->where('idEtapa', '=', $idEtapa)
                   ->first();

		$nombreEtapa = '---';

  		if(count($etapa) > 0)
  		{
  			$nombreEtapa = ucwords($etapa->nombreEtapa);
		}

  		return $nombreEtapa;
	}	 
	
	public static function traerNombreCortoEtapaId($idEtapa)
	{
  		$etapa = DB::table('etapa')
		          ->select('nombreCorto')
				   ->where('idEtapa', '=', $idEtapa)
                   ->first();

		$nombreCorto = '---';

  		if(count($etapa) > 0)
  		{
  			$nombreCorto = $etapa->nombreCorto;
		}

  		return $nombreCorto;
	}	 
	  
	public static function traerNombreDependenciaId($idDependencia)
	{
  		$dependencia = DB::table('dependencia')
				         ->where('idDependencia', '=', $idDependencia)
                         ->first();

  		if(count($dependencia) > 0)
  		{
  			$nombreDependencia = $dependencia->nombreDependencia;
		}
		else 
		{
			$nombreDependencia = '---';
		}

  		return $nombreDependencia;
	}
	
	public static function traerNombreFaltaId($idFalta)
	{
  		$falta = DB::table('faltas')
			       ->where('idFalta', '=', $idFalta)
                   ->first();

  		if(count($falta) > 0)
  		{
  			$nombreFalta = $falta->falta;
		}
		else 
		{
			$nombreFalta = '---';
		}

  		return $nombreFalta;
	}

  	public static function traerCargoPresuntoResponsablePortada($vigencia, $idRadicado)
	{
		$quejas = DB::table('acumulaqueja')
				  ->where('Radicado_idRadicado', '=', $idRadicado)
				  ->where('Radicado_vigencia', '=', $vigencia)
				  ->orderBy('Queja_idQueja', 'desc')
				  ->get();

		if(count($quejas) > 0)
		{
			$presuntoResponsable = "";

			foreach ($quejas as $queja)
			{
				$presuntos = Util::traerPresuntosResponsablesPorQueja($queja->Queja_idQueja);				

				//Presuntos Responsables
				if (count($presuntos) > 0)
				{
					if (count($presuntos) == 1)
					{
						$cargoPresuntoResponsable = $presuntos[0]->nombreCargo;
					}
					else
					{
						$cargoPresuntoResponsable = $presuntos[0]->nombreCargo." y ".(count($presuntos)-1)." más.";
					}
				}
				else
				{
					$cargoPresuntoResponsable = "Por determinar";
				}
			}
		}
		else
		{
			$cargoPresuntoResponsable = "<li>Queja no encontrada</li>";
		}

		return $cargoPresuntoResponsable;
	}

	public static function traerDependenciaPresuntoResponsablePortada($vigencia, $idRadicado)
	{
		$quejas = DB::table('acumulaqueja')
				  ->where('Radicado_idRadicado', '=', $idRadicado)
				  ->where('Radicado_vigencia', '=', $vigencia)
				  ->orderBy('Queja_idQueja', 'desc')
				  ->get();

		if(count($quejas) > 0)
		{
			$presuntoResponsable = "";

			foreach ($quejas as $queja)
			{
				$presuntos = Util::traerPresuntosResponsablesPorQueja($queja->Queja_idQueja);

				//Presuntos Responsables
				if (count($presuntos) > 0)
				{
					if (count($presuntos) == 1)
					{
						$dependenciaPresuntoResponsable = $presuntos[0]->nombreDependencia;
					}
					else
					{
						$dependenciaPresuntoResponsable = $presuntos[0]->nombreDependencia." y ".(count($presuntos)-1)." más.";
					}
				}
				else
				{
					$dependenciaPresuntoResponsable = "Por determinar";
				}
			}
		}
		else
		{
			$dependenciaPresuntoResponsable = "<li>Queja no encontrada</li>";
		}
		return $dependenciaPresuntoResponsable;
	}

	public static function traerCantidadEtapa($idEtapa)
	{
		$documentoUsuario = Session::get('documentoUsuario');

		$procesos = DB::table('radicado')
				   ->select('idQueja', 'nombreOrigenQueja', 'anonimo', 'EstadoQueja_idEstadoQueja', 'descEstadoQueja', 'nombreEtapa', 'descEstadoQueja', 'fechaQueja', 'presuntosHechos', 'fechaRecepcionQueja', 'numeroOficio', 'porDeterminar', 'nombreDependencia', 'falta', 'fechaAsignacion', DB::raw('SUBSTRING(presuntosHechos, 1, 256) as presuntosHechos'), 'presuntoLugar', 'vigencia', 'idRadicado', 'idEtapa')
  		  		     ->join('acumulaqueja', function($join) {
				  $join->on('acumulaqueja.Radicado_idRadicado', '=', 'radicado.idRadicado')
				       ->on('acumulaqueja.Radicado_vigencia', '=', 'radicado.vigencia');
				  })
				     ->join('abogadoasignado', function($join) {
			      $join->on('abogadoasignado.Radicado_idRadicado', '=', 'radicado.idRadicado')
			           ->on('abogadoasignado.Radicado_vigencia', '=', 'radicado.vigencia')
			        ->where('abogadoasignado.actual', '=', 'SI');
			      })
				     ->join('queja', 'acumulaqueja.Queja_idQueja', '=', 'queja.idQueja')
				 ->leftJoin('dependencia', 'queja.dependencia_idDependencia', '=', 'dependencia.idDependencia')
				 ->leftJoin('faltas', 'radicado.faltas_idFalta', '=', 'faltas.idFalta')
				     ->join('etapa', 'etapa.idEtapa', '=', 'radicado.Etapa_idEtapa')
				 ->leftJoin('estadoqueja', 'queja.EstadoQueja_idEstadoQueja', '=', 'estadoqueja.idEstadoQueja')
				 ->leftJoin('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
				     ->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
				  ->where('radicado.activo', '=', 1)
				  ->where('radicado.Etapa_idEtapa', '=', $idEtapa)
				  ->where('abogado.Persona_documentoPersona', $documentoUsuario)
				  ->where('radicado.EstadoRadicado_idEstadoRadicado', '!=', 2) //2 Radicado acumulado
				  ->groupBy('radicado.idRadicado')
				  ->groupBy('radicado.vigencia')
				  ->get();

		return count($procesos);
	}

	public static function traerCantidadProcesosActivosFuncionario($documentoUsuario)
	{
		$etapas = DB::table('etapa')
			     ->leftJoin('tiposetapa', 'etapa.tiposetapa_idTipoEtapa', '=', 'tiposetapa.idTipoEtapa')		
					  ->get();

		$granTotal = 0;

		foreach ($etapas as $etapa) 
		{
			$procesos = DB::table('radicado')
				   ->select('idQueja', 'nombreOrigenQueja', 'anonimo', 'EstadoQueja_idEstadoQueja', 'descEstadoQueja', 'nombreEtapa', 'descEstadoQueja', 'fechaQueja', 'presuntosHechos', 'fechaRecepcionQueja', 'numeroOficio', 'porDeterminar', 'nombreDependencia', 'falta', 'fechaAsignacion', DB::raw('SUBSTRING(presuntosHechos, 1, 256) as presuntosHechos'), 'presuntoLugar', 'vigencia', 'idRadicado', 'idEtapa')
  		  		     ->join('acumulaqueja', function($join) {
				  $join->on('acumulaqueja.Radicado_idRadicado', '=', 'radicado.idRadicado')
				       ->on('acumulaqueja.Radicado_vigencia', '=', 'radicado.vigencia');
				  })
				     ->join('abogadoasignado', function($join) {
			      $join->on('abogadoasignado.Radicado_idRadicado', '=', 'radicado.idRadicado')
			           ->on('abogadoasignado.Radicado_vigencia', '=', 'radicado.vigencia')
			        ->where('abogadoasignado.actual', '=', 'SI');
			      })
				     ->join('queja', 'acumulaqueja.Queja_idQueja', '=', 'queja.idQueja')
				 ->leftJoin('dependencia', 'queja.dependencia_idDependencia', '=', 'dependencia.idDependencia')
				 ->leftJoin('faltas', 'radicado.faltas_idFalta', '=', 'faltas.idFalta')
				     ->join('etapa', 'etapa.idEtapa', '=', 'radicado.Etapa_idEtapa')
				 ->leftJoin('estadoqueja', 'queja.EstadoQueja_idEstadoQueja', '=', 'estadoqueja.idEstadoQueja')
				 ->leftJoin('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
				     ->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
				  ->where('radicado.activo', '=', 1)
				  ->where('radicado.Etapa_idEtapa', '=', $etapa->idEtapa)
				  ->where('abogado.Persona_documentoPersona', $documentoUsuario)
				  ->where('radicado.EstadoRadicado_idEstadoRadicado', '!=', 2) //2 Radicado acumulado
				  ->groupBy('radicado.idRadicado')
				  ->groupBy('radicado.vigencia')
				  ->get();

			$total = count($procesos);

			if($total > 0)
			{
				$granTotal += $total;
			}
		}

		return $granTotal;
	}

	public static function traerCantidadEtapaVigencia($idEtapa, $vigencia)
	{
		$procesos = DB::table('radicado')
  				  ->join('acumulaqueja', function($join)
					{
				       	$join->on('acumulaqueja.Radicado_idRadicado', '=', 'radicado.idRadicado')
				           	 ->on('acumulaqueja.Radicado_vigencia', '=', 'radicado.vigencia');
				   	})
				  ->join('abogadoasignado', function($join)
				 	{
			       		$join->on('abogadoasignado.Radicado_idRadicado', '=', 'radicado.idRadicado')
			            	 ->on('abogadoasignado.Radicado_vigencia', '=', 'radicado.vigencia')
			            	 ->where('abogadoasignado.actual', '=', 'SI');
			    	})
				  ->join('etapa', 'etapa.idEtapa', '=', 'radicado.Etapa_idEtapa')
				  ->whereIn('radicado.vigencia', $vigencia)
				  ->where('radicado.Etapa_idEtapa', '=', $idEtapa)
				  ->groupBy('radicado.idRadicado')
				  ->groupBy('radicado.vigencia')
				  ->get();

		return count($procesos);
	}

	public static function traerCantidadRecibidos($vigActual, $mes)
	{
		$idUsuario = Session::get('documentoUsuario');

		if($vigActual == 1)
		{
			$vigencia = date("Y");
		}
		else
		{
			$vigencia = date ("Y", strtotime('-1 year', strtotime(date("Y"))));
		}

		$procesos = DB::table('abogadoasignado')
				  ->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
				  ->where('abogadoasignado.actual', '=', 'SI')
				  ->where('abogado.Persona_documentoPersona', '=', $idUsuario)
				  ->where(DB::raw('substr(fechaAsignacion, -10, 7)'), '=', $vigencia."-".$mes)
				  ->get();

		$cantidad = count($procesos);
		return $cantidad;
	}

	public static function traerCantidadAsignados($vigencia, $idAbogado)
	{
		$procesos = DB::table('abogadoasignado')
				  ->where('abogadoasignado.actual', '=', 'SI')
				  ->where('Abogado_idAbogado', '=', $idAbogado)
				  ->where(DB::raw('substr(fechaAsignacion, -10, 4)'), '=', $vigencia)
				  ->count();

		return $procesos;
	}

	public static function traerCantidadAsignadosMes($vigencia, $mes, $idAbogado)
	{
		$procesos = DB::table('abogadoasignado')
				  ->where('abogadoasignado.actual', '=', 'SI')
				  ->where('Abogado_idAbogado', '=', $idAbogado)
				  ->where(DB::raw('substr(fechaAsignacion, -10, 7)'), '=', $vigencia."-".$mes)
				  ->count();

		return $procesos;
	}

	public static function traerUltimoAuto($idEtapa)
	{
		$vigencia = date('Y');

		$ultimoAuto = DB::table('auto')
						->where('vigenciaAuto', '=', $vigencia)
						->where('Etapa_idEtapa', '=', $idEtapa)
						->max('idAuto');

		return $ultimoAuto;
	}

	public static function traerNombreAbogado($vigencia, $idRadicado)
	{
		$nombre = "";

		$abogado = DB::table('abogadoasignado')
				  ->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
				  ->join('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')
				  ->where('abogadoasignado.actual', '=', 'SI')
				  ->where('abogadoasignado.Radicado_idRadicado', '=', $idRadicado)
				  ->where('abogadoasignado.Radicado_vigencia', '=', $vigencia)
				  ->get();

		if(count($abogado) > 0)
		{
			$nombre = $abogado[0]->nombre;
		}

		return $nombre;
	}

	public static function traerIdAbogadoAsignado($vigencia, $idRadicado)
	{
		$idAbogado = 0;
		$abogado = DB::table('abogadoasignado')
	                ->select('idAbogado')
					  ->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
					 ->where('abogadoasignado.actual', '=', 'SI')
					 ->where('abogadoasignado.Radicado_idRadicado', '=', $idRadicado)
					 ->where('abogadoasignado.Radicado_vigencia', '=', $vigencia)
					 ->first();

		if(count($abogado) > 0)
		{
			$idAbogado = $abogado->idAbogado;
		}

		return $idAbogado;
	}
	
	public static function traerDocumentoAbogadoAsignado($vigencia, $idRadicado)
	{
		$abogado = DB::table('abogadoasignado')
				  ->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
				  ->join('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')
				  ->where('abogadoasignado.actual', '=', 'SI')
				  ->where('abogadoasignado.Radicado_idRadicado', '=', $idRadicado)
				  ->where('abogadoasignado.Radicado_vigencia', '=', $vigencia)
				  ->get();

		if(count($abogado) > 0)
		{
			$documentoAbogado = $abogado[0]->documentoPersona;
		}
		else
		{
			$documentoAbogado = 0;
		}

		return $documentoAbogado;
	}

	public static function traerDatosAbogadoActual($vigencia, $idRadicado)
	{
		$datosAbogado = [];

		$abogado = DB::table('abogadoasignado')
				      ->join('abogado', 'abogadoasignado.Abogado_idAbogado', '=', 'abogado.idAbogado')
				      ->join('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')
				     ->where('abogadoasignado.actual', 'SI')
				     ->where('abogadoasignado.Radicado_idRadicado', '=', $idRadicado)
				     ->where('abogadoasignado.Radicado_vigencia', '=', $vigencia)
				     ->first();

		if(count($abogado) > 0)
		{
			return $abogado;
		}

		return $datosAbogado;
	}

	public static function traerDatosAbogadoId($idAbogado)
	{
		$datosAbogado = [];

		$abogado = DB::table('abogado')
				      ->join('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')
				     ->where('abogado.idAbogado', $idAbogado)
				     ->first();

		if(count($abogado) > 0)
		{
			return $abogado;
		}

		return $datosAbogado;
	}

	public static function valid_email_address($mail)
	{
		$user   = '[a-zA-Z0-9_\-\.\+\^!#\$%&*+\/\=\?\`\|\{\}~\']+';
		$domain = '(?:(?:[a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.?)+';
		$ipv4   = '[0-9]{1,3}(\.[0-9]{1,3}){3}';
		$ipv6   = '[0-9a-fA-F]{1,4}(\:[0-9a-fA-F]{1,4}){7}';

		return preg_match("/^$user@($domain|(\[($ipv4|$ipv6)\]))$/", $mail);
    }

	public static function traerNombreAbogadoId($idAbogado)
	{
		$abogado = DB::table('abogado')
				->select('persona.nombre')
				  ->join('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')
				 ->where('abogado.idAbogado', '=', $idAbogado)
				 ->first();

		if(count($abogado) > 0)
		{
			$nombre = strtolower(json_encode($abogado->nombre));
		}
		else
		{
			$nombre = "<span style='color:#c1c1c1;'>No existe en la bd</span>";
		}

		return $nombre;
	}

	public static function traerDocumentoAbogadoId($idAbogado)
	{
		$abogado = DB::table('abogado')
					  ->join('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')
					 ->where('abogado.idAbogado', '=', $idAbogado)
					 ->first();

		if(count($abogado) > 0)
		{
			$documento = $abogado->documentoPersona;
		}
		else
		{
			$documento = 0;
		}

		return $documento;
	}

	public static function traerIdAbogadoDocumento($documentoPersona)
	{
		$abogado = DB::table('abogado')
					  ->join('persona', 'abogado.Persona_documentoPersona', '=', 'persona.documentoPersona')
					 ->where('persona.documentoPersona', '=', $documentoPersona)
					 ->first();

		if(count($abogado) > 0)
		{
			$idAbogado = $abogado->idAbogado;
		}
		else
		{
			$idAbogado = -1;
		}

		return $idAbogado;
	}

	public static function traerNombreDirector()
	{
		$director = DB::table('usuario')
				  ->join('persona', 'usuario.Persona_documentoPersona', '=', 'persona.documentoPersona')
				  ->where('usuario.director', '=', 1)
				  ->get();

		if(count($director) > 0)
		{
			$nombre = $director[0]->nombre;
		}
		else
		{
			$nombre = "<span style='color:#c1c1c1;'>No existe en la bd</span>";
		}

		return $nombre;
	}

	public static function traerNombrePersona($documento)
	{
		$persona = DB::table('persona')
		            ->select('nombre')
				     ->where('documentoPersona', $documento)
				     ->first();

		if(count($persona) > 0)
		{
			$nombre = $persona->nombre;
		}
		else
		{
			$nombre = "<span style='color:#c1c1c1;'>No existe en la bd</span>";
		}

		return $nombre;
	}

	public static function traerDatosUsuario($documento)
	{
		$persona = DB::table('usuario')
				    ->select('persona.nombre', 'cargo.nombreCargo')
			 	      ->join('persona', 'usuario.Persona_documentoPersona', '=', 'persona.documentoPersona')
					  ->join('cargo', 'usuario.cargo_idCargo', '=', 'cargo.idCargo')
				     ->where('persona.documentoPersona', $documento)
				     ->first();

		return $persona;
	}

	public static function traerDocumentoDirector()
	{
		$director = DB::table('usuario')
				  ->join('persona', 'usuario.Persona_documentoPersona', '=', 'persona.documentoPersona')
				  ->where('usuario.director', '=', 1)
				  ->get();

		if(count($director) > 0)
		{
			$documento = $director[0]->documentoPersona;
		}
		else
		{
			$documento = "<span style='color:#c1c1c1;'>No existe en la bd</span>";
		}

		return $documento;
	}

	public static function traerAutoEtapa($vigencia, $idRadicado, $idEtapa)
	{
		$auto = DB::table('auto')
				->where('Radicado_idRadicado', '=', $idRadicado)
				->where('Radicado_vigencia', '=', $vigencia)
				->where('Etapa_idEtapa', '=', $idEtapa)
				->where('apertura', '=', 1)
                ->get();

  		if(count($auto) > 0)
  		{
  			//Número del auto
  			$numeroAuto = $auto[0]->idAuto;
  		}
  		else
  		{
  			$numeroAuto = 0;
  		}

  		return $numeroAuto;
	}

	public static function almacenarOficio($destinatario, $entidad, $direccion, $idCiudad, $asunto, $radicado)
	{
		$idUsuario = Session::get('documentoUsuario');

		//Consulta el usuario y trae las iniciales----
		$usuario = DB::table('usuario')
					 ->where('usuario.Persona_documentoPersona', '=', $idUsuario)
					 ->first();

		$iniciales = $usuario->inicialesUsuario;
		//--------------------------------------------------------

		//Inserta y obtiene un número de oficio consecutivo de CDI
        $oficio = new Oficio;
        $oficio->VigenciaOficio = date("Y"); // Almacena el año actual
        $oficio->Persona_documentoPersona = $idUsuario;
        $oficio->fechaOficio = date("Y-n-j");
        $oficio->destinatarioOficio = $destinatario;
        $oficio->dependenciaOficio = $entidad;
        $oficio->asuntoOficio = $asunto." - ".$radicado;
        $oficio->save();

        $oficioCDI = "C.D.I. ".$oficio->idOficio."/".date("Y")." - ".$iniciales;

        //Registra el oficio en la base de datos del sistema arco
        $arco = new Arco;
        $arco->setConnection('mysqlArco');//cambia la conexión a la bd del arco
		$arco->vigencia = date("Y");
		$arco->numeroOficio = $oficioCDI." - ".$radicado;
		$arco->fechaOficio = date("Y-n-j");
		$arco->dependencias_idDependencia = 9;//9 Control Disciplinario
		$arco->destinatario = $destinatario;
		$arco->entidad = $entidad;
		$arco->direccion = $direccion;
		$arco->ciudades_idCiudad = $idCiudad;
		$arco->veredas_idVereda = 91;//No veredal
		$arco->copiaRecibido = "SI";
		$arco->destino = 1;
		$arco->recibidoRadicado = 0;
		$arco->fechaRadicado = date("Y-n-j");
		$arco->horaRadicado = date('g:i a');
		$arco->usuarios_idUsuario = $idUsuario;
		$arco->rutas_idRuta = 1;//1 Sin ruta
		$arco->guia = 0;
		$arco->estadosoficio_idEstadoOficio = 2;//2 Radicado sin enviar
		$arco->save();

		//Estado
		$estado = new Estado;
		$estado->setConnection('mysqlArco');//cambia la conexión a la bd del arco
		$estado->fechaEstado = date("Y-m-d H:i:s");//Obtiene la fecha actual
		$estado->observaciones = "Radicó el oficio número <strong><em>".$arco->idRadicado."-".date("Y")."</em></strong> con destino a <strong><em>".$destinatario."</em></strong>";
		$estado->radicados_vigencia = date("Y");
		$estado->radicados_idRadicado = $arco->idRadicado;
		$estado->etapas_idEtapa = 1;//Oficio Radicado
		$estado->usuarios_idUsuario = $idUsuario;
		$estado->ultimo = 1;
		$estado->save();

		$valores = array($oficioCDI, $arco->idRadicado."-".date("Y"));

		return $valores;
	}

	public static function verificarHora($idUsuario, $fechaTarea, $horaTarea)
	{
		$tareas = Tarea::where('Persona_documentoPersona', '=', $idUsuario)
		               ->where(DB::raw('substr(fechaInicioTarea, -19, 10)'), '=', $fechaTarea)
		               ->where(DB::raw('substr(fechaInicioTarea, -8, 8)'), '=', $horaTarea)
					   ->get();

		return $tareas;
	}

	 public static function getIP()
    {
        if (isset($_SERVER["HTTP_CLIENT_IP"]))
        {
            return $_SERVER["HTTP_CLIENT_IP"];
        }
        elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
        {
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        elseif (isset($_SERVER["HTTP_X_FORWARDED"]))
        {
            return $_SERVER["HTTP_X_FORWARDED"];
        }
        elseif (isset($_SERVER["HTTP_FORWARDED_FOR"]))
        {
            return $_SERVER["HTTP_FORWARDED_FOR"];
        }
        elseif (isset($_SERVER["HTTP_FORWARDED"]))
        {
            return $_SERVER["HTTP_FORWARDED"];
        }
        else
        {
            return $_SERVER["REMOTE_ADDR"];
        }
    }

	public static function almacenaLog($accion, $descripcion)
    {
        $ip = Util::getIp();//Obtiene la ip de la máquina del usuario
        $usuario = Session::get('idUsuario');//Obtiene el id del usuario

        if($usuario != "")
        {
            //Crea una instancia de la clase Bitácora
            $registro = new Registro;
            $registro->descripcionRegistro = $descripcion;
           	$registro->fechaRegistro = date("Y-m-d H:i:s");//Obtiene la fecha y hora actual
            $registro->ip = $ip;
            $registro->accion_idAccion = $accion;
            $registro->usuario_idUsuario = $usuario;
            $registro->save();
        }
    }

    //método estático privado
	public static function traerGenero($documentoUsuario)
	{
		$persona = Persona::find($documentoUsuario);
		if(count($persona) > 0)
		{
			$genero = $persona->sexo;
		}
		else
		{
			$genero = "Masculino";
		}

		return $genero;
	}

	public static function traerNombrePerfil($idPerfil)
	{
		$perfil = Perfil::find($idPerfil);

		if(count($perfil) > 0)
		{
			$nomPerfil = $perfil->nombrePerfil;
		}
		else
		{
			$nomPerfil = "Sesión Caducada";
		}

		return $nomPerfil;
	}

	public static function normaliza($cadena)
	{
	    $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞ
		ßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
		    $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuy
		bsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
		    $cadena = utf8_decode($cadena);
		    $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
		    //$cadena = strtolower($cadena);
		    return utf8_encode($cadena);
	}

	public static function traerIdFuncionario($documentoPersona)
	{
		$persona = DB::table('funcionario')
				      ->join('persona', 'funcionario.Persona_documentoPersona', '=', 'persona.documentoPersona')
					 ->where("documentoPersona", $documentoPersona)
					 ->first();

		if(count($persona) > 0)
		{
			$idFuncionario = $persona->idFuncionario;
		}
		else
		{
			$idFuncionario = 0;
		}

		return $idFuncionario;
	}

	public static function traerPresuntosResponsables($vigencia, $idRadicado)
	{
		$quejas = DB::table('acumulaqueja')
				  ->where('Radicado_idRadicado', '=', $idRadicado)
				  ->where('Radicado_vigencia', '=', $vigencia)
				  ->get();

		if(count($quejas) > 0)
		{
			$cadena = "";
			foreach ($quejas as $queja)
			{
				$presuntos = Util::traerPresuntosResponsablesPorQueja($queja->Queja_idQueja);

				if(count($presuntos) > 0)
				{
					foreach ($presuntos as $presunto)
					{
						$cadena = $cadena."<li>".$presunto->nombre."</li>";
					}
				}
				else
				{
					$cadena = $cadena."<li>Anónimo</li>";
				}
			}
		}
		else
		{
			$cadena = "<li>Queja no encontrada</li>";
		}

		return $cadena;
	}

	public static function traerPresuntosResponsablesPorQueja($idQueja)
	{
		$presuntos = DB::table('presuntoresponsable')
						->leftJoin('funcionario', 'presuntoresponsable.Funcionario_idFuncionario', '=', 'funcionario.idFuncionario')
						->leftJoin('dependencia', 'funcionario.Dependencia_idDependencia', '=', 'dependencia.idDependencia')
					    ->leftJoin('cargo', 'funcionario.Cargo_idCargo', '=', 'cargo.idCargo')
						->leftJoin('persona', 'funcionario.Persona_documentoPersona', '=', 'persona.documentoPersona')
					   ->where('Queja_idQueja', '=', $idQueja)
						 ->get();

		return $presuntos;
	}

	public static function traerQuejososPorQueja($idQueja)
	{
		$quejosos = DB::table('quejoso')
						->leftJoin('persona', 'quejoso.Persona_documentoPersona', '=', 'persona.documentoPersona')
					   ->where('Queja_idQueja', '=', $idQueja)
						 ->get();

		return $quejosos;
	}

	public static function traerQuejasPorDocumento($documento)
	{
		$quejas = DB::table('quejoso')
				   ->select('Queja_idQueja')
					->where('Persona_documentoPersona', '=', $documento)
					  ->get();

		return $quejas;
	}

	public static function traerPresuntosResponsablesPorDocumento($documento)
	{
		$quejas = DB::table('presuntoresponsable')
						->leftJoin('funcionario', 'presuntoresponsable.Funcionario_idFuncionario', '=', 'funcionario.idFuncionario')
						->leftJoin('dependencia', 'funcionario.Dependencia_idDependencia', '=', 'dependencia.idDependencia')
					    ->leftJoin('cargo', 'funcionario.Cargo_idCargo', '=', 'cargo.idCargo')
						->leftJoin('persona', 'funcionario.Persona_documentoPersona', '=', 'persona.documentoPersona')
					   ->where('documentoPersona', '=', $documento)
						 ->get();

		return $quejas;
	}

	public static function esAnonimo($idQueja)
	{
		$queja = DB::table('queja')
					->select('anonimo')
					 ->where('idQueja', $idQueja)
					 ->first();

		return $queja->anonimo;
	}

	public static function esPorDeterminar($idQueja)
	{
		$queja = DB::table('queja')
					->select('porDeterminar')
					 ->where('idQueja', $idQueja)
					 ->first();

		return $queja->porDeterminar;
	}
	
	public static function porcentajePrescripcion($vigencia, $idRadicado)
	{
		$radicado = DB::table('radicado')
				 	 ->select('fechaHechos', 'activo', 'fechaFinalizado')
					  ->where('vigencia', $vigencia)
					  ->where('idRadicado', $idRadicado)
					  ->first();

		if ($radicado->fechaHechos == null) 
		{
			return 0;
		} 
		else 
		{
			//Si el proceso está activo, la fecha a evaluar es la de hoy
			if ($radicado->activo == 1) 
			{
				$fechaFin = date('Y-m-d');
			} 
			else //Si está finalizado la fecha a evaluar es la fecha en la que se finalizó
			{
				$fechaFin = $radicado->fechaFinalizado;
			}
			
			//Dias que han transcurrido desde la fecha de los hechos hasta hoy
			$diasPasaron = Util::diasTranscurridos($radicado->fechaHechos, $fechaFin, 0);//0 No hábiles		
			/*
				1 año = 1825 días
				2 años = 730 días
				3 años = 1095 días
				4 años = 1460 días
				5 años = 1825 días							
			
				Verde:        día 1 a 1095 
				$porcentaje = (1095 * 100) / 1825; 60%

				Amarillo:     día 1096 a 1825
				$porcentaje = (1096 * 100) / 1825; 60,05479%
				$porcentaje = (1825 * 100) / 1825; 100%

				Rojo:         día 1826 a ...
				$porcentaje = (1826 * 100) / 1825; 100,05479%
			*/

			//1825 días = 5 años
			$porcentaje = ($diasPasaron * 100) / 1825;

			if($porcentaje > 100)
			{
				return 100;
			}

			return $porcentaje;
		}		
	}

	public static function diasPasaronPrescripcion($vigencia, $idRadicado)
	{
		$radicado = DB::table('radicado')
					  ->select('fechaHechos', 'activo', 'fechaFinalizado')
					  ->where('vigencia', $vigencia)
					  ->where('idRadicado', $idRadicado)
					  ->first();

		if ($radicado->fechaHechos == null) 
		{
			return 0;
		} 
		else 
		{
			//Si el proceso está activo, la fecha a evaluar es la de hoy
			if ($radicado->activo == 1) 
			{
				$fechaFin = date('Y-m-d');
			} 
			else //Si está finalizado la fecha a evaluar es la fecha en la que se finalizó
			{
				$fechaFin = $radicado->fechaFinalizado;
			}

			//Dias que han transcurrido desde la fecha de los hechos hasta hoy
			$diasPasaron = Util::diasTranscurridos($radicado->fechaHechos, $fechaFin, 0);//0 No hábiles		
			return $diasPasaron;
		}		
	}

	public static function updateDocumento($tabla, $documentoActual, $nuevoDocumento)
	{
		DB::table($tabla)
	      ->where('Persona_documentoPersona', $documentoActual)
		 ->update(['Persona_documentoPersona'=> $nuevoDocumento]);
	}

	public static function traerDatosQueja($vigencia, $idRadicado)
	{
		$queja = DB::table('acumulaqueja')		
				  ->select('idQueja', 'nombreOrigenQueja', 'anonimo', 'EstadoQueja_idEstadoQueja', 'descEstadoQueja', 'descEstadoQueja', 'fechaQueja', 'presuntosHechos', 'fechaRecepcionQueja', 'numeroOficio', 'porDeterminar', 'nombreDependencia', DB::raw('SUBSTRING(presuntosHechos, 1, 256) as presuntosHechos'), 'presuntoLugar', 'Radicado_vigencia', 'Radicado_idRadicado')
					->join('queja', 'acumulaqueja.Queja_idQueja', '=', 'queja.idQueja')
				->leftJoin('dependencia', 'queja.dependencia_idDependencia', '=', 'dependencia.idDependencia')
				->leftJoin('estadoqueja', 'queja.EstadoQueja_idEstadoQueja', '=', 'estadoqueja.idEstadoQueja')
				->leftJoin('origenqueja', 'queja.OrigenQueja_idOrigenQueja', '=', 'origenqueja.idOrigenQueja')
				   ->where('Radicado_idRadicado', '=', $idRadicado)
				   ->where('Radicado_vigencia', '=', $vigencia)
			     ->orderBy('fechaAcumula', 'desc')
				  ->first();	

		return $queja;
	}

	public static function traerFechaProceso($vigencia, $idRadicado)
	{
		$fechaQueja = "";

		$queja = DB::table('acumulaqueja')
			      ->select('fechaQueja')
				    ->join('queja', 'acumulaqueja.Queja_idQueja', '=', 'queja.idQueja')
				   ->where('Radicado_idRadicado', '=', $idRadicado)
				   ->where('Radicado_vigencia', '=', $vigencia)
				   ->first();

		if(count($queja) > 0)
		{
			$fechaQueja = $queja->fechaQueja;
		}

		return $fechaQueja;
	}

	public static function siguienteEstado($idEtapa)
	{
		$etapa = DB::table('etapa')
			      ->select('estadoradicado_idEstadoRadicado')
			       ->where('idEtapa', '=', $idEtapa)
                   ->first();

		return $etapa->estadoradicado_idEstadoRadicado;
	}

	public static function verificarPermiso($permiso, $perfil)
	{
		$permisos = DB::table('permisos')
					  ->where('Perfil_idPerfil', $perfil)
					  ->where('Privilegio_idPrivilegio', $permiso)
		  			  ->count();

		if ($permisos > 0) 
		{            
			return true;
		}

		return false;
	}

	public static function actionTraerFase($vigencia, $idRadicado)
	{
		//Trael el id de la etapa actual
		$etapas = DB::table('etapasproceso')
		           ->select('Etapa_idEtapa', 'tiposEtapa_idTipoEtapa')
		             ->join('etapa', 'etapasproceso.Etapa_idEtapa', '=', 'etapa.idEtapa')
			   	    ->where('Radicado_vigencia', $vigencia)
					->where('Radicado_idRadicado', $idRadicado)
				    ->where('actual', '=', 1)//1 actual
				  ->orderBy('idEtapaProceso', 'desc')
                    ->first();

		$faseActual = 0;

  		if(count($etapas) > 0)
  		{
			$faseActual = $etapas->tiposEtapa_idTipoEtapa;
  		} 

		return $faseActual;
	}

	public static function actionTraerNombreFase($vigencia, $idRadicado)
	{
		$faseActual = Util::actionTraerFase($vigencia, $idRadicado);

		switch ($faseActual) 
		{
			case '1':
				$nombreFase = "Fase de Instrucción";
				break;
			case '2':
				$nombreFase = "Fase de Juzgamiento";
				break;
			case '3':
				$nombreFase = "Segunda Instancia";
				break;
			default:
				$nombreFase = "";
				break;
		}

		return $nombreFase;
	}

	public static function traerNombreCortoEstadoId($idEstadoQueja)
	{
  		$estado = DB::table('estadoqueja')
		           ->select('nombreCorto')
				    ->where('idEstadoQueja', $idEstadoQueja)
                    ->first();

		$nombreEstado = '';

  		if(count($estado) > 0)
  		{
  			$nombreEstado = ucwords($estado->nombreCorto);
		}

  		return $nombreEstado;
	}

	public static function actionTraerNombreFalta($vigencia, $idRadicado)
	{
		$falta = DB::table('radicado')
		          ->select('falta')
				    ->join('faltas', 'radicado.faltas_idFalta', '=', 'faltas.idFalta')
			   	   ->where('vigencia', $vigencia)
			       ->where('idRadicado', $idRadicado)
                   ->first();

		$nombreFalta = '';

  		if(count($falta) > 0)
  		{
			$nombreFalta = $falta->falta;
  		} 

		return $nombreFalta;
	}
}