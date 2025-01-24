<?php 
class Tarea extends Eloquent 
{ 
	protected $table = 'tarea'; 
	public $timestamps = false; //Desactiva fecha y hora de creación del campo 
	protected $primaryKey = 'id'; //Cambia el id por defecto 
}