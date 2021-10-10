<?php
namespace svgtaUtils;

class utils{
	public static function verifyOtp($prov, $code){
		if(!$prov)
			throw new Exception('Prov needed, generated by setOtp');
		if(!$code)
			throw new Exception('Code needed, gived by the user');

		$otp = otp\svgtatotp::verifyCode($prov, $code);
		return $otp;
	}
	public static function setOtp($user, $domain){
		$otp = otp\svgtatotp::setTotp($user, $domain);
		return $otp;
	}

	public static function getOtpImg($prov){
		if(!$prov)
			throw new Exception('Prov needed, generated by setOtp');
		$otp = otp\svgtatotp::getImg($prov);
		return $otp;
	}

	public static function getTrace($trace, $back = false){
		if($back)
			$t = new utils\trace('script appelant : ' . debug_backtrace()[0]['file'] . ' ; ligne : ' . debug_backtrace()[0]['line'], true);
		else
			$t = new utils\trace();
		$t->getTrace($trace);
	}

	public static function crypt(){
		$crypt = new crypt\crypt();
		return $crypt;
	}

	

}