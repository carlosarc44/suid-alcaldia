<?php 
class Operador extends Eloquent 
{ 
	protected $table = 'operador'; 
	public $timestamps = false; //Desactiva fecha y hora de creación del campo 
	protected $primaryKey = 'idOperador'; //Cambia el id por defecto 
}