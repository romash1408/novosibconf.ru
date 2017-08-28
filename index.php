<?php
require_once("main.php");
template_top();
?>
<div id='start' class='wrapper'>
	<div>
		<div class='timer'>До начала онлайн-трансляции:<br /><span class='days'></span> дн. <span class='hours'></span> ч. <span class='minutes'></span> мин. <span class='seconds'></span> сек.</div>
		<?php
		script(function(){
			?>
			<script>
			$(function(){
					(function(){//Timer
						var days = $(".timer .days"),
							hours = $(".timer .hours"),
							minutes = $(".timer .minutes"),
							seconds = $(".timer .seconds"),
							showing = false;
						function timer(){
							var lastSeconds = Math.floor((Date.parse("2016-09-23T09:00:00.000+07:00") - (new Date()).getTime())/1000);
							if(lastSeconds <= 3600 && !showing){
								$("#translation div").append('<iframe src="https://www.youtube.com/embed/t9dPQCM2DQY" frameborder="0" allowfullscreen="allowfullscreen" id="fitvid630098" style="position: absolute; width: 100%; height: 100%;"></iframe>').animate(null, 5000).animate({paddingBottom: "56.25%"}, 3000);
								showing = true;
							}
							if(lastSeconds <= 0){
								$(".timer").html("");
								return false;
							}
							
							var lastMinutes = Math.floor(lastSeconds/60); seconds.html(lastSeconds - lastMinutes * 60);
							var lastHours = Math.floor(lastMinutes/60); minutes.html(lastMinutes - lastHours * 60);
							var lastDays = Math.floor(lastHours/24); hours.html(lastHours - lastDays * 24);
							days.html(lastDays);
							setTimeout(timer, 1000);
						}
						timer();
					})();//Timer
				});
			</script>
			<?php
		});
		?>
		<h1>23&nbsp;<span class='vertical'>сентября</span>&nbsp;24</h1>
		<h1>Не удерживай</h1>
		<h1>свою НАДЕЖДУ</h1>
		<p>присоединяйся к общероссийской конференции коалиции реабилитационных центров “Вызов”</p>
		<a href='/registration.php' class='button'>Зарегистрироваться</a>
	</div>
</div>
<div id='translation' class='wrapper'>
	<div style='position: relative; height: 0; padding-bottom: 0;'> </div>
</div>
<div id='about' class='wrapper'>
	<div>
		<h2 id='aboutAnchor'>Что это?</h2>
		<p>Всё возможно - это не просто конференция. Это доказательство того, что нет ничего невозможного для Бога.</p>
		<p>Это время, когда в одном месте соберутся единомышленники для планирования развития и созидания.</p>
		<p>Это место, где устанавливаются новые цели и рождаются мечты. Место где ты сможешь получить вдохновение для новых невозможных свершений.</p>
	</div>
</div>
<div id='speakers' class='wrapper'>
	<div>
		<h2 id='speakersAnchor'>Спикеры</h2>
		<speaker><img src='/images/speaker01.jpg' alt='speaker1' /><h5>Джерри Нэнс</h5><p>Президент Глобал Тин Челлендж, исполнительный директор Тин Челлендж Юго-Восточного Региона</p></speaker>
		<speaker><img src='/images/speaker02.jpg' alt='speaker2' /><h5>Кевин Тайлер</h5><p>Главный операционный директор Глобал Тин Челлендж</p></speaker>
		<speaker><img src='/images/speaker03.jpg' alt='speaker3' /><h5>Фил Хиллз</h5><p>Вице-президент, член совета директоров Глобал Тин Челлендж, исполнительный директор Тин Челлендж Великобритания</p></speaker>
		<speaker><img src='/images/speaker04.jpg' alt='speaker4' /><h5>Илья Банцеев</h5><p>Директор Тин Челлендж Евразийского региона</p></speaker>
		<speaker><img src='/images/speaker05.jpg' alt='speaker5' /><h5>Эдуард Грабовенко</h5><p>Начальствующий епископ РЦ ХВЕ</p></speaker>
		<speaker><img src='/images/speaker06.jpg' alt='speaker6' /><h5>Андрей Панасовец</h5><p>Заместитель начальствующего епископа РЦ ХВЕ в Сибирском федеральном округе, епископ</p></speaker>
		<speaker><img src='/images/speaker07.jpg' alt='speaker7' /><h5>Сергей Горбенко</h5><p>Епископ, старший пресвитер Омского объединения РЦ ХВЕ</p></speaker>
		<speaker><img src='/images/speaker08.jpg' alt='speaker8' /><h5>Василий Евчик</h5><p>Заместитель начальствующего епископа РЦ ХВЕ, руководитель социального отдела</p></speaker>
	</div>
</div>
<div id='speakers2' class='wrapper'>
	<div>
		<h2>ВЕДУЩИЕ МАСТЕР-КЛАССОВ</h2>
		<speaker><img src='/images/speaker09.jpg' alt='speaker9' /><h5>Дэвид Бэтти</h5><p>Уполномоченный миссионер Тин Челлендж, автор-составитель Групповых занятий для новообращённых христиан</p></speaker>
		<speaker><img src='/images/speaker10.jpg' alt='speaker10' /><h5>Евгений и Елена Кулаговы</h5><p>Евгений – член совета Директоров Коалиции «Вызов», региональный представитель по Западной Сибири. Елена – координатор служения «Свободная жизнь» в России.</p></speaker>
		<speaker><img src='/images/speaker11.jpg' alt='speaker11' /><h5>Алексей Ившин</h5><p>Руководитель служения реабилитации РЦ ХВЕ</p></speaker>
	</div>
</div>
<div id='data' class='wrapper'>
	<div>
		<h2 id='infoAnchor'>Когда и где</h2>
		<div class='info'><label>Когда:</label><span>23 - 24  сентября</span></div>
		<div class='info'><label>Старт:</label><span>Пятница, 23 сентября, 09:00</span></div>
		<div class='info'><label>Где:</label><span>Новокузнецк, ул. Орджоникидзе, 35 к. 2, церковь “Новоильинская”</span></div>
		<div class='info'><label>Финиш:</label><span>Суббота, 24 сентября, 16:30</span></div>
		
		<h2 id='registrationAnchor' style='padding-top: 90px; transform: translateY(-60px); -webkit-transform: translateY(-60px);'>Регистрационный взнос</h2>
		<div class='payment active'><label>500 .-</label><span>при оплате до 30.08</span></div>
		<div class='payment'><label>600 .-</label><span>при оплате до 20.09</span></div>
		<div class='payment'><label>700 .-</label><span>при оплате на месте</span></div>
		<a href='/registration.php' class='button'>Зарегистрироваться</a>
	</div>
</div>
<div id='map'>
	<script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?sid=4zLKpjYraloUPWVGCxd4wuDkL-qXXec4&amp;width=100%&amp;height=353&amp;lang=ru_RU&amp;sourceType=constructor&amp;scroll=true"></script>
</div>

<?php
template_bottom();
?>