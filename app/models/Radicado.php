<?php 
class Radicado extends Eloquent 
{ 
	protected $table = 'radicado'; 
	public $timestamps = false; //Desactiva fecha y hora de creación del campo 
	protected $primaryKey = 'idRadicado'; //Cambia el id por defecto 
}