<?php

class EmailController extends \BaseController 
{
	public function actionEnviarEmail($job, $data)
	{
		$email = $data['email'];
       	$data = $data['data'];

    	Mail::send('emails.template', $data, function ($message) use($email){
						$message->subject('Recordatorio Tareas para hoy');
						$message->from(array('comunicaciones@manizales.gov.co' => 'Recordatorios SUID'));
						$message->to($email);
						$message->setContentType('text/html');
					});		
		echo "enviado a: ".$email;		
		$job->delete();	
	}

	public function emailProcesoAsignado($job, $datos)
	{
		$email = $datos['email'];
		$radicado = $datos['radicado'];

		$asunto = 'Proceso '.$radicado.' asignado en fase de juzgamiento';
		$data = array('datos' => json_encode($datos));

    	Mail::send('emails.templateProcesoAsignado', $data, function ($message) use($email, $asunto){
						$message->subject($asunto);
						$message->from(array('comunicaciones@manizales.gov.co' => 'Notificaciones SUID :: Oficina de Control Disciplinario'));
						$message->to($email);
						$message->setContentType('text/html');
					});		
		echo "SUID Proceso Asignado en juzgamiento => Enviado a: ".$email." el: ".date("Y-m-d H:i:s")." ";
		$job->delete();	
	}
	
	public function emailProcesoRepartoJuzgamiento($job, $datos)
	{
		$email = $datos['email'];
		$radicado = $datos['radicado'];

		$asunto = 'Proceso '.$radicado.' para reparto en fase de juzgamiento';
		$data = array('datos' => json_encode($datos));

    	Mail::send('emails.templateProcesoRepartoJuzgamiento', $data, function ($message) use($email, $asunto){
						$message->subject($asunto);
						$message->from(array('comunicaciones@manizales.gov.co' => 'Notificaciones SUID :: Oficina de Control Disciplinario'));
						$message->to($email);
						$message->setContentType('text/html');
					});		
		echo "SUID Proceso para reparto en juzgamiento => Enviado a: ".$email." el: ".date("Y-m-d H:i:s")." ";
		$job->delete();	
	}


}
