<?php

echo "<pre>";
print_r($_SERVER);
die();
require_once("../main.php");
require_once("../Paypal.php");
require_once("../Sendmail.php");

define('__ROOT__', explode(":", $_SERVER["HTTP_REFERER"], 2)[0] . '://' . $_SERVER["HTTP_HOST"]);

$db = database();
template_top();
?>
<div id='requests' class='wrapper'>
	<div>
		<div>
			<h2>Ваши заявки</h2>
		</div>
		<?php
		if(isset($_GET["administrat"])){
			$requests = [];
			$req = $db->query("SELECT `id` FROM `request` ORDER BY `id` ASC");
			while($next = $req->fetch_assoc()) $requests[] = $next["id"];
		}
		else $requests = $_SESSION["requests"];

		for ($i = count($requests) - 1; $i >= 0; --$i) {
			$request = $db->query("SELECT * FROM `request` WHERE `id` = {$requests[$i]}");
			if ($request) {
				$request = $request->fetch_assoc();
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
				elseif(preg_match("/^([a-z]+)-([0-9]+)/", $request["payment"], $matches) && $matches[1] != "place"){
					if($matches[1] == "paied"){
						echo "Оплачено $matches[2] р.";
						if(isset($_POST["sendmail"])){
							(new Sendmail("spam@romash1408.ru"))->setTheme("Зарегестрирован пользователь")->data(function() use($request, $matches){
								?>
								<h2>Новый пользователь зарегистрирован на <a href='<?=__ROOT__?>/'>Конференцию тюремного служения</a></h2>
								<h3>Данные пользователя:</h3>
								<?=$request["data"]?>
								<p><?="Произведена оплата в размере <b>$matches[2] руб.</b>"?></p>
								<?php
							})->send();
						}
					}
					else echo "<a href='/request.php?request=$request[hash]'>Оплатить $matches[2] р. (".($matches[1]=="card" ? "банковская карта" : "Paypal").")</a>";
				}
				else{
					echo "Оплата $matches[2] р. (на месте)";
					if(isset($_POST["sendmail"])){
						(new Sendmail("spam@romash1408.ru"))->setTheme("Зарегестрирован пользователь")->data(function() use($request, $matches){
							?>
							<h2>Новый пользователь зарегистрирован на <a href='<?=__ROOT__?>/'>Конференцию тюремного служения</a></h2>
							<h3>Данные пользователя:</h3>
							<?=$request["data"]?>
							<p><?="Оплата на месте в размере <b>$matches[2] руб.</b>"?></p>
							<?php
						})->send();
					}
				}
					
				
				echo "</div>" .
				"</div>";
			}
		}

		if(!$found) echo "Вы ещё не подали заявок.";
		?>
	</div>
</div>
<?php
template_bottom();
?>