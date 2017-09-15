<?php
header("Content-Type: text/html; charset=utf-8");
require_once("../main.php");

define('__ROOT__', explode(":", $_SERVER["HTTP_REFERER"], 2)[0] . '://' . $_SERVER["HTTP_HOST"]);

$db = database();
//require_once("../Paypal.php");
//initPaypal();
require_once("../Sendmail.php");

function paied($email, $data, $payment){
	(new Sendmail("orehov19@gmail.com"))->setTheme("Зарегестрирован пользователь | novosibconf.ru")->data(function() use($data, $payment){
		?>
		<h2>Новый пользователь зарегистрирован на <a href='<?=__ROOT__?>'>конференции тюремного служения</a></h2>
		<h3>Данные пользователя:</h3>
		<?=$data?>
		<p><?=$payment?></p>
		<?php
	})->send();
	
	(new Sendmail($email))->setTheme("Регистрация на Конференцию тюремного служения")->data(function(){
		?>
		<p>Поздравляем!<br />Вы успешно зарегистрированы на <a href='<?=__ROOT__?>'>Конференцию тюремного служения</a></p>
		<p>Мы ждем вас 20 сентября по адресу:<br />г. Новосибирск, ул. Оловозаводская, 1, церковь «Назарет».</p>
		<p>Начало конференции в 10:00.</p>
		<p>Дополнительная информация по телефону:<br /><a href='tel:+7 903 049-60-12 '>+7 903 049-60-12</a> (Леонид).</p>
		<?php
	})->send();
}

/*Find or create request*/
if(isset($_GET["request"])){
	$request = $db->query("SELECT * FROM `request` WHERE `hash` = '".$db->real_escape_string($_GET["request"])."'");
}
else/*if(isset($_GET["paymentId"])){
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
	
	paied($request["email"], $request["data"], "Произведена оплата в размере <b>{$payment->amount} руб.</b>");
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
		paied($request["email"], $request["data"], "Произведена оплата в размере <b>{$_POST["withdraw_amount"]}</b> руб.");
	}
	die();
}
else*/{
	$info = [];
	if(!$_POST["name"] || !$_POST["phone"] || !$_POST["email"] || !$_POST["Город"]){
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
		//if(time() < 1472576399 && $_POST["payment"] == 500) $price = 500;
		//elseif(time() < 1474390799 && $_POST["payment"] == 600) $price = 600;
		//else{
		if (time() < strtotime('10.09.2017')) $price = 1800;
		else $price = 2000;
		paied($_POST["email"], $info, "Оплата на месте в размере <b>$price руб.</b>");
		//}
		
		$info = $db->real_escape_string($info);
		$email = $db->real_escape_string($_POST["email"]);
		$method = $db->real_escape_string($_POST["pay_method"]);
		
		$db->query("INSERT INTO `request` (`email`, `data`, `payment`, `hash`) VALUES ('$email', '$info', '$method-$price', '$hash')") or die($db->error);
		$request = $db->insert_id;
		$request = $db->query("SELECT * FROM `request` WHERE `id` = $request")->fetch_assoc();
		
		(new Sendmail($request["email"]))->setTheme("Всёвозможно - Начало регистрации")->data(function() use($request){
			?>
			<p>Вы начали регистрацию на <a href='<?=__ROOT__?>'>Конференцию тюремного служения</a>.</p>
			<p>Информация о вашей заявке доступна на странице: <a href='<?=__ROOT__?>/request.php?request=<?=$request["hash"]?>'><?=__ROOT__?>/request.php?request=<?=$request["hash"]?></a></p>
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
				<input type="hidden" name="successURL" value="<?=__ROOT__?>/requests.php"> 
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
		$_SESSION["requests"][] =  $request["id"];
		header("Location: /requests.php");
		die();
	}
} else {
	template_top();
	echo "<div class='wrapper'><div>Заявка не найдена.</div></div>";
	template_bottom();
}
