<?php
session_start();
$_SCRIPTS = array();

function template_top(){
	?>
	<!DOCTYPE html>
	<html>
		<head>
			<meta charset='UTF-8' />
			<meta name='viewport' content='width=device-width' />
			<title></title>
			<link rel='stylesheet' href='/fonts/fonts.css' />
			<link rel='stylesheet' href='/css/bootstrap.css' />
			<link rel='stylesheet' href='/css/main.css' />
		</head>
		<body class='mobile'>
			<header class='wrapper'>
				<div>
					<a class='mainlogo' href='/#'><img src='/images/logo.png' alt='logo' /></a>
					<div class='mainmenu'>
						<a href='/#aboutAnchor'>О конференции</a>
						<a href='/#speakersAnchor'>Спикеры</a>
						<a href='/#infoAnchor'>Расписание</a>
						<a href='/#registrationAnchor'>Регистрация</a>
					</div>
					<a class='phone' href='tel:89133127056'>8 913 312-70-56</a>
					<a class='phone' href='tel:89609234477'>8 960 923-44-77</a>
				</div>
			</header>
	<?php
}

function template_bottom(){
	global $_SCRIPTS;
	?>
			<footer class='wrapper'>
				<div>
					<img src='/images/footer1.png' alt='footer1' style='float: left; height: 51px;' />
					<img src='/images/footer6.png' alt='footer6' style='float: right; height: 51px;'/>
					<img src='/images/footer5.png' alt='footer5' style='float: right; height: 51px;'/>
					<img src='/images/footer4.png' alt='footer4' style='float: right; height: 51px;'/>
					<img src='/images/footer3.png' alt='footer3' style='float: right; height: 51px;'/>
					<img src='/images/footer2.png' alt='footer2' style='float: right; height: 51px;'/>
				</div>
			</footer>
			<script src='/js/jquery-2_1_1_min.js'> </script>
			<script>
			if (!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
				document.body.classList.remove('mobile');
			}
			<?php
			foreach($_SCRIPTS as $script){
				switch(gettype($script)){
					case "string": echo $script; break;
					case "object": echo "</script>"; $script(); echo "<script>"; break;
					case "array": echo 
					"</script>
						<script ".implode(" ", array_map(function($attr, $value){return $attr . ($value != "" ? "='$value'" : "");}, array_keys($script), $script)).">
					<script>";
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
		$db = mysqli_connect("localhost", "root", "", "novosibconf") or die("Coudn't connect to database");
	}
	return $db;
}

function initPaypal(){
	Paypal::init([
		"sandbox" => "",
        "sandbox.id" => "AR-UokfzWVVHCFf90vbpYZ4gPBaEiuykHPbQleWeR66du0vGwoPVvybyi69zP7RXF_ZGAlR6wx8Wv3-N",
        "sandbox.secret" => "EAgDl3Si_irsupdWLEomvLFDw7BARdToZiCVFJLAl0p_MiFFqaznZpFpY4XqzQM-M_i8n_-Sl-g4ZH6S",
		"id" => "ASb92v9U7d6s7t67Hajxx-2cQsxiVvL02njBGXhJtgdgCxVCooWWyDEr3s-R36cW2QAHo8ENRU1kFfU8",
		"secret" => "EIQbFet7a24aaPmo8_TeWzLeaYg3Yp3J1hUHWv8fLHR0MedH18fYgkMghc4xY91VQ8Uu3v6VW-5zNeBg"
	]);
}
?>