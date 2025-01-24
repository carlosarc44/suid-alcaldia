<?php 
class TipoPlantilla extends Eloquent 
{ 
	protected $table = 'tipoplantilla'; 
	public $timestamps = false; //Desactiva fecha y hora de creación del campo 
	protected $primaryKey = 'idTipoPlantilla'; //Cambia el id por defecto 
}