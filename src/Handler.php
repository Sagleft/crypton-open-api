<?php
	namespace App;

	class Handler {
		public $logic = null;
		public $user  = null;
		public $renderT = null;
		public $last_error = '';

		protected $db      = null;
		protected $enviro  = null;
		protected $db_enabled = false;

		public function __construct() {
			$this->enviro  = new Environment();
			$this->db_enabled = getenv('db_enabled') == '1';
			if($this->isDBEnabled()) {
				$this->db = new DataBase();
			}
		}

		function isDBEnabled(): bool {
			return $this->db_enabled;
		}

		public function dataFilter($str = ''): string {
			if($this->isDBEnabled()) {
				return Utilities::dataFilter($str, $this->db);
			} else {
				return Utilities::dataFilter($str);
			}
		}
		
		public function checkINT($value = 0): int {
			return Utilities::dataFilter($value, $this->db);
		}
		
		public function apiSuccess($data = []): void {
			exit(json_encode([
				'status' => 'success',
				'data'   => $data,
				'error'  => ''
			]));
		}

		public function apiError($err_info = '', $err_code = 500): void {
			http_response_code($err_code);
			exit(json_encode([
				'status' => 'success',
				'data'   => $data,
				'error'  => ''
			]));
		}
	}
