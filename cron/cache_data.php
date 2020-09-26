<?php
	require_once __DIR__ . '/../vendor/autoload.php';

	$handler = new App\Handler();
	$status_success = $handler->updateCachedData();
	if(!$status_success) {
		echo $handler->last_error;
	}
