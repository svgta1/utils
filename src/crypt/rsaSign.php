<?php
namespace svgta\utils\crypt;
use phpseclib3\Crypt\EC;

class rsaSign{
	private $keys= [
		'private' => false,
		'public' => false,
	];

	private $clientPubKey = false;

	public function setKeys($privateKey = false){
		if(!$privateKey)
			$this->keys['private'] = EC::createKey('Ed25519');
		else
			$this->keys['private'] = EC::load($privateKey);

		$this->keys['public'] = $this->keys['private']->getPublicKey();
	}

	public function setClientKey($key){
		$this->clientPubKey = EC::load($key);
	}

	public function getPrivateKey(){
		return $this->keys['private']->toString('PKCS8');
	}
	public function getProtectedKey($password){
		return $this->keys['private']->withPassword($password)->toString('PKCS8');
	}
	public function getUnProtectedKey($password){
		return $this->keys['private']->withPassword()->toString('PKCS8');
	}

	public function getPublicKey(){
		return $this->keys['public']->toString('PKCS8');
	}

	public function sign($msg){
		return $this->keys['private']->sign($msg);
	}

	public function verify($msg, $sign){
		return $this->clientPubKey->verify($msg, $sign);
	}

}