<?php
	namespace App;

	class HelpProvider {
		public static function getHelp(): array {
			return [
				'version'  => '1.0.1',
				'date'     => '2020-09-26', //Y-m-d
				'commands' => [
					'/api/market_depth'  => 'Asks for market depth for a CRP/USDT trading pair',
					'/api/recent_trades' => 'Get data on recent trades',
					'/api/chart_data'    => 'Get data for building a trading chart',
					'/api/price_change'  => 'Trading volume, current and past rate',
					'/api/price_current' => 'Current rate for a CRP/USDT trading pair',
					'/api/order_book'    => 'Volume and prices of buy and sell orders'
				]
			];
		}

		public static function printHelp(): string {
			return Utilities::jsonReadableEncode(HelpProvider::getHelp());
		}
	}
