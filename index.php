<?php

class PHPApi {

	public $not_found_callback;

	function setUp(){
		/**
		 * incluindo as bibliotecas
		 */
		require_once 'requires.php';

		/**
		 * Este é o arquivo que faz todos os mapentos necessários e 
		 * que chama as classes, actions e códigos respectivos á URL
		 */

		if(MG_DEBUG){
			ini_set('error_reporting', E_ALL ^ E_NOTICE);
		}

		/**
		 * registrando loggers
		 */ 
		register_shutdown_function('error_handler');

		/*
		 * Chamando o arquivo correspondente 
		 */
		try{

			resolveController(resolveRequest(), $this->not_found_callback);

			/*
			 * Se chegar aqui então quer dizer que todo o processo ocorreu bem, logo ele gera o JSON como sucesso
			 */
			global $mgResult;
			global $mgError;
			
			if(isset($mgResult))
				die(new RetornoJson(Status::$OK, $mgResult));
			else if(isset($mgError))
				die(new RetornoJson(Status::$BAD_REQUEST, $mgError));

		}catch (Exception $e){
			/*
			 * Nesse ponto o sistema sofreu algum erro e irá apresentar o erro no formato JSON
			 */
			catch_error($e);
		}
	}
}

/**
 * Captura os erros que acontecem no PHP e não são excecoes
 */
function error_handler(){
	$e = error_get_last();
	if(!$e || $e['type'] == E_NOTICE)return ;
		
	catch_error(new ErrorException($e['message'], 0, $e[type], $e[file], $e[line]));
}

function catch_error($e){
	if(MG_DEBUG){
		header('Content-Type: text/plain; charset=utf-8');
		echo $e;
	}else{
		file_put_contents("error.log", date("[Y/m/d H:i:s] ", time()) . $e->__toString() . "\n", FILE_APPEND);
		die(new RetornoJson(Status::$INTERNAL_SERVER_ERROR, array('code' => 500, 'message' => $e->getMessage())));
	}
}

if(MG_AS_API){
	$api = new PHPApi();
	$api->setUp();
}