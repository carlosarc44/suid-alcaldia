<?php 
class Persona extends Eloquent 
{ 
	protected $table = 'persona'; 
	public $timestamps = false; //Desactiva fecha y hora de creación del campo 
	protected $primaryKey = 'documentoPersona'; //Cambia el id por defecto 
}