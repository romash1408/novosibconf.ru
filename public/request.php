<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("../main.php");

$db = database();

$Sendmail = m_init_phpmailer();
$__ROOT__ = __ROOT__;
$INFO = INFO;

function paied($email, $data, $payment)
{
	global $Sendmail;
	global $__ROOT__;
	global $INFO;

	foreach (CONF["mail_getters"][PROJECT_NAME] as $getter)
	{
		$Sendmail->addAddress($getter);
	}
	$Sendmail->Subject = "Заявка с novosibconf.ru | " . INFO['title'];
	$Sendmail->msgHTML(<<<HTML
<h2>Заявка с сайта <a href='{$__ROOT__}'>{$__ROOT__}</a> ({$INFO['title']})</h2>
<h3>Данные пользователя:</h3>
{$data}
<p>{$payment}</p>
HTML
	,  __DIR__);
	$Sendmail->send();
	$Sendmail->clearAddresses();
	

	$startTime = explode(' ', INFO["start"]["time"], 2)[0];
	$phones = implode("<br />", array_map(function($name, $phone){
		return "<a href='tel:$phone'>$phone</a> ($name).";
	}, array_keys(INFO["phones"]), INFO["phones"]));

	$Sendmail->addAddress($email);
	$Sendmail->Subject = "Регистрация на novosibconf.ru | " . INFO['title'];
	$Sendmail->msgHTML(<<<HTML
<p>Поздравляем!<br />Вы успешно зарегистрированы на <a href='{$__ROOT__}'>{$__ROOT__}</a> ({$INFO['title']})</a></p>
<p>Мы ждем вас {$INFO["start"]["day"]} {$INFO["start"]["month"]} по адресу:<br />{$INFO["address"]}.</p>
<p>Начало конференции в {$startTime}.</p>
<p>
	Дополнительная информация по телефону:<br />
	{$phones}
</p>
HTML
	, __DIR__);
	$Sendmail->send();
	$Sendmail->clearAddresses();
}

/* Find or create request */
if (isset($_GET["request"]))
{
	$request = $db->query("SELECT * FROM `request` WHERE `hash` = '".$db->real_escape_string($_GET[request])."'");
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
		foreach (INFO["prices"] as $time => $priceInfo)
		{
			if (time() < strtotime($time)) {
				$price = $priceInfo["amount"];
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

		$Sendmail->addAddress($request["email"]);
		$Sendmail->Subject = "Регистрация на novosibconf.ru | " . INFO['title'];
		$Sendmail->msgHTML(<<<HTML
<p>Вы начали регистрацию на <a href='{$__ROOT__}'>{$__ROOT__}</a> ({$INFO['title']}).</p>
<p>Информация о вашей заявке доступна на странице: <a href='{$__ROOT__}/request.php?request={$request["hash"]}'>{$__ROOT__}/request.php?request={$request["hash"]}</a></p>
HTML
		, __DIR__);
		$Sendmail->send();
		$Sendmail->clearAddresses();

		if ($method == "place")
		{
			paied($request["email"], $info, "Оплата на месте в размере <b>$price руб.</b>");
		}
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
