<?php
	namespace App;

	class Handler {
		public $logic = null;
		public $user  = null;
		public $renderT = null;
		public $last_error = '';
		public $solver = null; //Solver object

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
			return (int) Utilities::dataFilter($value, $this->db);
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
				'data'   => [],
				'error'  => $err_info
			]));
		}

		function cacheJsonToFile($url, $filename = 'cache.json'): bool {
			$json = Utilities::curlGET($url);
			if(! Utilities::isJson($json)) {
				$this->last_error = 'The requested file is not valid JSON';
				return false;
			} else {
				$filepath = __DIR__ . '/../cache/' . $filename;
				file_put_contents($filepath, $json);
				$status_success = file_exists($filepath);
				if($status_success) {
					return true;
				} else {
					$this->last_error = 'failed to save cached file named ' . $filename;
					return false;
				}
			}
		}

		public function updateCachedData(): bool {
			$trade_pair = 'CRPUSDT';
			$url_host = 'https://api.zg.com/openapi/quote/v1/';
			$cached_count = 0;

			$cache_orders = [
				'market_depth'  => 'depth?limit=50&symbol=' . $trade_pair,
				'recent_trades' => 'trades?limit=10&symbol=' . $trade_pair,
				'chart_data'    => 'klines?limit=30&interval=4h&symbol=' . $trade_pair,
				'price_change'  => 'ticker/24hr?symbol=' . $trade_pair,
				'price_current' => 'ticker/price?symbol=' . $trade_pair,
				'order_book'    => 'ticker/bookTicker?symbol=' . $trade_pair
			];

			foreach($cache_orders as $key => $value) {
				$status_success = $this->cacheJsonToFile(
					$url_host . $value, //url
					$key . '.json'      //filename
				);
				if($status_success) {
					$cached_count++;
				}
			}
			return $cached_count == count($cache_orders);
		}

		public function setAPIHeaders(): void {
			if(!headers_sent()) {
				header('Access-Control-Allow-Origin: *', false);
				header('Content-Type: application/json', false);
			}
		}

		public function parseRequest(): bool {
			$request = Utilities::dataFilter($_SERVER['REQUEST_URI']);
			$this->solver = new Solver();
			if(! $this->solver->parseRequest($request)) {
				$this->last_error = $this->solver->last_error;
				return false;
			} else {
				return true;
			}
		}
	}
