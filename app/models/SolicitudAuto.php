<?php 
class SolicitudAuto extends Eloquent 
{ 
	protected $table = 'solicitudauto'; 
	public $timestamps = false; //Desactiva fecha y hora de creación del campo 
	protected $primaryKey = 'idSolicitudAuto'; //Cambia el id por defecto 
}