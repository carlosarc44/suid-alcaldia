<?php 
class Arco extends Eloquent 
{ 
	protected $table = 'radicados'; 
	public $timestamps = false; //Desactiva fecha y hora de creación del campo 
	protected $primaryKey = 'idRadicado'; //Cambia el id por defecto 
}