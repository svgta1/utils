<?php
namespace svgtaUtils\otp;
use OTPHP\TOTP;
use OTPHP\Factory;
use ParagonIE\ConstantTime\Base32;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class svgtatotp{
	private $totp;
	private $imgbase64;

	private const ALGO = [
		'sha1',
		'sha256',
		'sha512',
	];
	private const DIGITS = [
		6,
		8,
	];

	private const DEFAULT = [
		'algo' => 'sha1',
		'digits' => 6,
	];

	public static function setTotp($label, $issuer, $pwd = false, $sha = self::DEFAULT['algo'], $digits = self::DEFAULT['digits']){
		if(!$pwd)
			$pwd = trim(Base32::encodeUpper(random_bytes(32)));
		$totp = TOTP::create(
			$pwd,
			30,
			$sha,
			$digits
		);
		$totp->setLabel($label);
		$totp->setIssuer($issuer);
		return [
			'prov' => $totp->getProvisioningUri(),
			'key' => $totp->getSecret(),
			'code' => $totp->now(),
			'algo' => $totp->getDigest(),
			'digits' => $totp->getDigits(),
		];
	}

	public static function setMultiTotp($label, $issuer, $pwd){
		$res = self::_setMultiTotp($label, $issuer, $pwd);
		$res[img] = self::getQrCode($res['sha512-8']['prov']);
		return $res;
	}

	private static function _setMultiTotp($label, $issuer, $pwd){
		$res = [];
		foreach(self::DIGITS as $d){
			foreach(self::ALGO as $a){
				$k = $a.'-'.$d;
				$res[$k] = self::setTotp($label, $issuer, $pwd, $a, $d);
			}
		}

		return $res;
	}

	private static function getQrCode($prov){
		$temp_file = tempnam(sys_get_temp_dir(), 'qrcode_'.trim(Base32::encodeUpper(random_bytes(8))).'.png');
		$renderer = new ImageRenderer(
			new RendererStyle(256),
			new ImagickImageBackEnd()
		);
		$writer = new Writer($renderer);
		$writer->writeFile($prov, $temp_file);
		$data = file_get_contents($temp_file);
		unlink($temp_file);
		return 'data:image/png;base64,' . base64_encode($data);
	}

	public static function getImg($prov){
		return self::getQrCode($prov);
	}

	public static function getFromProv($prov){
		$otp = Factory::loadFromProvisioningUri($prov);
		return [
			'prov' => $otp->getProvisioningUri(),
			'key' => $otp->getSecret(),
			'code' => $otp->now(),
			'algo' => $otp->getDigest(),
			'digits' => $otp->getDigits(),


		];
	}

	public static function getCode($prov){
		$otp = Factory::loadFromProvisioningUri($prov);
		return $otp->now();
	}

	public static function verifyCode($prov, $code){
		$otp = Factory::loadFromProvisioningUri($prov);
		return $otp->verify($code);
	}

	public static function getCompatibleDivice($label, $issuer, $pwd, $code){
		$multi = self::_setMultiTotp($label, $issuer, $pwd);
		foreach($multi as $r){
			if(self::verifyCode($r['prov'], $code))
				return $r;
		}

		throw new \Exception('Code not available');
	}
}
