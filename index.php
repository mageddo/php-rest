<?php

class PHPApi {

	public $not_found_callback;

	function proxyAPI($url){
		$this->$not_found_callback = function($opt){
			$ch = curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_URL => $url,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_CUSTOMREQUEST => getRequestMethod(),
				CURLOPT_POSTFIELDS => file_get_contents("php://input"),
				CURLOPT_VERBOSE => true,
				CURLOPT_HEADER => true,
				CURLOPT_HTTPHEADER => getallheaders()
			));
			$response = curl_exec($ch);
			$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
			curl_close($ch);
			$index = strpos($response , "\n");
			$headers = substr($response, $index, $headerSize - $index);
			$completeHeaders = substr($response, 0, $headerSize);
			$statusLine = substr($completeHeaders, 0, $index);
			$body = substr($response, $headerSize);
			$first = true;
			foreach (explode("\n", $completeHeaders) as $header){
				if($header){
					if($first){
						preg_match("/([0-9]{3})/", $header, $matches);
						http_response_code($matches[0]);
						$first = false;
					}else{
						header($header);
					}
				}
			}
			echo $body;
		}
		$this->setUp();
	}

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

# calling configurations
callConfig();
function callConfig(){
	$configs = array(
		getenv('MG_CONFIG_FILE'), __DIR__ . '/config.php',
		 __DIR__ . '/config.sample.php'
	);
	foreach ($configs as $configFile) {
		if(file_exists($configFile)){
			require_once $configFile;
			return ;
		}
	}
	throw new Exception('deve existir ao menos o arquivo de configuração padrão');
}

if(MG_AS_API){
	$api = new PHPApi();
	$api->setUp();
}