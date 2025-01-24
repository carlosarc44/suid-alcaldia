<?php 
class Estado extends Eloquent 
{ 
	protected $table = 'estados'; 
	public $timestamps = false; //Desactiva fecha y hora de creación del campo 
	protected $primaryKey = 'idEstado'; //Cambia el id por defecto 
}