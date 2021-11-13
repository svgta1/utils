<?php
namespace svgta\utils\utils;

class conv{
	public static function b64Encode($in){
		return \base64_encode($in);
	}
	public static function b64Decode($in){
		return \base64_decode($in);
	}
	public static function h2b($in){
		return \hex2bin($in);
	}
	public static function b2h($in){
		return \bin2hex($in);
	}


}