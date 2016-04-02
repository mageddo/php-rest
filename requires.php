<?php
/**
 * Destinado a chamar todas as bibliotecas necessárias no sistema 
 * !!!!
 * Este arquivo deve chamar apenas classes e funções, NUNCA chame ou execute códigos por aqui 
 * !!!!
 */

# exceptions
require_once 'classes/InvalidBase64Exception.php';

# defaults/customs 
require_once 'classes/RetornoJson.php';
require_once 'classes/Status.php';
require_once 'classes/UseSQL.php';
require_once 'general/functions.php';
