<?php 
class Etapa extends Eloquent 
{ 
	protected $table = 'etapa'; 
	public $timestamps = false; //Desactiva fecha y hora de creación del campo 
	protected $primaryKey = 'idEtapa'; //Cambia el id por defecto 
}