<?php
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

/**
 * Parametros da requisição
 */ 
$_PARAM = @json_decode($_REQUEST['rsq']);

/**
 * Arquivo que será carregado
 */  
$url = $_GET['cmd_url'];
$url_original = $url;

/*
 * Tirando a barra do final caso tenha
 */
$tamanhoUrl = strlen($url); 
if($tamanhoUrl > 0){
	if($url[$tamanhoUrl - 1] == '/'){
		$url = substr($url, 0, $tamanhoUrl - 1);
	}
	$url = str_replace("/", "-", $url);
}

/*
 * Chamando o arquivo correspondente 
 */
try{
	if(!$url){
		$url = "default";		
	}

	// chamando a página correspondente
	$path = 'controller/' . $url . '.php';
	if(!file_exists($path)){
		header('Content-Type: application/json; charset=utf-8');
		die(new RetornoJson(Status::$NOT_FOUND, "A url '" .curPageURL(). "' não existe"));
	}
	// chamando arquivo respectivo
	@require_once ($path);
	
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
		header('Content-Type: application/json; charset=utf-8');
		file_put_contents("error.log", date("[Y/m/d H:i:s] ", time()) . $e->__toString() . "\n", FILE_APPEND);
		die(new RetornoJson(Status::$BAD_REQUEST, $e->getMessage()));
	}
}
