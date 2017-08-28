<?php
require_once("main.php");
require_once("Paypal.php");

$db = database();
template_top();
?>
<div id='requests' class='wrapper'>
	<div>
		<h2>Ваши заявки</h2>
		<?php
		if(!is_array($_SESSION["requests"])) $_SESSION["requests"] = [];
		if(isset($_GET["administrat"])){
			$requests = [];
			$req = $db->query("SELECT `id` FROM `request` ORDER BY `created` DESC");
			while($next = $req->fetch_assoc()) $requests[$next["id"]] = true;
		}
		else $requests = $_SESSION["requests"];
		foreach($requests as $request => $bar)
			if($request = $db->query("SELECT * FROM `request` WHERE `id` = $request")->fetch_assoc()){
				$found = true;
				echo "<div style='margin-bottom: 30px;'>" .
					"<h3>Заявка №$request[id]</h3>" .
					$request['data'] .
					"<div style='text-align: right'>";
				
				if(preg_match("/^PAY-/", $request['payment'])){
					try{
						initPaypal();
						$payment = new Payment(new Paypal(), $request["payment"]);
						if($payment->state == "approved") echo "Оплачено {$payment->amount} р."; else
						if($payment->state == "failed") echo "Оплата {$payment->amount} р. не удалась. Paypal: {$payment->failReason}";
						else echo "<a href='/request.php?request=$request[hash]'>Оплатить {$payment->amount} р. (Paypal)</a>";
					} catch(AppDie $e)
					{
						echo "Оплата отменена.";
					}
				}
				elseif(preg_match("/^([a-z]+)-([0-9]+)/", $request["payment"], $matches) && $matches[2] > 0){
					if($matches[1] == "paied") echo "Оплачено $matches[2] р.";
					else echo "<a href='/request.php?request=$request[hash]'>Оплатить $matches[2] р. (".($matches[1]=="card" ? "банковская карта" : "Paypal").")</a>";
				}
				else echo "Оплата 700 р. (на месте)";
					
				
				echo "</div>" .
				"</div>";
			}
		if(!$found) echo "Вы ещё не подали заявок.";
		?>
	</div>
</div>
<?php
template_bottom();
?>