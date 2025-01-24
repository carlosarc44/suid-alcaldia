<?php 
class Funcionarios extends Eloquent 
{ 
	protected $table = 'funcionarios'; 
	public $timestamps = false; //Desactiva fecha y hora de creación del campo 
	protected $primaryKey = 'idFuncionario'; //Cambia el id por defecto 
}