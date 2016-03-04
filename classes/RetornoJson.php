<?php
class RetornoJson {
	
	/**
	 * 1 = sucesso, 0 = erro
	 * @var int
	 */
	public $estado;
	public $dados; 
	
	function __construct($estado, $dados){
		$this->estado = $estado;
		$this->dados = $dados;
	}
	
	function __toString(){
		return json_encode($this); 
	}
}