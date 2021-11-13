<?php
namespace svgta\utils\crypt;
use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\Common\Formats\Keys\PKCS8;

class rsaCrypt{
	private $keys= [
		'private' => false,
		'public' => false,
	];

	private $clientPubKey = false;

	public function __construct(){
		PKCS8::setEncryptionAlgorithm('id-PBES2');
		PKCS8::setEncryptionScheme('aes256-CBC-PAD');
		PKCS8::setPRF('id-hmacWithSHA512-256');
		PKCS8::setIterationCount(4096);
	}

	public function setKeys($privateKey = false, $password = false){
		if(!$privateKey)
			$this->keys['private'] = RSA::createKey(2048);
		else
			$this->keys['private'] = RSA::load($privateKey, $password);
		$this->keys['public'] = $this->keys['private']->getPublicKey();
	}

	public function setClientKey($key){
		$this->clientPubKey = RSA::load($key);
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

	public function decrypt($msg){
		return $this->keys['private']->decrypt($msg);
	}

	public function encrypt($msg){
		return $this->clientPubKey->encrypt($msg);
	}

}