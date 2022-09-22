<style>
	.st-center{text-align:center}
</style>
<div class="panel panel-default">
	<div class="panel-heading">
		<h1 class="panel-title st-center">Песочница GEXIUM</h1>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title st-center">Запустить игру</h3>
	</div>
	<div class="panel-body st-center">
		<a href="https://megafon.paladin-mobile.ru/?username=<?=TESTID?>&checkSum=<?=sha1(TESTID.SECRET)?>" target="_blank" class="btn btn-primary">Главный игрок</a><br><br>
		<?$gamer=Api::guidv4()?>
		<a href="https://megafon.paladin-mobile.ru/?username=<?=$gamer?>&checkSum=<?=sha1($gamer.SECRET)?>" target="_blank" class="btn btn-primary">Cлучайный игрок</a><br><br>
		<span onclick="clearClient()" class="btn btn-primary">Сбросить главного игрока</span><br><br>
	</div>
</div>
<script>
	function clearClient(){
		$.ajax({
		  url: 'https://<?=$_SERVER['HTTP_HOST']?>/api/v1/clearClient?guid=<?=TESTID?>&checksum=<?=sha1(TESTID.SECRET)?>',
		}).done(function() {
			alert('cleared')
		});
	}
</script>
<?/*?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Поучить состояние МФЦ</h3>
	</div>
	<div class="panel-body">
		<button class="btn btn-primary" id="getComplex">Отправить</button><br><br>
		<p>Результат</p>
		<pre class="prettyjson" id="getComplexResult"></pre>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Сохранить пользователя</h3>
	</div>
	<div class="panel-body">
		<form id="saveClientForm">
			<div class="form-inline">
				<div class="row">
					<div class="col-md-2">
						<div class="form-group">
							<label>idClient</label><br>
							<input type="text" class="form-control" name="idClient" value="123456789">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label>nameComplex</label><br>
							<input type="text" class="form-control" name="nameComplex" value="Центр госуслуг района Фили-Давыдково">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label>AGE (возраст)</label><br>
							<input type="text" class="form-control" name="AGE" value="35">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label>GENDER (пол)</label><br>
							<input type="text" class="form-control" name="GENDER" value="M">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label>HT (рост)</label><br>
							<input type="text" class="form-control" name="HT" value="175.0">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label>WT (вес)</label><br>
							<input type="text" class="form-control" name="WT" value="75.8">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						&nbsp;
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<div class="form-group">
							<label>BMI (ИМТ)</label><br>
							<input type="text" class="form-control" name="BMI" value="24.8">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label>BMR (потр. калорий)</label><br>
							<input type="text" class="form-control" name="BMR" value="1648">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label>BFM (Жировая масса)</label><br>
							<input type="text" class="form-control" name="BFM" value="16.7">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label>PBF (Процент жира)</label><br>
							<input type="text" class="form-control" name="PBF" value="22.0">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label>VFA (Висцерадбный жир)</label><br>
							<input type="text" class="form-control" name="VFA" value="90.2">
						</div>
					</div>
				</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						&nbsp;
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<button class="btn btn-primary" id="saveClient">Отправить</button><br><br>
					</div>
				</div>
			</div>
			<input type="hidden" name="appkey" value="en0O4yYREn879va4">
		</form>
		<p>Результат</p>
		<pre class="prettyjson" id="saveClientResult"></pre>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Получить меню</h3>
	</div>
	<div class="panel-body">
		<form id="getMenuForm">
			<div class="form-inline">
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label>complex</label><br>
							<input type="text" class="form-control" name="complex" value="1">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>fizgroup (Группа физ. акт.)</label><br>
							<input type="text" class="form-control" name="fizgroup" value="1">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>allergy[] (аллергия 1 moloko)</label><br>
							<input type="text" class="form-control" name="allergy[]" value="">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>allergy[] (аллергия 2 yayca)</label><br>
							<input type="text" class="form-control" name="allergy[]" value="">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						&nbsp;
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label>disease (болезнь)</label><br>
							<input type="text" class="form-control" name="disease" value="">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						&nbsp;
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<button class="btn btn-primary" id="getMenu">Отправить</button>
					</div>
				</div>
			</div>
			<input type="hidden" name="appkey" value="en0O4yYREn879va4">
		</form>
		<p>Результат</p>
		<span class="prettyjson" id="getMenuResult"></span>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Авторизоваться</h3>
	</div>
	<div class="panel-body">
		<form id="loginForm">
			<div class="form-inline">
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label>Login</label><br>
							<input type="text" class="form-control" id="login" value="">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label>Password</label><br>
							<input type="text" class="form-control" id="password" value="">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						&nbsp;
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<button class="btn btn-primary" id="loginBut">Отправить</button>
					</div>
				</div>
			</div>
		</form>
		<p>Результат</p>
		<pre class="prettyjson" id="loginResult"></pre>
	</div>
</div>
<?*/?>