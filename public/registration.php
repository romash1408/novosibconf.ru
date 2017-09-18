<?php
require_once("../main.php");
template_top();
?>

<div id='registration' class='wrapper'>
	<div>
		<div><h2>Регистрация</h2></div>

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
				<div class="col-xs-8" style='float: right; margin-top: 10px;display: none;' >
					<input type='text' id='Узнали_о_конференцииText' name='Узнали_о_конференции' class='form-control'  placeholder='Как вы узнали о Конференции?' />
				</div>
			</div>

			<div class='form-group'>
				<label class='col-xs-4 control-label' for='Город'>Необходимо ли вам расселение?</label>
				<div class='col-xs-8'>
					<label class="radio-inline">
						<input type="radio" name="Необходимо_расселение" value="Да"> Да
					</label>
					<label class="radio-inline">
						<input type="radio" name="Необходимо_расселение" value="Нет" checked> Нет
					</label>
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
			function anotherSelectorChange()
			{
				var name = $(this).attr("name");
				if ($(this).val() == "Другое")
				{
					$("#"+name+"Text").attr("name", name).attr('required', 'required').parent().slideDown(200);
				}else{
					$("#"+name+"Text").attr("name", "").removeAttr('required').parent().slideUp(200);
				}
			};

			$(".selectInputWithAnother").on("change", anotherSelectorChange).trigger("change");
			
			$("#registration").on("submit", function(event){
				var errors = "",
					required = {
						'name': 'ФИО',
						'phone': 'Контактный номер',
						'email': 'E-mail',
						'Город': 'Город',
					};

				for (var name in required) {
					if ($("#registration [name='" + name + "']").val() == "")
					{
						errors = errors + "Не заполнено поле \"" + required[name] + "\"\n";
					}
				}
				
				if (errors != "")
				{
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