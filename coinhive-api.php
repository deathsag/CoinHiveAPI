<?php
// Instantiate the class with your secret key
$coinhive = new CoinHiveAPI('fmC6jSW3DKyK5D8QQrwd40rRAUf9rUCw');

// Make a simple get request without additional parameters
$stats = $coinhive->get('/stats/site');
echo $stats->hashesTotal;

// Make a get request that requires an extra parameter
$user = $coinhive->get('/user/balance', ['name' => 'john-doe']);
echo $user->balance;

// Make a post request
$link = $coinhive->post('/link/create', [
	'url' => 'http://google.com', 
	'hashes' => 1024
]);

if ($link->success) {
	echo $link->url;
}

class CoinHiveAPI {
	const API_URL = 'https://api.coinhive.com';
	private $secret = null;
	public function __construct($secret) {
		if (strlen($secret) !== 32) {
			throw new Exception('CoinHive - Invalid Secret');
		}
		$this->secret = $secret;
	}
  
	function get($path, $data = []) {
		$data['secret'] = $this->secret;
		$url = self::API_URL.$path.'?'.http_build_query($data);
		$response = file_get_contents($url);
		return json_decode($response);
	}
	
	function post($path, $data = []) {
		$data['secret'] = $this->secret;
		$context = stream_context_create([
			'http' => [
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($data)
			]
		]);
		$url = SELF::API_URL.$path;
		$response = file_get_contents($url, false, $context);
		return json_decode($response);
	}	
}
