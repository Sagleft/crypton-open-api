<?php
	require_once __DIR__ . '/../vendor/autoload.php';

	$handler = new App\Handler();
	$handler->setAPIHeaders();

	$err_code = $handler->checkINT($_GET['code']);
	$accept_codes = [400, 403, 404, 429, 500, 502, 503, 504];
	if(! in_array($err_code, $accept_codes)) {
		$err_code = 400;
	}
	
	$handler->apiError('An error has occurred. HTTP Code: ' . $err_code, $err_code);
