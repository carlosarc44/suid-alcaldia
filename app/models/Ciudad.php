<?php 
class Ciudad extends Eloquent 
{ 
	protected $table = 'ciudad'; 
	public $timestamps = false; //Desactiva fecha y hora de creación del campo 
	protected $primaryKey = 'idCiudad'; //Cambia el id por defecto 
}