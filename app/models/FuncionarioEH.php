<?php 
class FuncionarioEH extends Eloquent 
{ 
	protected $table = 'funcionarioeh'; 
	public $timestamps = false; //Desactiva fecha y hora de creación del campo 
	protected $primaryKey = 'idFuncionarioEH'; //Cambia el id por defecto 
}