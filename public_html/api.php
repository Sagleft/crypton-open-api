<?php
	require_once __DIR__ . '/../vendor/autoload.php';

	$handler = new App\Handler();
	$handler->setAPIHeaders();

	if(! $handler->parseRequest()) {
		$handler->apiError($handler->last_error);
	}

	$requestRoot = $handler->solver->getRoot();
	if(empty($requestRoot)) {
		$handler->apiError($handler->solver->last_error);
	}

	if($requestRoot == 'help') {
		echo App\HelpProvider::printHelp(); exit;
	}

	$available_methods = ['market_depth', 'recent_trades', 'chart_data', 'price_change', 'price_current', 'order_book'];
	if(!in_array($requestRoot, $available_methods)) {
		$handler->apiError('method not found', 404);
	}

	$data_path = __DIR__ . '/../cache/' . $requestRoot . '.json';
	$data_json = file_get_contents($data_path);
	if(! App\Utilities::isJson($data_json)) {
		$handler->apiError('Data request server error', 500);
	}

	$handler->apiSuccess(json_decode($data_json));
