<?php
/**
 * Destinado a funções genéricas que possam ser usadas por todo o sistema
 */


/**
 * Retorna a URL da página atual
 * @return string
 */
function curPageURL() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}



/**
 * Retorna a conexão(apenas uma conexão por requests)
 */
function getConnection(){
	@require_once 'connection.php';
	global $con;
	return $con;
}


/**
 * Verufica se é uma base64 válida
 */
function is_base64_encoded($data) {
	if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data)) {
			return TRUE;
	} else {
			return FALSE;
	}
}

/**
 * Escreve a imagem base64 para uma imagem
 * @param unknown $str
 * @return string
 */
function writeBase64ToImage($str){
	if(!is_base64_encoded($str))
		throw new InvalidBase64Exception("O código base64 que foi passado para a imagem não é válido");
	
	$bytes = base64_decode($str);
	$fileName = uniqid() . '.png';
	$fp = fopen(getFilesPath() . $fileName, 'w');
	fwrite($fp, $bytes);
	fclose($fp);
	sleep(1);
	return $fileName;
	
}

/**
 * Retorna o caminho onde as imagens ficam armazenadas
 * @return string
 */
function getFilesPath(){
	return SYS_UPLOAD_FOLDER;
}


/**
 * Remove do array ou objeto passado todos as chaves vazias ou que não fazem parte 
 * do atributo $fields
 * @param unknown $object
 * @param unknown $fields
 */
function clearModel(&$object, $fields){
	$is_object = is_object($object);
	$fields = (array) $fields;
	$object = (array) $object;
	foreach ($object as $k=>$v){
		if(FALSE === array_search($k, $fields)){
			unset($object[$k]);
		}
	}
	
	$object = (object) $object;
}

/**
 * Le a string com o nome do arquivo e o converte para base64
 * @return string
 */
function readFileToBase64($dbname){
	$caminho = getFilesPath() . $dbname;
	if(!file_exists($caminho))
		return "";
	return base64_encode(file_get_contents($caminho));
}

// Returns a file size limit in bytes based on the PHP upload_max_filesize
// and post_max_size
function getFileUploadMaxSize() {
	static $max_size = -1;

	if ($max_size < 0) {
		// Start with post_max_size.
		$max_size = parse_size(ini_get('post_max_size'));

		// If upload_max_size is less, then reduce. Except if upload_max_size is
		// zero, which indicates no limit.
		$upload_max = parse_size(ini_get('upload_max_filesize'));
		if ($upload_max > 0 && $upload_max < $max_size) {
			$max_size = $upload_max;
		}
	}
	return $max_size;
}

function parse_size($size) {
	$unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
	$size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
	if ($unit) {
		// Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
		return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
	}
	else {
		return round($size);
	}
}

/**
 * returns the image type or null if is not a imagem
 */
function get_image_type ( $filename ) {
	$img = getimagesize( $filename );
	if ( !empty( $img[2] ) )
		return image_type_to_mime_type( $img[2] );
}

function setJsonHeader(){
	header('Content-Type: application/json; charset=utf-8');
}

function getJsonBody(){
	$inputJSON = file_get_contents('php://input');
	return json_decode($inputJSON);
}

function getRequestMethod(){
	return $_SERVER['REQUEST_METHOD'];
}
function getRequestUrl(){
	return $_GET['cmd_url'];
}
function resolveRequest(){
	/**
	 * Arquivo que será carregado
	 */  
	$url = getRequestUrl();
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
	if(!$url){
		$url = "default";
	}
}
function resolveController($url){
	// chamando a página correspondente
	$path = 'controller/' . $url . '.php';
	if(!file_exists($path)){
		die(new RetornoJson(Status::$NOT_FOUND, array('code' => 4041, 'message' => "A url '" .curPageURL(). "' não existe")));
	}
	// chamando arquivo respectivo
	@require_once ($path);
	
}