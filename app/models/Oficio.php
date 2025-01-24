<?php 
class Oficio extends Eloquent 
{ 
	protected $table = 'oficio'; 
	public $timestamps = false; //Desactiva fecha y hora de creación del campo 
	protected $primaryKey = 'idOficio'; //Cambia el id por defecto 
}