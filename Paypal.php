<?php
class AppDie extends Exception{}

class Paypal{
	public static $sandbox = "", $id = "", $secret = "",
		$CREATE_URL = "", $TOKEN_URL = "";
	
	public static function init($settings){
		if(isset($settings)){
			if(isset($settings["sandbox"])) self::$sandbox = $settings["sandbox"];
			self::$id = $settings[self::$sandbox . "id"];
			self::$secret = $settings[self::$sandbox . "secret"];
			self::$CREATE_URL = 'https://api.'.self::$sandbox.'paypal.com/v1/payments/payment';
			self::$TOKEN_URL = 'https://api.'.self::$sandbox.'paypal.com/v1/oauth2/token';
		} else throw new AppDie("Paypal settings are not set.");
	}
	
	private $scope, $nonce, $token, $type, $app, $remain;
	
	public function __construct($id = null, $secret = null){
		if($id == null) $id = self::$id;
		if($secret == null) $secret = self::$secret;
		
		$token = $this->curl(
			self::$TOKEN_URL,
			["Accept: application/json", "Accept-Language: en_US"],
			"grant_type=client_credentials",
			"POST",
			function(&$ch) use($id, $secret){
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($ch, CURLOPT_USERPWD, $id . ":" . $secret);
			}
		);
		
		if(!isset($token["access_token"])) throw new AppDie("Connect to Paypal failed, wrong id or secret. ".self::$id);
		$this->scope = $token["scope"];
		$this->nonce = $token["nonce"];
		$this->token = $token["access_token"];
		$this->type = $token["token_type"];
		$this->app = $token["app_id"];
		$this->remain = $token["expires_in"];
	}
	
	public function createPayment($items, $description, $success, $cancel)
	{
		$transaction = [
			"amount" => [
				"total" => 0,
				"currency" => "RUB",
				"details" => [
					"subtotal" => 0,
					"shipping_discount" => 0
				]
			],
			"description" => $description,
			"item_list" => [
				"items" => []
			]
		];
		
		foreach($items as $item){
			switch($item["type"]){
			case "item":
				if(!isset($item["count"])) $item["count"] = 1;
				$transaction["item_list"]["items"][] = 
					[
						"name" => $item["name"],
						"price" => $item["price"],
						"currency" => "RUB",
						"quantity" => $item["count"],
						"description" => $item["description"]
					];
				$transaction["amount"]["total"] += $item["price"] * $item["count"];
				$transaction["amount"]["details"]["subtotal"] += $item["price"] * $item["count"];
				break;
			case "discount":
					$transaction["amount"]["total"] -= $item["size"];
					$transaction["amount"]["details"]["shipping_discount"] -= $item["size"];
				break;
			}
		}
		$bill = $this->curl(
			self::$CREATE_URL,
			'Content-Type: application/json',
			json_encode([
				"intent" => "sale",
				"redirect_urls" => [
					"return_url" => $success,
					"cancel_url" => $cancel
				],
				"payer" => [
					"payment_method" => "paypal"
				],
				"transactions" => [$transaction]
			])
		);
		

		if(!isset($bill["state"]) || $bill["state"] != "created"){
			if(isset($bill["details"])) 
				$bill["message"] .= array_reduce($bill["details"], function($last, $error){
					return $last . "<br /><i style='font-size:70%'>$error[field]: $error[issue]</i>";
				}, "");
			else
				$bill["message"] = print_r($transaction,1);
			throw new AppDie("Paypal error: " . $bill["message"]);
		}
		
		return new Payment($this, $bill["id"]);
	}
	
	public function curl($url, $headers = [], $data = '', $method = "POST", $handler = null){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
		curl_setopt($ch, CURLOPT_SSLVERSION , 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		if(!is_array($headers)) $headers = [$headers];
		if($this->token) $headers[] = "Authorization: {$this->type} {$this->token}";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		if(is_array($data)) $data = implode("&", $data);
		if($data != '') curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		if(get_class($handler) == "Closure") $handler($ch);
		$res = curl_exec($ch);
		if($res === false){
			$error = curl_error($ch);
			curl_close($ch);
			throw new AppDie("Error with connection to PayPal: " . $error);
		}
		curl_close($ch);
		$assoc = json_decode($res, 1);
		if($assoc === null) return $res;
		else return $assoc;
	}
};

class Payment{
	private $pp;
	public $id, $state, $amount, $discount, $urls, $failReason = '';
	//Amount is only paid money (summ - discount)
	
	public function __construct(Paypal $pp, $id){
		$this->pp = $pp;
		$this->id = $id;
		$this->update();
			
	}
	
	public function update()
	{
		if(preg_match("/discount-([0-9]+)/", $this->id, $match)){
			$this->id = "discount";
			$this->amount = 0;
			$this->discount = -$match[1];
			$this->state = "completed";
			$this->urls = [];
			return ;
		}
		$bill = $this->pp->curl(
			Paypal::$CREATE_URL . "/" . $this->id,
			'Content-Type: application/json',
			'', "GET"
		);
		if(!isset($bill["state"])) throw new AppDie("Payment {$this->id} wasn't found (<pre>" . (isset($bill["message"]) ? $bill["message"] : str_replace("\n", '<br />', print_r($bill, 1))) . "</pre>)");
		$this->id = $bill["id"];
		$this->payer = $bill["payer"];
		$this->state = $bill["state"];
		if($this->state == "failed") $this->failReason = $bill["failure_reason"];
		$this->amount = $bill["transactions"][0]["amount"]["total"] * 1;
		$this->discount = 0 + $bill["transactions"][0]["amount"]["details"]["shipping_discount"] * 1;
		$this->urls = [];
		foreach($bill["links"] as $link){
				$this->urls[$link["rel"]] = $link["href"];
		}
	}
	
	public function exec($payerId){
		if($this->id =='discount') return true;
		
		if(!isset($this->urls["execute"]))
			throw new AppDie("Payment {$this->id} coudn't be executed. Status: {$this->state}");
		
		$bill = $this->pp->curl(
			$this->urls["execute"],
			'Content-Type: application/json',
			json_encode(["payer_id" => $payerId])
		);
		
		if(!isset($bill["state"]))
			if(isset($bill["message"]))
				throw new AppDie($bill["message"]);
			else throw new AppDie(str_replace("\n", '<br />', print_r($bill, 1)));
		
		$this->update();
		
		if($this->state == "failed")
			throw new AppDie($bill["failure_reason"]);
		if($this->state != "completed" && $this->state != "approved")
			throw new AppDie("Payment status is {$this->state}");
		return true;
	}
};
?>