<?php
require_once("../main.php");
template_top();
?>

<div id='start' class='wrapper'>
	<div>
		<h1><?=INFO["start"]["day"]?>&nbsp;&nbsp;<span class='vertical'><?=INFO["start"]["month"]?></span>&nbsp;&nbsp;<?=INFO["finish"]["day"]?></h1>
		<p class='txt01'>ОБЩЕРОССИЙСКАЯ КОНФЕРЕНЦИЯ<br />ТЮРЕМНОГО СЛУЖЕНИЯ</p>
		<p class='txt02'>ПРАКТИЧЕСКАЯ РАБОТА<br />В ИСПРАВИТЕЛЬНЫХ КОЛОНИЯХ.<br />ПРАКТИЧЕСКАЯ ДЕЯТЕЛЬНОСТЬ<br />И РАБОТА С ФСИН.</p>
		<a href='/registration.php' class='btn01'>Зарегистрироваться</a>
	</div>
</div>
<div id='translation' class='wrapper'>
	<div style='position: relative; height: 0; padding-bottom: 0;'> </div>
</div>
<div id='about' class='wrapper'>
	<div>
		<div><h2 id='aboutAnchor'>О конференции</h2></div>
		<p>20 и 21 сентября тюремные служители России и ближнего зарубежья соберутся вместе, чтобы делиться опытом, вдохновлять и ободрять друг друга.</p>
		<p>В этом году мы уделим большое количестов времени для практических рекомендаций по организации и улучшению тюремного служения.</p>
	</div>
</div>

<div id='speakers' class='wrapper'>
	<div>
		<div>
			<h2 id='speakersAnchor'>Спикеры</h2>
		</div>
		<div>
			<?php
			foreach (INFO["speakers"] as $i => $speaker)
			{
				$id = sprintf("%02d", $i);
				?>
				<speaker>
					<img src='/img/speakers/<?=PROJECT_NAME?>/speaker<?=$id?>.jpg' alt='speaker<?=$id?>' />
					<h5><?=$speaker["name"]?></h5>
					<p><?=$speaker["description"]?></p>
				</speaker>
				<?php
			}
			?>
		</div>
	</div>
</div>

<div id='data' class='wrapper'>
	<div>
		<div><h2 id='infoAnchor'>Когда и где</h2></div>
		<div class='info'>
			<label>Когда:</label>
			<span>
				<?=INFO["start"]["day"]?>
				<?(INFO["start"]["month"] != INFO["finish"]["month"] ? INFO["start"]["month"] : "")?>-
				<?=INFO["finish"]["day"]?>
				<?=INFO["finish"]["month"]?>
			</span>
		</div>
		<div class='info'>
			<label>Старт:</label>
			<span>
				<?=INFO["start"]["day"]?>
				<?=INFO["start"]["month"]?>,
				<?=INFO["start"]["time"]?>
			</span>
		</div>
		<div class='info'>
			<label>Где:</label>
			<span><?=INFO["address"]?></span>
		</div>
		<div class='info'>
			<label>Финиш:</label>
			<span>
				<?=INFO["finish"]["day"]?>
				<?=INFO["finish"]["month"]?>,
				<?=INFO["finish"]["time"]?>
			</span>
		</div>
	</div>
</div>

<div id='registration' class='wrapper'>
	<div>
		<div><h2 id='registrationAnchor'>Добровольное пожертвование</h2></div>
		<?php
		foreach (INFO["prices"] as $price)
		{
			echo "<div class='payment'><label>$price[amount] .-</label><span>$price[label]</span></div>";
		}
		?>
		<div class='payment'><p>Добровольное пожертвование вносится на организацию конференции. На конференции вам будет предоставлено питание и кофе-брэйки. Добровольные пожертвования вносятся по месту проведения.</p></div>
		<div class='clear'> </div>
		<div class='text-center'>
			<a href='/registration.php' class='btn01'>Зарегистрироваться</a>
		</div>
	</div>
</div>

<div id='map'>
	<script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?sid=AkGQ8unmSxOUbLyLKkIeMLjJtWqKX1cs&amp;width=100%&amp;height=353&amp;lang=ru_RU&amp;sourceType=constructor&amp;scroll=true"></script>
</div>

<?php
template_bottom();
?>