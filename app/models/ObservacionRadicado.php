<?php 
class ObservacionRadicado extends Eloquent 
{ 
	protected $table = 'observacionesradicado'; 
	public $timestamps = false; //Desactiva fecha y hora de creación del campo 

	protected $primaryKey = 'idObservacionesRadicado'; //Cambia el id por defecto 
}