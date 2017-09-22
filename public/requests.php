<?php
require_once("../main.php");

$db = database();
template_top();

?>
<div id='requests' class='wrapper'>
	<div>
		<div>
			<h2>Ваши заявки</h2>
		</div>

		<?php
		if (isset($_GET["administrat"]))
		{
			if ($_GET["administrat"] != CONF["password"])
			{
				die("Wrong password (should be like: <code>?administrat=&lt;password&gt;</code>). Access denied.");
			}

			$requests = [];
			$req = $db->query("SELECT `id` FROM `request` ORDER BY `id` ASC");
			while ($next = $req->fetch_assoc())
			{
				$requests[] = $next["id"];
			}

		} else {

			$requests = $_SESSION["requests"];
		}

		$workedRequests = [];

		for ($i = count($requests) - 1; $i >= 0; --$i)
		{
			if (!array_key_exists($i, $requests)) continue;

			if (array_key_exists($requests[$i], $workedRequests))
			{
				if (!isset($_GET["administrat"]))
				{
					unset($_SESSION["requests"][$i]);
				}
				continue;
			}
			$workedRequests[$requests[$i]] = true;

			$request = $db->query("SELECT * FROM `request` WHERE `id` = {$requests[$i]}");
			if (!$request) continue;

			$request = $request->fetch_assoc();
			$found = true;
			echo "<div style='margin-bottom: 30px;'>" .
				"<h3>Заявка №$request[id]</h3>" .
				$request['data'] .
				"<div style='text-align: right'>";


			preg_match("/^([a-z]+)-(-?[0-9]+)/", $request["payment"], $matches);
			// There was a code checking paypal and card payments before.
			// For now only 'place' payment is using.
			
			echo "Оплата $matches[2] р. (на месте)";
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