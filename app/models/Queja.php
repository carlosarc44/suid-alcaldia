<?php 
class Queja extends Eloquent 
{ 
	protected $table = 'queja'; 
	public $timestamps = false; //Desactiva fecha y hora de creación del campo 
	protected $primaryKey = 'idQueja'; //Cambia el id por defecto 
}