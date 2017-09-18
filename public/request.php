<?php
require_once("../main.php");

$db = database();

require_once("../Sendmail.php");

function paied($email, $data, $payment){
	(new Sendmail(<?=INFO["mail_getters"])) -> setTheme("Регистрация novosibconf.ru | {INFO['title']}") -> data( function() use($data, $payment)
	{
		?>
		<h2>Заявка с сайта <a href='<?=__ROOT__?>'><?=__ROOT__?></a> (<?=INFO['title']?>)</h2>
		<h3>Данные пользователя:</h3>
		<?=$data?>
		<p><?=$payment?></p>
		<?php
	})->send();
	
	(new Sendmail($email)) -> setTheme(<?=INFO['title']?>) -> data( function()
	{
		?>
		<p>Поздравляем!<br />Вы успешно зарегистрированы на <a href='<?=__ROOT__?>'><?=__ROOT__?></a> (<?=INFO['title']?>)</a></p>
		<p>Мы ждем вас <?=INFO["start"]["day"]?> <?=INFO["start"]["month"]?> по адресу:<br /><?=INFO["address"]?>.</p>
		<p>Начало конференции в <?=explode(' ', INFO["start"]["time"], 2)[0]?>.</p>
		<p>
			Дополнительная информация по телефону:<br />
			<?php
			foreach (INFO["phones"] as $name => $phone)
			{
				echo "<a href='tel:$phone'>$phone</a> ($name).";
			}
			?>
		</p>
		<?php
	})->send();
}

/*Find or create request*/
if(isset($_GET["request"])){
	$request = $db->query("SELECT * FROM `request` WHERE `hash` = '".$db->real_escape_string($_GET["request"])."'");
} else {
	$info = [];
	if (!$_POST["name"] || !$_POST["phone"] || !$_POST["email"] || !$_POST["Город"])
	{
		die("Не указано одно из обязательных полей <script>history.back();</script>");
	}

	foreach ($_POST as $field => $value)
	{
		if ($value == "") continue;
		if ($field == "name") $field = "ФИО"; else
		if ($field == "phone") $field = "Контактный_телефон"; else
		if ($field == "email") $field = "Электронная_почта"; else
		if ($field == "payment") continue;
		
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
		
	if (!($request = $db->query("SELECT * FROM `request` WHERE `hash`='$hash'")->fetch_assoc()))
	{
		$price = -1;
		foreach (INFO["prices"] as $time => $price)
		{
			if (time() < strtotime($time)) {
				$price = 1800;
				break;
			}
		}

		if ($price == -1)
		{
			die("Регистрация закрыта");
		}
		
		$info = $db->real_escape_string($info);
		$email = $db->real_escape_string($_POST["email"]);
		$method = $db->real_escape_string("place");
		
		$db->query("INSERT INTO `request` (`email`, `data`, `payment`, `hash`) VALUES ('$email', '$info', '$method-$price', '$hash')") or die($db->error);
		$request = $db->insert_id;
		$request = $db->query("SELECT * FROM `request` WHERE `id` = $request")->fetch_assoc();
		
		(new Sendmail($request["email"])) -> setTheme("Регистрация novosibconf.ru | {INFO['title']}")->data(function() use($request){
			?>
			<p>Вы начали регистрацию на <a href='<?=__ROOT__?>'><?=__ROOT__?></a> (<?=INFO['title']?>).</p>
			<p>Информация о вашей заявке доступна на странице: <a href='<?=__ROOT__?>/request.php?request=<?=$request["hash"]?>'><?=__ROOT__?>/request.php?request=<?=$request["hash"]?></a></p>
			<?php
		})->send();

		paied($request["email"], $info, "Оплата на месте в размере <b>$price руб.</b>");
	}
}

/*What to do with request*/
if (is_array($request) || ($request = $request->fetch_assoc()))
{
	$_SESSION["requests"][] =  $request["id"];
	header("Location: /requests.php");
} else {
	template_top();
	echo "<div class='wrapper'><div>Заявка не найдена.</div></div>";
	template_bottom();
}
