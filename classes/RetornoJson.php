<?php
class RetornoJson {

	public $estado;
	public $dados; 
	
	function __construct($estado, $dados){
		$this->estado = $estado;
		$this->dados = $dados;
	}
	
	function __toString(){
		@http_response_code($this->estado);
		@header('Content-Type: application/json; charset=utf-8');
		if(MG_DEBUG){
			return json_encode($this->dados, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE);
		}
		return json_encode($this->dados); 
	}
}