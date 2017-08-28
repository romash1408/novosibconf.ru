<?php
class Sendmail{
	private $dest = [],
			$theme = "Всёвозможно - информацинное сообщение",
			$data = "",
			$headers = [
				'MIME-Version' => '1.0',
				'Content-type' => 'text/html; charset="windows-utf-8"',
				'From' => 'info@xn----ctbbkhf9ajfcc0a.xn--p1ai',
			];
	
	public function __construct($dest = "")
	{
		if($dest) $this->dest[] = $dest;
	}
	
	public function clearDestonation()
	{
		$this->dest = [];
		return $this;
	}
	
	public function addDestonation($dest)
	{
		$this->dest[] = $dest;
		return $this;
	}
	
	public function setTheme($theme)
	{
		$this->theme = $theme;
		return $this;
	}
	
	public function data($data)
	{
		if(gettype($data) == 'object' && get_class($data) == 'Closure'){
			$buffer = "";
			try{
			ob_start(function($ob) use(&$buffer){
				$buffer .= $ob;
				return "";
			});
			$data = $data();
			ob_end_clean();
			} catch(AppDie $e){
				ob_end_clean();
				throw $e;
			}
			
			if(gettype($data) != "string") $data = $buffer;
			unset($buffer);
		}
		$this->data = $data;
		return $this;
	}
	
	public function send()
	{
		$headers = [];
		foreach($this->headers as $header => $value){
			$headers[] = "$header: $value";
		}
		$headers = implode("\r\n", $headers);
		foreach($this->dest as $to){
			@mail($to, $this->theme, $this->data, $headers);
		}
		return $this;
	}
	
};
?>