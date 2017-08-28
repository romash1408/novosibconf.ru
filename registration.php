<?php
require_once("main.php");
template_top();
?>
<div id='registration' class='wrapper'>
	<div>
		<h2>Регистрация</h2>
		<form action='request.php' method='POST' class='form-horizontal'>
			<div class='form-group'>
				<label class='col-xs-4 control-label' for='name'>ФИО<span style='color:#B44'>*</span>:</label>
				<div class='col-xs-8'>
					<input name='name' placeholder='Фамилия* Имя* Отчество' class='form-control' required />
				</div>
			</div>
			<div class='form-group'>
				<label class='col-xs-4 control-label' for='phone'>Контактный телефон<span style='color:#B44'>*</span>:</label>
				<div class='col-xs-8'>
					<input name='phone' type='tel' placeholder='+7 (123) 456-78-90' class='form-control' required />
				</div>
			</div>
			<div class='form-group'>
				<label class='col-xs-4 control-label' for='email'>Электронная почта<span style='color:#B44'>*</span>:</label>
				<div class='col-xs-8'>
					<input name='email' type='email' placeholder='e@mail.ru' class='form-control' required />
				</div>
			</div>
			<div class='form-group'>
				<label class='col-xs-4 control-label' for='Город'>Город<span style='color:#B44'>*</span>:</label>
				<div class='col-xs-8'>
					<input name='Город' placeholder='Новокузнецк' class='form-control' required />
				</div>
			</div>
			<div class='form-group'>
				<label class='col-xs-4 control-label' for='Церковь'>Ваша церковь<span style='color:#B44'>*</span>:</label>
				<div class='col-xs-8'>
					<input name='Церковь' placeholder='Название церкви' class='form-control' required />
				</div>
			</div>
			<div class='form-group'>
				<label class='col-xs-4 control-label' for='Организация'>Название реабилитационного центра или организации:</label>
				<div class='col-xs-8'>
					<input name='Организация' placeholder='например, Благотворительный фонд социальный реабилитации граждан «Источник Жизни»' class='form-control' />
				</div>
			</div>
			<div class='form-group'>
				<label class='col-xs-4 control-label' for='Ваша_должность_в_реабцентре'>Ваша должность<span style='color:#B44'>*</span>:</label>
				<div class='col-xs-8'>
					<select name='Должность' class='form-control  selectInputWithAnother'>
						<option>Директор</option>
						<option>Работник</option>
						<option>Волонтёр</option>
						<option>Другое</option>
					</select>
				</div>
				<div class="col-xs-8" style='float: right; margin-top: 10px;' >
					<input type='text' id='ДолжностьText' name='Должность' class='form-control'  placeholder='Ваша должность в организации' />
				</div>
			</div>
			<div class='form-group'>
				<label class='col-xs-4 control-label' for='Узнали_о_конференции'>Как вы узнали о Конференции?<span style='color:#B44'>*</span>:</label>
				<div class='col-xs-8'>
					<select name='Узнали_о_конференции' class='form-control selectInputWithAnother'>
						<option>Из церкви</option>
						<option>От коллег</option>
						<option>От друзей</option>
						<option>Из интернета</option>
						<option>Другое</option>
					</select>
				</div>
				<div class="col-xs-8" style='float: right; margin-top: 10px;' >
					<input type='text' id='Узнали_о_конференцииText' name='Узнали_о_конференции' class='form-control'  placeholder='Как вы узнали о Конференции?' />
				</div>
			</div>
			<div class='form-group'>
				<div class='col-xs-offset-4 col-xs-8'>
					<div class='checkbox'>
						<label>
							<input name='Необходимо_расселение' type='checkbox' value='1'/> Необходимо ли вам расселение?
						</label>
					</div>
				</div>
			</div>
			<div class='form-group'>
				<div class='col-xs-offset-4 col-xs-8'>
					<div class='checkbox'>
						<label>
							<input name='Необходимы_диски_с_виедо' type='checkbox' value='1' /> Хотели бы вы приобрести диски с видео сразу после конференции?
						</label>
					</div>
				</div>	
			</div>
			<div class='form-group'>
				<label class='col-xs-4 control-label' for='Дата_приезда'>Дата вашего приезда:</label>
				<div class='col-xs-8'>
					<input name='Дата_приезда' type='datetime-local' value='2016-09-23T08:00:00' class='form-control' />
				</div>
			</div>
			<div class='form-group'>
				<div class='col-xs-offset-4 col-xs-8'>
					<div class='checkbox'>
						<label>
							<input name='С детьми' type='checkbox' value='1' />Будут ли с вами дети?
						</label>
					</div>
				</div>	
			</div>
			<div class='form-group'>
				<label class='col-xs-4 control-label'>Оплата:</label>
				<div class='col-xs-8'>
					<select name='payment' class='form-control'>
						<option value="500" <?=(time() > 1472576399 ? "disabled" : "")?>>500 рублей (он-лайн)</option>
						<option value="600" <?=((time() < 1472576399) || (time() > 1474390799) ? "disabled" : "")?>>600 рублей (он-лайн)</option>
						<option value="700">700 рублей (на месте)</option>
					</select>
				</div>
			</div>
			<div class='form-group' style='display: none;' id='pay_method'>
				<label class='col-xs-4 control-label'>Способ оплаты:</label>
				<div class='col-xs-8'>
					<select name='pay_method' class='form-control' disabled>
						<option value="card" selected>Банковская карта</option>
						<option value="paypal">PayPal</option>
					</select>
					<input type='hidden' name='pay_method' value='card' />
				</div>
			</div>
			<div class='form-group'>
				<div class="col-xs-offset-4 col-xs-8">
					<input type='submit' class='btn btn-success' value='Отправить заявку'/>
				</div>
			</div>
		</form>
		<?php
		script(function(){
			?>
			<script>
			function anotherSelectorChange(){
				var name = $(this).attr("name");
				if($(this).val()=="Другое"){
					$("#"+name+"Text").attr("name", name).attr('required', 'required').parent().slideDown(200);
				}else{
					$("#"+name+"Text").attr("name", "").removeAttr('required').parent().slideUp(200);
				}
			};
			$(".selectInputWithAnother").on("change", anotherSelectorChange).trigger("change");
			
			$("select[name='payment']").on("change", function(){
				$("#pay_method").slideToggle(200, "linear", $(this).val()!=700);
			}).trigger("change");
			
			$("#registration").on("submit", function(event){
				var errors = "";
				if($("#registration [name='name']").val() == "") errors = errors + "Не заполнено поле \"ФИО\"\n";
				if($("#registration [name='phone']").val() == "") errors = errors + "Не заполнено поле \"Контактный номер\"\n";
				if($("#registration [name='email']").val() == "") errors = errors + "Не заполнено поле \"E-mail\"\n";
				if($("#registration [name='Город']").val() == "") errors = errors + "Не заполнено поле \"Город\"\n";
				if($("#registration [name='Церковь']").val() == "") errors = errors + "Не заполнено поле \"Церковь\"\n";
				if(errors != ""){
					event.preventDefault();
					alert(errors);
					return false;
				}
			});
			</script>
			<?php
		}); ?>
	</div>
</div>
<?php
template_bottom();
?>