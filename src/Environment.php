<?php
	namespace App;
	class Environment {
		public function __construct() {
			$this->loadFromENV();
		}

		public function loadFromENV() {
			$dotenv = \Dotenv\Dotenv::create(__DIR__ . "/../");
			$dotenv->load();
		}
	}
