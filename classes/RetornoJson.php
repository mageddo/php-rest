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
		return toJson($this->dados);
	}

	static function invalidField($message){
		self::message(Status::$BAD_REQUEST, array('message' => $message));
	}

	static function message($code, $message){
		die(new RetornoJson($code, array('message' => $message)));
	}

	static function success($o){
		die(new RetornoJson(Status::$OK, $o));
	}

	static function response($status, $data = ''){
		die(new RetornoJson($status, $data));
	}
}