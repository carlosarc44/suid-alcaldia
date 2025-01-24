<?php 
class Plantilla extends Eloquent 
{ 
	protected $table = 'plantilla'; 
	public $timestamps = false; //Desactiva fecha y hora de creación del campo 
	protected $primaryKey = 'idPlantilla'; //Cambia el id por defecto 
}