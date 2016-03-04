<?php
global $_PARAM;
if(!isset($_PARAM))
	throw new Exception('Por favor informe parametros válidos para a requisição');

$con = new PDO('mysql:host='. DB_HOST .';dbname='. DB_NAME .';charset=utf8', DB_USER, DB_PASSWORD);
$con->exec("set names utf8");

$GLOBALS['con'] = $con;
$GLOBALS['link'] = $con;

$db = new UseSQL();
$GLOBALS['db'] = $db; 
