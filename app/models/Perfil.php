<?php 
class Perfil extends Eloquent 
{ 
	protected $table = 'perfil'; 
	public $timestamps = false; //Desactiva fecha y hora de creación del campo 
	protected $primaryKey = 'idPerfil'; //Cambia el id por defecto 
}