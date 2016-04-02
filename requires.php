<?php
/**
 * Destinado a chamar todas as bibliotecas necessárias no sistema 
 * !!!!
 * Este arquivo deve chamar apenas classes e funções, NUNCA chame ou execute códigos por aqui 
 * !!!!
 */

# calling configurations
callConfig();

# exceptions
require_once 'classes/InvalidBase64Exception.php';

# defaults/customs 
require_once 'classes/RetornoJson.php';
require_once 'classes/Status.php';
require_once 'classes/UseSQL.php';
require_once 'general/functions.php';

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
