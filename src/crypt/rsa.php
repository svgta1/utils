<?php
namespace svgtaUtils\crypt;
use svgtaUtils\utils\conv;
class rsa{
	private $sign;
	private $rsa;
	private $aes;
	private $type;

	const TYPE_DECODE = [
		'b64' => "b64Decode",
		'hex' => "h2b",
	];

	const TYPE_ENCODE = [
		'b64' => "b64Encode",
		'hex' => "b2h",
	];


	const DEFAULT_TYPE = 'b64';

	public function __construct(){
		$this->sign = new rsaSign();
		$this->rsa = new rsaCrypt();
		$this->aes = new aes();
		$this->type = self::DEFAULT_TYPE;
	}
	public function setTypeEncode($type){
		if(!isset(self::TYPE_ENCODE[$type]))
			throw new Exception('Type non valide : b64 ou hex');

		$this->type = $type;
	}
	public function getRsaKeys(){
		$ret = [
			'sign' => [
				'publicKey' => $this->sign->getPublicKey(),
				'privateKey' => $this->sign->getPrivateKey(),
			],
			'crypt' => [
				'publicKey' => $this->rsa->getPublicKey(),
				'privateKey' => $this->rsa->getPrivateKey(),
			]
		];
		return $ret;
	}
	public function setClientKeys($signKey, $rsaKey){
		$this->sign->setClientKey($signKey);
		$this->rsa->setClientKey($rsaKey);
	}
	public function setServerPrivateKeys($signKey = false, $rsaKey = false, $password = false){
		$this->sign->setKeys($signKey);
		$this->rsa->setKeys($rsaKey, $password);
	}
	public function encRsaMessage($msg){
		$cypher = $this->rsa->encrypt($msg);
		try{
			$sign = $this->sign->sign($cypher);
		}catch(\Throwable $e){
			$sign = false;
		}
		return [
			'cypher' => conv::{self::TYPE_ENCODE[$this->type]}($cypher),
			'sign' => $sign ? conv::{self::TYPE_ENCODE[$this->type]}($sign) : false,
			'type' => $this->type,
		];
	}
	public function decRsaMessage($cypher, $sign, $type = false){
		if(!$type)
			$type = $this->type;
		
		$cypher = conv::{self::TYPE_DECODE[$type]}($cypher);
		$sign = conv::{self::TYPE_DECODE[$type]}($sign);

		try{
			if(!$this->sign->verify($cypher, $sign))
				return ['verify' => false, 'reason' => 'Bad sign client public key'];
		}catch(\Throwable $e){
			return ['verify' => false, 'reason' => 'No sign client public key given', 'message' => $this->rsa->decrypt($cypher)];
		}
		return ['verify' => true, 'message' => $this->rsa->decrypt($cypher)];
	}

	public function genAesKey(){
		$this->aes->setKey();
		return [
			'key' => conv::{self::TYPE_ENCODE[$this->type]}($this->aes->getKey()),
			'type' => $this->type,
		];
	}
	public function setAesKey($key, $type = false){
		if(!$type)
			$type = $this->type;
		$this->aes->setKey(conv::{self::TYPE_DECODE[$type]}($key));
	}
	public function encAesMessage($message){
		$this->aes->setIv();
		$iv = $this->aes->getIv();
		$cypher = $this->aes->enc($message);
		return [
			'cypher' => conv::{self::TYPE_ENCODE[$this->type]}($iv) . ':' . conv::{self::TYPE_ENCODE[$this->type]}($cypher),
			'type' => $this->type,
		];
	}
	public function decAesMessage($cypher, $type = false){
		if(!$type)
			$type = 'b64';
		$a = explode(':', $cypher);
		$iv = conv::{self::TYPE_DECODE[$type]}($a[0]);
		$cy = conv::{self::TYPE_DECODE[$type]}($a[1]);
		$this->aes->setIv($iv);
		return $this->aes->dec($cy);
	}
}