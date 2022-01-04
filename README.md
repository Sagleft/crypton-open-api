
![logo](https://raw.githubusercontent.com/Sagleft/crypton-open-api/master/logo.png)

## Application

You can use this solution as a proxy request. You can build your applications and website widgets based on this data.
Trading data is taken from the ZG.com exchange.

## Usage example

```
GET https://crypton.idyll.info/api/price_current
```
response example:

```
{"status":"success","data":{"symbol":"CRPUSDT","price":"0.1196"},"error":""}
```

## help request

```
GET https://crypton.idyll.info/api/help
```
response example:

```
{
	"version": "1.0.1",
	"date": "2020-09-26",
	"commands": 
	{
		"/api/market_depth": "Asks for market depth for a CRP/USDT trading pair",
		"/api/recent_trades": "Get data on recent trades",
		"/api/chart_data": "Get data for building a trading chart",
		"/api/price_change": "Trading volume, current and past rate",
		"/api/price_current": "Current rate for a CRP/USDT trading pair",
		"/api/order_book": "Volume and prices of buy and sell orders"
	}
}
```

---

![image](https://github.com/Sagleft/Sagleft/raw/master/image.png)

### :globe_with_meridians: [Telegram канал](https://t.me/+VIvd8j6xvm9iMzhi)
