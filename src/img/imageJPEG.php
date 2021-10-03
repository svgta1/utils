<?php
namespace svgtaUtils\img;

class imageJPEG{
	const DEFAULT_SIZE = [
		'width' => 1200,
		'height' => 900,
	];

	const JPEG_QUALITY = 90;

	private $filePath = false;
	private $maxWidth = false;
	private $maxHeight = false;

	public function __construct($filePath = false, $maxWidth = false, $maxHeight = false, $quality = false){
		$this->filePath = $filePath;
		$this->maxWidth = $maxWidth ? $maxWidth : self::DEFAULT_SIZE["width"];
		$this->maxHeight = $maxHeight ? $maxHeight : self::DEFAULT_SIZE["height"];
		$this->quality = $quality ? $quality : JPEG_QUALITY;

	}

	public function convert($savePath = false){
		$image = \Imagecow\Image::fromFile($this->filePath);
		$image->resize($this->maxWidth, $this->maxHeight);
		$image->quality($this->quality);
		$image->autoRotate();
		
		$image->save($savePath);
	}
}