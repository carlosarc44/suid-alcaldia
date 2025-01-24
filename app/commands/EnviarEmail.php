<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class EnviarEmail extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'enviar:email';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Comando que envía un correo electrónico con las tareas para el día actual.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		 $nombre = $this->argument('nombre');

		$fechaHoy = date("Y-m-d");//Obtiene la fecha actual 
		$idUsuario = Session::get('documentoUsuario');

		$tareas = DB::table('tarea')
				    ->join('persona', 'tarea.Persona_documentoPersona', '=', 'persona.documentoPersona')
	                ->where(DB::raw('substr(fechaInicioTarea, -19, 10)'), '=', $fechaHoy)
	                ->where('finalizadaTarea', '=', 0)
	                ->orderBy('tarea.fechaInicioTarea', 'asc')
				    ->get();

		//Array para las tareas
	    $tareasDia = array();

		foreach ($tareas as $tarea) 
		{
		    $email = $tarea->email;
			
			$datos = array("asuntoTarea" 	 => $tarea->asuntoTarea,
						   "descripcionTarea" => $tarea->descripcionTarea,
						   "horaTarea" 	 => date("g:i a", strtotime($tarea->fechaInicioTarea)),
						   "proceso"  => $tarea->Radicado_vigencia."-".$tarea->Radicado_idRadicado,
						   "nombresDestinatario"  => $tarea->nombre); 

			array_push($tareasDia, $datos);
			unset($datos);	
		}//foreach

		$data = array('arreglo' => json_encode($tareasDia));
		
		//Si se almacenó al menos una tarea
		if(count($tareasDia) > 0)
		{
			Mail::send('emails.template', $data, function ($message) use($email){
				$message->subject('Recordatorio Tareas para hoy');
				$message->from(array('notificaciones@manizales.gov.co' => 'Recordatorios SUID'));
				$message->to($email);
				$message->setContentType('text/html');
			});		
			echo "enviado ".$nombre;		
			//$job->delete();		 
		}			
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
    {
        return array(
            array('nombre', InputArgument::REQUIRED, 'Name of the new user'),
        );
    }

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
