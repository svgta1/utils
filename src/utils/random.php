<?php
namespace svgtaUtils\utils;

class random{
	const MIN_PWD_LENGTH = 10;
	const TOKEN_DEFAULT_LEN = 32;
	const MIN_TOKEN_LENGTH = 16;
	const SALT_LENGTH = 32;

	public static function genUUID(){
		return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			// 32 bits for "time_low"
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

			// 16 bits for "time_mid"
			mt_rand( 0, 0xffff ),

			// 16 bits for "time_hi_and_version",
			// four most significant bits holds version number 4
			mt_rand( 0, 0x0fff ) | 0x4000,

			// 16 bits, 8 bits for "clk_seq_hi_res",
			// 8 bits for "clk_seq_low",
			// two most significant bits holds zero and one for variant DCE1.1
			mt_rand( 0, 0x3fff ) | 0x8000,

			// 48 bits for "node"
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
		);
	}

	public static function pwdGen($len = 16){
		if($len < self::MIN_PWD_LENGTH)
			throw new Exception('Password Lenght to small');
		$pwd = new \TBETool\PasswordGenerator($len);
		return $pwd->generate();
	}

	public static function tokenGen($len = self::TOKEN_DEFAULT_LEN){
		if($len < self::MIN_TOKEN_LENGTH)
			throw new Exception('Token Lenght to small');
		$token = new \TBETool\PasswordGenerator($len, 1, 'lower_case,upper_case,numbers');
		return $token->generate();		
	}
	
	public static function genSalt(){
		$len = self::SALT_LENGTH;
		$salt = new \TBETool\PasswordGenerator($len, 1, 'lower_case,numbers');
		return $salt->generate();
	}
}