<?php

class InvalidBase64Exception extends Exception {
	public function __construct($message, $code = 90012, Exception $previous = null) {
		parent::__construct($message, $code, $previous);
	}
}