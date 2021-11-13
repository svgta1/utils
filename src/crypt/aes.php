<?php
namespace svgta\utils\crypt;
use phpseclib3\Crypt\AES as secAes;
use phpseclib3\Crypt\Random;

class aes{
	private $iv;
	private $key;

	public function __contruct(){
		$this->iv = false;
		$this->key = false;
	}

	public function setIv($iv = false){
		if(!$iv)
			$iv = Random::string(16);
		$this->iv = $iv;
	}

	public function setKey($key = false){
		if(!$key)
			$key = Random::string(32);
		$this->key = $key;
	}

	public function getIv(){
		return $this->iv;
	}

	public function getKey(){
		return $this->key;
	}

	public function enc($value){
		$c = $this->ctrl();
		$enc = $c->encrypt($value);
		$this->iv = false;
		return $enc;
	}

	public function dec($cypher){
		$c = $this->ctrl();
		$dec = $c->encrypt($cypher);
		$this->iv = false;
		return $dec;
	}

	private function ctrl(){
		if(!$this->iv)
			throw new Exception('Iv not set');
		if(!$this->key)
			throw new Exception('key not set');
		$a = new secAes('ctr');
		$a->setIV($this->iv);
		$a->setKey($this->key);
		return $a;
	}
}
