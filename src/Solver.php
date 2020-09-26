<?php
	namespace App;

	class Solver {
		public $request_raw   = '';
		public $last_error    = '';
		public $request_parts = [];

		public function __construct($request = '') {
			$this->parseRequest($request);
		}

		public function parseRequest($request): bool {
			if($request == '') {
				$this->last_error = 'empty request received';
				return false;
			}

			$this->request_raw = $request;
			$this->request_parts = explode('/', $this->request_raw);

			if(count($this->request_parts) < 3) {
				http_response_code(400); //bad request
				HelpProvider::printHelp();
			}

			return true;
		}

		public function getRequestParts(): array {
			return $this->request_parts;
		}

		public function getPart($index = 2): string {
			if(count($this->request_parts) > $index) {
				return urldecode(trim($this->request_parts[$index]));
			} else {
				http_response_code(204); //no content
				$this->last_error = 'request element #' . $index . ' not found';
				return '';
			}
		}

		public function getRoot(): string {
			return $this->getPart(2);
		}
	}
