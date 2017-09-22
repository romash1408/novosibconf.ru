<?php

require_once("vendor/autoload.php");

session_start();

if (!isset($_SESSION["requests"]))
{
	$_SESSION["requests"] = [];
}

$_SCRIPTS = array();

define("CONF", include("config.php"));
define("INFO", CONF["project_info"]);

$protocol = (
	isset($_SERVER["HTTP_REFERER"]) ?
	explode(":", $_SERVER["HTTP_REFERER"], 2)[0] :
	"http"
);
define('__ROOT__', "$protocol://$_SERVER[HTTP_HOST]");

function template_top()
{

	$phone = INFO["phones"];
	$phone = array_shift($phone);

	?>
	<!DOCTYPE html>
	<html>
		<head>
			<meta charset='UTF-8' />
			<meta name='viewport' content='width=device-width' />
			<title><?=INFO["title"]?></title>
			<link async href="/favicon.ico" rel="shortcut icon" />
			<link async type="text/css" rel='stylesheet' href='/fonts/fonts.css' />
			<link async type="text/css" rel='stylesheet' href='/css/bootstrap.css' />
			<link async type="text/css" rel='stylesheet' href='/css/main.css' />
		</head>
		<body class='mobile'>
			<header class='wrapper'>
				<div>
					<a class='mainlogo' href='/#'>
						<?=file_get_contents("img/logo.svg")?>
					</a>
					<div class='mainmenu'>
						<a href='/##about' data-anchor='#about'><span>О конференции</span></a>
						<a href='/##speakers' data-anchor='#speakers'><span>Спикеры</span></a>
						<a href='/##data' data-anchor='#data'><span>Расписание</span></a>
						<a href='/##registration' data-anchor='#registration'><span>Регистрация</span></a>
					</div>
					<a class='phone' href='tel:<?=$phone?>'><?=$phone?></a>
				</div>
			</header>
	<?php
}

function template_bottom(){

	global $_SCRIPTS;
	$phone = INFO["phones"];
	$phone = array_shift($phone);

	?>
			<footer class='wrapper'>
				<div>
					<a href='/'><?=file_get_contents("img/logo.svg")?></a>
					<div style='float: right'>
						<a class='phone' href='tel:<?=$phone?>'><?=$phone?></a>
					</div>
					<div><small><?=INFO["footer_info"]?></small></div>
				</div>
			</footer>
			<script src='/js/jquery-2_1_1_min.js'></script>
			<script src='/js/anchor.js'></script>
			<script>
			<?php
			foreach($_SCRIPTS as $script){
				switch(gettype($script)){
					case "string": echo $script; break;
					case "object": echo "</script>"; $script(); echo "<script>"; break;
					case "array": echo 
					"</script>
					<script " .
						implode(" ", array_map(function ($attr, $value)
						{
							return $attr . ($value != "" ? "='$value'" : "");
						}, array_keys($script), $script)) . 
					"> <script>";
				}
			}
			?>
			</script>
		</body>
	</html>
	<?php
}

function script($script){
	global $_SCRIPTS;
	$_SCRIPTS[] = $script;
}

function database(){
	static $db = NULL;
	if(!$db){
		$db = mysqli_connect(CONF["db"]["server"], CONF["db"]["user"], CONF["db"]["password"], CONF["db"]["db"]) or die("Coudn't connect to database");
	}
	return $db;
}

function m_init_phpmailer($config = null)
{
	if ($config === null)
	{
		$config = CONF["sendmail"];
	}

	$config = array_merge([
		"type" => "mail",
		"charset" => "UTF-8",
		"html" => false,
		"debug" => false,
		"from" => "system@$_SERVER[HTTP_HOST]",
	], $config);

	$mailer = new \PHPMailer\PHPMailer\PHPMailer($config["debug"]);
	$mailer->isHTML($config["html"]);
	$mailer->setFrom($config["from"]);
	$mailer->setLanguage("ru");
	$mailer->Charset = $config["charset"];

	switch ($config["type"])
	{
	case "mail":
		$mailer->isMail();
		break;

	case "smtp":
		$config = array_merge([
			"port" => 21,
			"secure" => '',
		], $config);

		$mailer->isSMTP();
		$mailer->Host = $config["host"];
		$mailer->Port = $config["port"];
		$mailer->SMTPSecure = $config["secure"];

		if (isset($config["user"]) && isset($config["password"]))
		{
			$mailer->SMTPAuth = true;
			$mailer->Username = $config["user"];
			$mailer->Password = $config["password"];

		} else {

			$mailer->SMTPAuth = false;
		}
		break;
	}

	return $mailer;
}

?>