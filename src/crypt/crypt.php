<?php
namespace svgta\utils\crypt;
use svgta\utils\utils\conv;
user svgta\utils\utils;
class crypt{
	const TYPE_DECODE = [
		'b64' => "b64Decode",
		'hex' => "h2b",
	];
	const TYPE_ENCODE = [
		'b64' => "b64Encode",
		'hex' => "b2h",
	];
	const DEFAULT_TYPE = 'b64';
	const HELP = [
		'Server : privateKey' => 'Charger la clé privée RSA via la méthode setPrivateKey($key)',
		'Server : signature' => 'Charger la clé privée de signature via la méthode setSignKey($key). Elle peut être la même que la clé privée RSA',
		'Client : publicKey' => 'Charger la clé publique du client via la méthode setClientKey($key)',
		'Client : signature' => 'Charger la clé publique de signature du client via la méthode setClientSignKey($key). Elle peut etre la même que la clé publique RSA du client';
	]

	$private $sPk;
	$private $sSk;
	$private $cPk;
	$private $cSk;


	public function __construct($help = false){
		if($help){
			foreach(self::HELP as $k=>$v){
				utils::getTrace($k . ' : ' . $v );
			}
		}
	}

	public function setPrivateKey($key){
	}
	public function setSignKey($sign){
	}
	public function setClientKey(key){
	}
	public function setClientSignKey(key){
	}


	public function setTypeEncode($type){
		if(!isset(self::TYPE_ENCODE[$type]))
			throw new Exception('Type non valide : b64 ou hex');

		$this->type = $type;
	}

}
