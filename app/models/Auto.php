<?php 
class Auto extends Eloquent 
{ 
	protected $table = 'auto'; 
	public $timestamps = false; //Desactiva fecha y hora de creación del campo 
	protected $primaryKey = 'idAuto'; //Cambia el id por defecto 
}