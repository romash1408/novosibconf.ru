<?php
class Sendmail{
	private $dest,
			$theme,
			$data,
			$from,
			$headers = [
				'MIME-Version' => '1.0',
				'Content-type' => 'text/html; charset="windows-utf-8"',
			];
	
	public function __construct($dest = "")
	{
		if (is_array($dest))
		{
			$this->dest = $dest;

		} else {

			$this->dest = [];
			if (!empty($dest))
			{
				$this->dest[] = $dest;
			}
		}
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

	public function from($email)
	{
		if (!$email) return $this->from;

		$this->from = $email;
		return $this;
	}
	
	public function data($data)
	{
		if (gettype($data) == 'object' && get_class($data) == 'Closure')
		{
			$buffer = "";
			try{
				ob_start();
				$data = $data();
				$buffer = ob_get_contents();
				ob_end_clean();
			} catch(Exception $e){
				ob_end_clean();
				throw $e;
			}
			
			if (gettype($data) != "string")
			{
				$data = $buffer;
			}
			unset($buffer);
		}
		$this->data = $data;
		return $this;
	}
	
	public function send()
	{
		$this->headers["From"] = ($this->from ? $this->from : "system@$_SERVER[HTTP_HOST]");

		$headers = [];
		foreach ($this->headers as $header => $value)
		{
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