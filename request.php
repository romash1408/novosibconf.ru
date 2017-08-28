<?php
header("Content-Type: text/html; charset=utf-8");
require_once("main.php");

$db = database();
require_once("Paypal.php");
initPaypal();
require_once("Sendmail.php");

function paied($email, $data, $payment){
	(new Sendmail("vsevozmozhno@gmail.ru"))->setTheme("Зарегестрирован пользователь")->data(function() use($data, $payment){
		?>
		<h2>Новый пользователь зарегистрирован на <a href='http://все-возможно.рф/'>ВСЁВОЗМОЖНО.РФ</a></h2>
		<h3>Данные пользователя:</h3>
		<?=$data?>
		<p><?=$payment?></p>
		<?php
	})->send();
	
	(new Sendmail($email))->setTheme("Регистрация на ВСЁВОЗМОЖНО")->data(function(){
		?>
		<p>Поздравляем! Вы успешно зарегистрированы на конференцию "Всёвозможно". Мы ждем вас 23 сентября по адресу: г. Новокузнецк, ул. Орджоникидзе 35/2, церковь "Новоильинская". Начало регистрации в 09:00</p>
		<p>Если у Вас возникнут дополнительные вопросы, Вы можете получить консультацию по телефонам:</p>
		<p><a href='tel:89133127056'>+7 (913) 312-70-56</a> – Данил</p>
		<p><a href='tel:89609234477'>+7 (960) 923-44-77</a> – Иван</p>
		<p style='font-weight: bold;'>Обратите внимание! Разница во времени с Москвой + 4 часа.</p>
		<?php
	})->send();
}

/*Find or create request*/
if(isset($_GET["request"])){
	$request = $db->query("SELECT * FROM `request` WHERE `hash` = '".$db->real_escape_string($_GET["request"])."'");
}
elseif(isset($_GET["paymentId"])){
	$payment = new Payment(
		new Paypal(),
		$_GET["paymentId"]
	);
	$payment->exec($_GET["PayerID"]);
	
	$request = $db->query("SELECT * FROM `request` WHERE `payment` = '{$payment->id}'") or die($db->error);
	if(!($request = $request->fetch_assoc())){
		template_top();
		echo "<div class='wrapper'><div>Не удалось найти ваши данные на сайте. Пожалуйста, обратитесь за помощью по электронной почте <a href='mailto:romash1408@yandex.ru'>romash1408@yandex.ru</a>, указав идентификатор оплаты и контактный номер. Мы обязательно свяжемся с вами. Администрация просит прощения за доставленные неудобства.</div></div>";
		template_bottom();
		exit();
	}
	
	paied($request["email"], $request["data"], "Произведена оплата в размере {$payment->amount} руб.");
}
elseif(isset($_POST["notification_type"])){
	if(sha1($_POST["notification_type"] ."&". $_POST["operation_id"] ."&". $_POST["amount"] ."&". $_POST["currency"] ."&". $_POST["datetime"] ."&". $_POST["sender"] ."&". $_POST["codepro"] ."&". "BuoPcUSZ3jE92Nit9yA49jS0" ."&". $_POST["label"]) != $_POST["sha1_hash"]){
		header("HTTP/1.x 404 Not Found");
	}
	elseif
	(
		preg_match("/request([0-9]+)/", $_POST["label"], $matches) &&
		($request = $db->query("SELECT * FROM `request` WHERE `id` = $matches[1] AND `payment` = 'card-".floor($_POST["withdraw_amount"])."'")) &&
		($request = $request->fetch_assoc())
	){
		$db->query("UPDATE `request` SET `payment` = 'paied-".floor($_POST["withdraw_amount"])."' WHERE `id` = $matches[1]");
		paied($request["email"], $request["data"], "Произведена оплата в размере {$_POST["withdraw_amount"]} руб.");
	}
	die();
}
else{
	$info = [];
	if(!$_POST["name"] || !$_POST["phone"] || !$_POST["email"] || !$_POST["Город"] || !$_POST["Церковь"]){
		die("Не указано одно из обязательных полей <script>history.back();</script>");
	}

	foreach($_POST as $field => $value){
		if($value == "") continue;
		if($field == "name") $field = "ФИО"; else
		if($field == "phone") $field = "Контактный_телефон"; else
		if($field == "email") $field = "Электронная_почта"; else
		if($field == "payment") continue; else
		if($field == "pay_method") continue;
		
		$field = str_replace('_', ' ', $field);
		
		$info[] =
			"<div><label style='width: 300px; color: grey; display: inline-block; margin-bottom: 5px; font-weight: 700;'>" .
			($value !== "1" ?
				"$field:</label> $value" :
				"</label>$field"
			) .
			"</div>";
	}
	
	$info = 
		"<div style='display: block; padding: 9.5px; margin: 10px 0; font-size: 13px; line-height: 1.42857143; color: #333; word-break: break-all; word-wrap: break-word; background-color: #f5f5f5; border: 1px solid #ccc; border-radius: 4px;'>" .
		implode("", $info) .
		"</div>";
	$hash = sha1($info);
		
	if(!($request = $db->query("SELECT * FROM `request` WHERE `hash`='$hash'")->fetch_assoc())){
		if(time() < 1472576399 && $_POST["payment"] == 500) $price = 500;
		elseif(time() < 1474390799 && $_POST["payment"] == 600) $price = 600;
		else{
			$price = 0;
			paied($_POST["email"], $info, "Оплата на месте в размере <b>700 руб.</b>");
		}
		
		$info = $db->real_escape_string($info);
		$email = $db->real_escape_string($_POST["email"]);
		$method = $db->real_escape_string($_POST["pay_method"]);
		
		$db->query("INSERT INTO `request` (`email`, `data`, `payment`, `hash`) VALUES ('$email', '$info', '$method-$price', '$hash')") or die($db->error);
		$request = $db->insert_id;
		$_SESSION["requests"][$request] = true;
		$request = $db->query("SELECT * FROM `request` WHERE `id` = $request")->fetch_assoc();
		
		(new Sendmail($request["email"]))->setTheme("Всёвозможно - Начало регистрации")->data(function() use($request){
			?>
			<p>Вы начали регистрацию на <a href='http://все-возможно.рф'>ВСЁВОЗМОЖНО</a>.</p>
			<p>Чтобы оплатить заявку или просмотреть информацию о ней, вы можете перейти по ссылке <a href='http://все-возможно.рф/request.php?request=<?=$request["hash"]?>'>http://все-возможно.рф/request.php?request=<?=$request["hash"]?></a></p>
			<?php
		})->send();
	}
}

/*What to do with request*/
if(is_array($request) || ($request = $request->fetch_assoc())){
	if(preg_match("/^([a-z]+)-([0-9]+)/", $request["payment"], $matches) && $matches[2] > 0){
		switch($matches[1]){
		case "paypal":
			$paypal = new Paypal();
			$item = [
				"type" => "item",
				"name" => "Заявка №" . $request["id"],
				"price" => $matches[2]
			];
			$bill = $paypal->createPayment([$item], "Регистрация на конференцию Всёвозможно", "http://$_SERVER[SERVER_NAME]/request.php", "http://$_SERVER[SERVER_NAME]/requests.php");
			
			if(!isset($bill->urls["approval_url"]))
			{
				template_top();
				echo "Произошла ошибка при соединении с PayPal. Ваша заявка сохранена и доступна для повторной попытки оплаты на странице <a href='http://$_SERVER[SERVER_NAME]/requests.php'>http://$_SERVER[SERVER_NAME]/requests.php</a>. Пожалуйста, сохраните эту ссылку и попробуйте оплатить заявку позднее.";
				template_bottom();
				die();
			}
			
			$db->query("UPDATE `request` SET `payment` = '{$bill->id}' WHERE `id` = $request[id]");
			header("Location: {$bill->urls["approval_url"]}");
			die();
			break;
		case "card":
			?>
			Перенаправление на страницу оплаты...<br />
			<form method="POST" action="https://money.yandex.ru/quickpay/confirm.xml" id='payForm'> 
				<input type="hidden" name="receiver" value="410014459812201"> 
				<input type="hidden" name="formcomment" value="Источник жизни"> 
				<input type="hidden" name="short-dest" value="Регистрация на конференцию Всёвозможно"> 
				<input type="hidden" name="label" value="request<?=$request["id"]?>"> 
				<input type="hidden" name="quickpay-form" value="donate"> 
				<input type="hidden" name="targets" value="Оплата заявки №<?=$request["id"]?>"> 
				<input type="hidden" name="sum" value="<?=$matches[2]?>" data-type="number"> 
				<input type="hidden" name="successURL" value="http://все-возможно.рф/requests.php"> 
				<input type="hidden" name="need-fio" value="false"> 
				<input type="hidden" name="need-email" value="false"> 
				<input type="hidden" name="need-phone" value="false"> 
				<input type="hidden" name="need-address" value="false"> 
				<input type="hidden" name="paymentType" value="AC">
				<input type="submit" value="Перейти на страницу оплаты"> 
			</form>
			<script>setTimeout(function(){document.getElementById("payForm").submit();}, 1500);</script>
			<?php
			die();
			break;
		}
	}
	
	if(preg_match("/^PAY-/", $request["payment"]) && ($payment = new Payment(new Paypal(), $request["payment"])) && $payment->state == "created"){
		header("Location: {$payment->urls["approval_url"]}");
		die();
	} else{
		$_SESSION["requests"][$request["id"]] = true;
		header("Location: /requests.php");
		die();
	}
} else {
	template_top();
	echo "<div class='wrapper'><div>Заявка не найдена.</div></div>";
	template_bottom();
}
?>