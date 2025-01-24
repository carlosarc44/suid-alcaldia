<?php 
class Informante extends Eloquent 
{ 
	protected $table = 'informante'; 
	public $timestamps = false; //Desactiva fecha y hora de creación del campo 
	protected $primaryKey = 'idInformante'; //Cambia el id por defecto 
}