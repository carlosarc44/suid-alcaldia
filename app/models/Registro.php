<?php 
class Registro extends Eloquent 
{ 
	protected $table = 'registro'; 
	public $timestamps = false; //Desactiva fecha y hora de creación del campo 
	protected $primaryKey = 'idRegistro'; //Cambia el id por defecto 
}