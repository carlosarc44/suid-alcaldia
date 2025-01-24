<?php 
class NoHabil extends Eloquent 
{ 
	protected $table = 'nohabil'; 
	public $timestamps = false; //Desactiva fecha y hora de creación del campo 
	protected $primaryKey = 'idNoHabil'; //Cambia el id por defecto 
}