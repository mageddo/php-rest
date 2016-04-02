<?php

/*
 * where the system will upload files ?
 */
define('SYS_UPLOAD_FOLDER','images/uploads/');
define('MG_DEBUG', false);


/*
 * where are the controllers? Pass absolute path.
 */
define('MG_CONTROLLER_PATH', __DIR__ . "/controller");


/*
 * You will use this framework as library (FALSE) or this system will be the API (TRUE)?
 */
define('MG_AS_API', true);


/*
 * database data connection
 */
define('MG_DB_USER', 'root');
define('MG_DB_PASSWORD', 'root');
define('MG_DB_HOST', 'localhost');
define('MG_DB_NAME', 'pedido-api');