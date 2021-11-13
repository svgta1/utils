<?php
namespace svgta\utils\ctrl;
class ctrl{
	public static function isBot() {
		if($_SERVER["REMOTE_ADDR"] == $_SERVER["SERVER_ADDR"])
			return true;

		return (
			isset($_SERVER['HTTP_USER_AGENT'])
				&& preg_match('/bot|crawl|slurp|spider|mediapartners|facebook|Lighthouse|DareBoost|Qwant|google/i', $_SERVER['HTTP_USER_AGENT'])
		);
	}
	public static function getHostname(){
		return $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"];
	}

	public static function isCliRequest(){
		if(php_sapi_name() == 'cli')
			return true;
		return false;
	}
}