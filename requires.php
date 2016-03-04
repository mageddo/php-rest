<?php
/**
 * Destinado a chamar todas as bibliotecas necessárias no sistema 
 * !!!!
 * Este arquivo deve chamar apenas classes e funções, NUNCA chame ou execute códigos por aqui 
 * !!!!
 */

# calling configurations
if(file_exists('config.php')){
	include 'config.php';
}else{
	if (!file_exists('config.sample.php')) {
		throw new Exception('deve existir ao menos o arquivo de configuração padrão');
	}
	include 'config.sample.php';
}

# exceptions
require_once 'classes/InvalidBase64Exception.php';

# defaults/customs 
require_once 'classes/RetornoJson.php';
require_once 'classes/Status.php';
require_once 'classes/UseSQL.php';
require_once 'general/functions.php';

# DAOs