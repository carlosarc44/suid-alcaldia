<?php 
class AbogadoAsignado extends Eloquent 
{ 
	protected $table = 'abogadoasignado'; 
	public $timestamps = false; //Desactiva fecha y hora de creación del campo 
	protected $primaryKey = 'idAbogadoAsignado'; //Cambia el id por defecto 
}