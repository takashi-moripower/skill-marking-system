# TakashiMoripower/CakeOpauth plugin for CakePHP

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require TakashiMoripower/CakeOpauth
```


vendor\opauth\facebook\FacebookStrategy.php
```
	private function me($access_token) {

		$params = array(
			'access_token' => $access_token,
			'appsecret_proof' => hash_hmac('sha256', $access_token, $this->strategy['app_secret'])			//	added
		);
```