<?php
global $_PARAM;
if(!isset($_PARAM))
	throw new Exception('Por favor informe parametros válidos para a requisição');

$con = new PDO('mysql:host='. MG_DB_HOST .';dbname='. MG_DB_NAME .';charset=utf8', MG_DB_USER, MG_DB_PASSWORD);
$con->exec("set names utf8");

$GLOBALS['con'] = $con;
$GLOBALS['link'] = $con;

$db = new UseSQL();
$GLOBALS['db'] = $db; 
