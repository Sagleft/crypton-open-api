<?php
	require_once __DIR__ . '/../vendor/autoload.php';

	$handler = new App\Handler();
	$handler->setAPIHeaders();
	echo App\HelpProvider::printHelp();
