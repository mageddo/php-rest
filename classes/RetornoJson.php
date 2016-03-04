<?php
class RetornoJson {

	public $estado;
	public $dados; 
	
	function __construct($estado, $dados){
		$this->estado = $estado;
		$this->dados = $dados;
	}
	
	function __toString(){
		@http_response_code($estado);
		@header('Content-Type: application/json; charset=utf-8');
		return json_encode($this); 
	}
}