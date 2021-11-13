<?php
namespace svgta\utils\utils;
class trace{
	private $begin;
	private $end;
	private $inter;
	private $cli;

	public function __construct($from = false, $affDate = false){
		$this->cli = \svgta\utils\ctrl\ctrl::isCliRequest();
		if($this->cli){
			$this->begin = PHP_EOL;
			$this->end = PHP_EOL;
			$this->inter = PHP_EOL;
		}else{
			$this->begin = '<div class="svgtatrace"><pre>';
			$this->end = '</pre><br /></div>';
			$this->inter = '<br />';
		}
		
		echo $this->begin;
		if($this->cli)
			echo "\033[32m";
		if($affDate)
			print_r('--> trace ' . date('Y-m-d H:i:s') . ' : ');
		else
			print_r('--> trace : ');

		echo $this->inter;

		
		if($from){
			if($this->cli)
				echo "\033[36m";

			if(is_string($from))
				print_r('-> ' . $from);
			else
				print_r($from);
			echo $this->inter;
		}
	}

	public function __destruct(){
		if($this->cli)
			echo "\033[32m";
		print_r('--> trace end');
		echo $this->end;
		if($this->cli)
			echo "\033[39m";
	}

	public function getTrace($trace){
		if($this->cli)
			echo "\033[39m";

		print_r($trace);
		if(!is_array($trace) AND !is_object($trace))
			echo $this->inter;
	}
}