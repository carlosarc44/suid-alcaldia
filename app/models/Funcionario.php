<?php 
class Funcionario extends Eloquent 
{ 
	protected $table = 'funcionario'; 
	public $timestamps = false; //Desactiva fecha y hora de creación del campo 
	protected $primaryKey = 'idFuncionario'; //Cambia el id por defecto 
}