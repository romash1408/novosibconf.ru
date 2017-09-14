<?php
require_once("../main.php");
template_top();
?>
<div id='start' class='wrapper'>
	<div>
		<h1>20&nbsp;&nbsp;<span class='vertical'>сентября</span>&nbsp;&nbsp;21</h1>
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
			<speaker>
				<img src='/img/speakers/speaker01.jpg' alt='speaker01' />
				<h5>Старкова Марина</h5>
				<p>Член Общественного Совета при УФСИН России по Тюменскому краю. Религовед.</p>
			</speaker>
			<speaker>
				<img src='/img/speakers/speaker02.jpg' alt='speaker02' />
				<h5>Мкртумян Армен</h5>
				<p>Председатель Общественного совета при УФСИН России по Тюменскому краю. Пастор</p>
			</speaker>
			<speaker>
				<img src='/img/speakers/speaker03.jpg' alt='speaker03' />
				<h5>Каргапольцева Анна</h5>
				<p>Президент ПРООСПП "Выбор". Заместитель председателя ОНК по Пермскому краю.</p>
			</speaker>
			<speaker>
				<img src='/img/speakers/speaker04.jpg' alt='speaker04' />
				<h5>Харив Сергей</h5>
				<p>Руководитель Российского тюремного служения РОСХВЕ. Религовед. Москва.</p>
			</speaker>
		</div>
		<div class='clear'>
			<speaker>
				<img src='/img/speakers/speaker05.jpg' alt='speaker05' />
				<h5>Семиколенов Леонид</h5>
				<p>Пастор тюремного служения. Новосибирск</p>
			</speaker>
			<speaker>
				<img src='/img/speakers/speaker07.jpg' alt='speaker07' />
				<h5>Уманский Игорь</h5>
				<p>Пастор. Руководитель Тюремного служения. Ачинск.</p>
			</speaker>
			<speaker>
				<img src='/img/speakers/speaker08.jpg' alt='speaker08' />
				<h5>Зырянов Михаил</h5>
				<p>Пастор. Красноярск</p>
			</speaker>
		</div>
	</div>
</div>

<div id='data' class='wrapper'>
	<div>
		<div><h2 id='infoAnchor'>Когда и где</h2></div>
		<div class='info'><label>Когда:</label><span>20 - 21  сентября</span></div>
		<div class='info'><label>Старт:</label><span>20 сентября, 10:00 - 19:00</span></div>
		<div class='info'><label>Где:</label><span>г.&nbsp;Новосибирск, ул.&nbsp;Оловозаводская, 1</span></div>
		<div class='info'><label>Финиш:</label><span>21 сентября, 09:00 - 17:00</span></div>
	</div>
</div>

<div id='registration' class='wrapper'>
	<div>
		<div><h2 id='registrationAnchor'>Добровольное пожертвование</h2></div>
		<div class='payment'><label>1800 .-</label><span>При регистрации до 10 сентября</span></div>
		<div class='payment'><label>2000 .-</label><span>При регистрации после 10 сентября</span></div>
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