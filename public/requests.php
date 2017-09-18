<?php
require_once("../main.php");
require_once("../Sendmail.php");

$db = database();
template_top();

?>
<div id='requests' class='wrapper'>
	<div>
		<div>
			<h2>Ваши заявки</h2>
		</div>

		<?php
		if (isset($_GET["administrat"]) && $_GET["administrat"] == CONF["password"])
		{
			$requests = [];
			$req = $db->query("SELECT `id` FROM `request` ORDER BY `id` ASC");
			while ($next = $req->fetch_assoc())
			{
				$requests[] = $next["id"];
			}
		}
		else $requests = $_SESSION["requests"];

		for ($i = count($requests) - 1; $i >= 0; --$i) {
			$request = $db->query("SELECT * FROM `request` WHERE `id` = {$requests[$i]}");
			if (!$request) continue;

			$request = $request->fetch_assoc();
			$found = true;
			echo "<div style='margin-bottom: 30px;'>" .
				"<h3>Заявка №$request[id]</h3>" .
				$request['data'] .
				"<div style='text-align: right'>";
			
			echo "Оплата $matches[2] р. (на месте)";
			if (isset($_POST["sendmail"]))
			{
				(new Sendmail("spam@romash1408.ru"))->setTheme("Зарегестрирован пользователь")->data(function() use($request, $matches){
					?>
					<h2>Новый пользователь зарегистрирован на <a href='<?=__ROOT__?>/'>Конференцию тюремного служения</a></h2>
					<h3>Данные пользователя:</h3>
					<?=$request["data"]?>
					<p><?="Оплата на месте в размере <b>$matches[2] руб.</b>"?></p>
					<?php
				})->send();
			}
			
			echo "</div>" .
			"</div>";
		}

		if (!$found)
		{
			echo "Вы ещё не подали заявок.";
		}
		?>
	</div>
</div>
<?php
template_bottom();
?>