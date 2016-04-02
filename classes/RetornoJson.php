<?php
class RetornoJson {

	public $estado;
	public $dados;
	public $headers;
	
	function __construct($estado, $dados, $headers = array('Content-Type: application/json; charset=utf-8')){
		$this->estado = $estado;
		$this->dados = $dados;
		$this->headers = $headers;
	}
	
	function __toString(){
		@http_response_code($this->estado);
		@header('Access-Control-Allow-Origin: *');
		foreach ($this->headers as $header) {
			@header($header);
		}
		if(MG_DEBUG){
			return json_encode($this->dados, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE);
		}
		return json_encode($this->dados); 
	}

	static function invalidField($message){
		die(new RetornoJson(Status::$BAD_REQUEST, array('message' => $message)));
	}

	static function success($o){
		die(new RetornoJson(Status::$OK, $o));
	}
}