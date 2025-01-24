<?php 
class Parametro extends Eloquent 
{ 
	protected $table = 'parametro'; 
	public $timestamps = false; //Desactiva fecha y hora de creación del campo 
	protected $primaryKey = 'idParametro'; //Cambia el id por defecto 
}