<?php 
class ArchivoGenerado extends Eloquent 
{ 
	protected $table = 'archivogenerado'; 
	public $timestamps = false; //Desactiva fecha y hora de creación del campo 
	protected $primaryKey = 'idArchivoGenerado'; //Cambia el id por defecto 
}