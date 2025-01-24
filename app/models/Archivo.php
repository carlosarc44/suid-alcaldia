<?php 
class Archivo extends Eloquent 
{ 
	protected $table = 'archivo'; 
	public $timestamps = false; //Desactiva fecha y hora de creación del campo 
	protected $primaryKey = 'idArchivo'; //Cambia el id por defecto 
}