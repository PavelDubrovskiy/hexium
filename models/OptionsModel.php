<?
class Options{
	public static $data=array(
		'goods'=>array(
			'MSL3'=>array(
				'name'=>'Покупка жизни +4, магниты +4, защита +4',
				'description'=>"Купить набор за 75 ₽",
				'price'=>75,
				'publicId'=>'d25cc8fa-2a36-419d-80e3-02a917d483a7',
				'award'=>array('lives'=>4,'magnet'=>4,'shield'=>4)
			),
			'MS2'=>array(
				'name'=>'Покупка магниты +3, защита +3',
				'description'=>"Купить набор из суперсил за 35 ₽",
				'price'=>35,
				'publicId'=>'c87bc1e1-841f-4019-9c61-bcf44a4ca4b3',
				'award'=>array('lives'=>0,'magnet'=>3,'shield'=>3)
			),
			'L5'=>array(
				'name'=>'Покупка жизни +5',
				'description'=>"Купить 5 попыток за 40 ₽",
				'price'=>40,
				'publicId'=>'0aaa4f01-056c-49a4-bc10-f04a8c196d24',
				'award'=>array('lives'=>5,'magnet'=>0,'shield'=>0)
			),
			'L3'=>array(
				'name'=>'Покупка жизни +3',
				'description'=>"Купить 3 попытки за 30 ₽",
				'price'=>30,
				'publicId'=>'0e509349-8753-48ff-a467-af562ee445a6',
				'award'=>array('lives'=>3,'magnet'=>0,'shield'=>0)
			),
			'L1'=>array(
				'name'=>'Покупка жизни +1',
				'description'=>"Купить 1 попытку за 10 ₽",
				'price'=>10,
				'publicId'=>'cc3e40c4-5491-4a6e-a1f1-e058708a84e7',
				'award'=>array('lives'=>1,'magnet'=>0,'shield'=>0)
			),
			'M3'=>array(
				'name'=>'Покупка магниты +3',
				'description'=>"Купить 3 магнита за 17 ₽",
				'price'=>17,
				'publicId'=>'5cbc2187-0163-461f-9959-94085e72a3f3',
				'award'=>array('lives'=>0,'magnet'=>3,'shield'=>0)
			),
			'M1'=>array( 
				'name'=>'Покупка магниты +1',
				'description'=>"Купить 1 магнит за 7 ₽",
				'price'=>7,
				'publicId'=>'b66cd96e-e348-48d7-9d24-76ef757819d0',
				'award'=>array('lives'=>0,'magnet'=>1,'shield'=>0)
			),
			'S3'=>array(
				'name'=>'Покупка защита +3',
				'description'=>"Купить 3 брони за 17 ₽",
				'price'=>17,
				'publicId'=>'00ffd6d8-6889-4075-b65b-d0feb3997e63',
				'award'=>array('lives'=>0,'magnet'=>0,'shield'=>3)
			),
			'S1'=>array(
				'name'=>'Покупка защита +1',
				'description'=>"Купить 1 броню за 7 ₽",
				'price'=>7,
				'publicId'=>'1658d747-e27c-4bce-8b46-231c8c8d1d69',
				'award'=>array('lives'=>0,'magnet'=>0,'shield'=>1)
			),
		),
		'tasks'=>array(
			'PS50'=>array(
				"actionLink"=>"https://lk.megafon.ru/inapp/refill",
				"updatedAt"=>"2022-03-18T14:30:00.000Z",
				"publicId"=>"1f0b534d-79aa-4eba-9178-aef8b9322e8e",
				"name"=>"Положите от 50 ₽ на счет",
				"description"=>"Пополните баланс через приложение «МегаФон»",
				'award'=>array('lives'=>1,'magnet'=>0,'shield'=>0)
			),
			'AUTOP'=>array(
				"actionLink"=>"https://lk.megafon.ru/inapp/autopay",
				"updatedAt"=>"2022-03-18T14:30:00.000Z",
				"publicId"=>"878696ed-c8c8-4328-8a19-a5653b3a4e3a",
				"name"=>"Подключите автоплатёж",
				"description"=>"Задайте любую сумму, и счет будет пополняться автоматически",
				'award'=>array('lives'=>1,'magnet'=>0,'shield'=>0)
			),
			'BIRTHDATE'=>array(
				"actionLink"=>"https://lk.megafon.ru/inapp/personal",
				"updatedAt"=>"2022-03-18T14:30:00.000Z",
				"publicId"=>"b329bff2-4589-4b17-b99a-231fcb0e7128",
				"name"=>"Укажите дату рождения",
				"description"=>"Укажите ваш день рождения в Личном кабинете",
				'award'=>array('lives'=>1,'magnet'=>0,'shield'=>0)
			),
			/*
			'INVITEFRIEND'=>array(
				"actionLink"=>"https://lk.megafon.ru/inapp/invitefriend",
				"updatedAt"=>"2022-03-18T14:30:00.000Z",
				"publicId"=>"ef383256-b6d6-46a2-b55f-6408acd31147",
				"name"=>"Приведите друга",
				"description"=>"Отправьте другу приглашение перейти в МегаФон со своим номером",
				'award'=>array('lives'=>1,'magnet'=>0,'shield'=>0)
			),
			*/
			'EMAIL'=>array(
				"actionLink"=>"https://lk.megafon.ru/inapp/personal",
				"updatedAt"=>"2022-03-18T14:30:00.000Z",
				"publicId"=>"3e7f4a3f-f9ad-45c8-bbc1-f2ee3a080d6a",
				"name"=>"Укажите e-mail",
				"description"=>"Укажите свою почту в Личном кабинете",
				'award'=>array('lives'=>1,'magnet'=>0,'shield'=>0)
			),
			'MPLUS'=>array(
				"actionLink"=>"https://lk.megafon.ru/inapp/engagement",
				"updatedAt"=>"2022-04-14T18:55:00.000Z",
				"publicId"=>"5bcdadb1-e189-4021-88a4-0c45eac732f7",
				"name"=>"Подключите МегаФон Плюс",
				"description"=>"Получите выгодную мультиподписку на несколько сервисов по цене одного",
				'award'=>array('lives'=>5,'magnet'=>0,'shield'=>0)
			),
			/*
			'WAITFRIEND'=>array(
				"actionLink"=>"https://lk.megafon.ru/inapp/invitefriend",
				"updatedAt"=>"2022-04-14T18:55:00.000Z",
				"publicId"=>"ea479c93-72b5-4267-a2f7-9b8b1893baa5",
				"name"=>"Дождитесь друга",
				"description"=>"Дождитесь, когда друг перейдет в МегаФон по вашему приглашению",
				'award'=>array('lives'=>10,'magnet'=>0,'shield'=>0)
			),
			*/
			'CREDITCARD'=>array(
				"actionLink"=>"https://lk.megafon.ru/inapp/newcard",
				"updatedAt"=>"2022-04-14T18:55:00.000Z",
				"publicId"=>"f1af2db7-c261-466c-9dc3-20e636adfe95",
				"name"=>"Добавьте свою карту",
				"description"=>"Привяжите банковскую карту и пополняйте баланс удобнее",
				'award'=>array('lives'=>1,'magnet'=>0,'shield'=>0)
			),
			/*
			'BUYSOME'=>array(
				"actionLink"=>"https://lk.megafon.ru/inapp/newcard",
				"updatedAt"=>"2022-03-18T14:30:00.000Z",
				"publicId"=>"f1af2db7-c261-466c-9dc3-20e636adfe95",
				"name"=>"Купите попытку",
				"description"=>"Купите попытку в магазине и получите суперсилу в подарок",
				'award'=>array('lives'=>0,'magnet'=>0,'shield'=>1)
			)
			*/
		),
		'ratings'=>array(
			'PlaceTop50PerDay'=>array(
				'name'=>'Ежедневный топ 50',
				'publicId'=>'3a061330-80a6-4e77-943d-cf021074eb4f', // - ID приза привязанного в мегафоне
				'award'=>array(
					"isDefault"=>true,
					"segments"=>[
						"3a061330-80a6-4e77-943d-cf021074eb4f"
					],
					"campaignId"=>"paladin01",
					"picture"=>"https://spb.megafon.ru/download/~federal/files/game_center/mf_game_core_month.png",
					"location"=>"rating",
					"publicId"=>"3a061330-80a6-4e77-943d-cf021074eb4f",
					"subtitle"=>"На ваш мобильный счёт",
					"locationId"=>[
						100
					],
					"updatedAt"=>"2022-04-20T11:00:00.000Z",
					"priority"=>20,
					"probability"=>5,
					"description"=>"Как получить приз:<br>Нажмите на кнопку «Забрать», и 100 бонусных рублей зачислится на счёт.<br><br>Бонусные рубли можно будет использовать в течение месяца для оплаты тарифа, звонков, SMS, интернета <a='https://moscow.megafon.ru/lpage/bonus_na_schet/?externalBrowser=true'>и не только.</a> <br>Их нельзя использовать для мобильных переводов, оплаты услуг третьих лиц и контент-провайдеров.",
					"sendNotify"=>true,
					"description2"=>"Бонусные рубли можно использовать в течение месяца для оплаты тарифа, звонков, SMS, интернета <a='https://moscow.megafon.ru/lpage/bonus_na_schet/?externalBrowser=true'>и не только.</a> <br>Их нельзя использовать для мобильных переводов, оплаты услуг третьих лиц и контент-провайдеров.",
					"logo"=>"https://moscow.megafon.ru/download/~federal/files/game_center/logo_box_silver.png",
					"subtitle2"=>"На ваш мобильный счёт",
					"type"=>"award",
					"externalLink"=>"",
					"name"=>"100 бонусных рублей на 1 месяц",
					"limitPerCampaign"=>2100
				)
			),
			'PlaceTop50PerWeek'=>array(
				'name'=>'Еженедельный топ 50',
				'publicId'=>'65926ebc-6797-4d1f-9bd7-b79124fef99e',
				'award'=>array(
					"isDefault"=>true,
					"segments"=>[
						"65926ebc-6797-4d1f-9bd7-b79124fef99e"
					],
					"campaignId"=>"paladin01",
					"picture"=>"https://spb.megafon.ru/download/~federal/files/game_center/mf_game_core_year.png",
					"location"=>"rating",
					"publicId"=>"65926ebc-6797-4d1f-9bd7-b79124fef99e",
					"subtitle"=>"На ваш мобильный счёт",
					"locationId"=>[
						200
					],
					"updatedAt"=>"2022-04-20T11:00:00.000Z",
					"priority"=>20,
					"probability"=>5,
					"description"=>"Как получить приз:<br>Нажмите на кнопку «Забрать», и 500 бонусных рублей зачислится на счёт.<br><br>Бонусные рубли можно будет использовать в течение года для оплаты тарифа, звонков, SMS, интернета <a='https://moscow.megafon.ru/lpage/bonus_na_schet/?externalBrowser=true'>и не только.</a> <br>Их нельзя использовать для мобильных переводов, оплаты услуг третьих лиц и контент-провайдеров.",
					"sendNotify"=>true,
					"description2"=>"Бонусные рубли можно использовать в течение года для оплаты тарифа, звонков, SMS, интернета <a='https://moscow.megafon.ru/lpage/bonus_na_schet/?externalBrowser=true'>и не только.</a> <br>Их нельзя использовать для мобильных переводов, оплаты услуг третьих лиц и контент-провайдеров.",
					"logo"=>"https://moscow.megafon.ru/download/~federal/files/game_center/logo_box_gold.png",
					"subtitle2"=>"На ваш мобильный счёт",
					"type"=>"award",
					"externalLink"=>"",
					"name"=>"500 бонусных рублей на год",
					"limitPerCampaign"=>300
				)
			)
		),
		'cups'=>array(
			'maxsnake1'=>array(
				'name'=>'Самый длинный хвост',
				'stars'=>1,
				'award'=>array('lives'=>0,'magnet'=>1,'shield'=>0,'track'=>0)
			),
			'maxsnake2'=>array(
				'name'=>'Самый длинный хвост',
				'stars'=>2,
				'award'=>array('lives'=>0,'magnet'=>1,'shield'=>0,'track'=>0)
			),
			'maxsnake3'=>array(
				'name'=>'Самый длинный хвост',
				'stars'=>3,
				'award'=>array('lives'=>0,'magnet'=>1,'shield'=>0,'track'=>0)
			),
			'marathon1'=>array(
				'name'=>'Марафонец',
				'stars'=>1,
				'award'=>array('lives'=>0,'magnet'=>0,'shield'=>1,'track'=>0)
			),
			'marathon2'=>array(
				'name'=>'Марафонец',
				'stars'=>2,
				'award'=>array('lives'=>0,'magnet'=>0,'shield'=>1,'track'=>0)
			),
			'marathon3'=>array(
				'name'=>'Марафонец',
				'stars'=>3,
				'award'=>array('lives'=>0,'magnet'=>0,'shield'=>1,'track'=>0)
			),
			'invulnerable1'=>array(
				'name'=>'Неуязвимый',
				'stars'=>1,
				'award'=>array('lives'=>0,'magnet'=>0,'shield'=>0,'track'=>100)
			),
			'invulnerable2'=>array(
				'name'=>'Неуязвимый',
				'stars'=>2,
				'award'=>array('lives'=>0,'magnet'=>0,'shield'=>0,'track'=>200)
			),
			'invulnerable3'=>array(
				'name'=>'Неуязвимый',
				'stars'=>3,
				'award'=>array('lives'=>0,'magnet'=>0,'shield'=>0,'track'=>300)
			),
			'persistent1'=>array(
				'name'=>'Настойчивый',
				'stars'=>1,
				'target'=>7,
				'award'=>array('lives'=>0,'magnet'=>0,'shield'=>0,'track'=>100)
			),
			'persistent2'=>array(
				'name'=>'Настойчивый',
				'stars'=>2,
				'target'=>14,
				'award'=>array('lives'=>0,'magnet'=>0,'shield'=>0,'track'=>200)
			),
			'persistent3'=>array(
				'name'=>'Настойчивый',
				'stars'=>3,
				'target'=>21,
				'award'=>array('lives'=>0,'magnet'=>0,'shield'=>0,'track'=>300)
			),
			'secretcup1'=>array(
				'name'=>'Секретный кубок',
				'stars'=>1,
				'target'=>'Собрать 50 шариков у змейки, не потеряв ни одной жизни',
				'award'=>array('lives'=>1,'magnet'=>1,'shield'=>1,'track'=>0)
			),
			'secretcup2'=>array(
				'name'=>'Секретный кубок',
				'stars'=>2,
				'target'=>'Собрать броню и магнит, не потеряв ни одной жизни',
				'award'=>array('lives'=>1,'magnet'=>1,'shield'=>1,'track'=>0)
			),
			'secretcup3'=>array(
				'name'=>'Секретный кубок',
				'stars'=>3,
				'target'=>'Собрать 3 капсулы подряд, не потеряв ни одной жизни',
				'award'=>array('lives'=>1,'magnet'=>1,'shield'=>1,'track'=>0)
			),
			'supercup'=>array(
				'name'=>'Суперкубок',
				'stars'=>1,
				'target'=>'Соберите все награды',
				'publicId'=>'43cb1157-ee4f-45f7-acdd-a80e8aa4e224',
				'award'=>array(
					 "isDefault"=>true,
					"segments"=>[
						"43cb1157-ee4f-45f7-acdd-a80e8aa4e224"
					],
					"campaignId"=>"paladin01",
					"picture"=>"https://spb.megafon.ru/download/~federal/files/game_center/mf_game_ozon.png",
					"location"=>"grand_prix",
					"publicId"=>"43cb1157-ee4f-45f7-acdd-a80e8aa4e224",
					"subtitle"=>"Поздравляем!<br>Для получения приза нажмите «Забрать».",
					"locationId"=>[
						300
					],
					"updatedAt"=>"2022-04-15T19:00:00.000Z",
					"priority"=>20,
					"probability"=>100,
					"description"=>"Ваш гран-при — электронный подарочный сертификат для покупок на Ozon!<br>Активируйте его в личном кабинете на Ozon в течение 12 месяцев. Затем средства можно будет тратить на покупки в течение 3 лет.",
					"sendNotify"=>false,
					"description2"=>"Ваш гран-при — электронный подарочный сертификат для покупок на Ozon!<br>-Как воспользоваться призом:<br>Скопируйте промокод и активируйте его в личном кабинете на Ozon в течение 12 месяцев. Затем средства можно будет тратить на покупки в течение 3 лет.",
					"logo"=>"https://moscow.megafon.ru/download/~federal/files/game_center/logo_megafon_white.png",
					"subtitle2"=>"Приз ваш!",
					"type"=>"coupon",
					"externalLink"=>"",
					"name"=>"500 рублей для покупок на Ozon",
					"limitPerCampaign"=>2500
				)
			)
		)
	);
	public static $awards=array(
		'boosters'=>array(
			'Ваш первый приз!'=>array(
				'name'=>'Ваш первый приз!',
				'award'=>array('lives'=>3,'magnet'=>0,'shield'=>0,'track'=>0)
			),
			'Магнит'=>array(
				'name'=>'Магнит',
				'award'=>array('lives'=>0,'magnet'=>1,'shield'=>0,'track'=>0)
			),
			'Одна игровая попытка'=>array(
				'name'=>'Одна игровая попытка',
				'award'=>array('lives'=>1,'magnet'=>0,'shield'=>0,'track'=>0)
			),
			'Броня'=>array(
				'name'=>'Броня',
				'award'=>array('lives'=>0,'magnet'=>0,'shield'=>1,'track'=>0)
			),
			'Три магнита'=>array(
				'name'=>'Три магнита',
				'award'=>array('lives'=>0,'magnet'=>3,'shield'=>0,'track'=>0)
			),
			'Три брони'=>array(
				'name'=>'Три брони',
				'award'=>array('lives'=>0,'magnet'=>0,'shield'=>3,'track'=>0)
			),
			'Три игровые попытки'=>array(
				'name'=>'Три игровые попытки',
				'award'=>array('lives'=>3,'magnet'=>0,'shield'=>0,'track'=>0)
			)
		)
	);
	public function initOptions(){
		$settings=Cache::get('client_settings');
		foreach(array('marathon','maxsnake','invulnerable') as $item){
			for($i=1;$i<4;$i++){
				Options::$data['cups'][$item.$i]['target']=(int)$settings[$item][$i-1];
			}
		}
	}
	public static function getPromo($guid,$publicId){
		$promoCodes=array(
			'630f302d-cf0d-47c1-90c3-51d3735bc113'=>'MFTV_GAME_2232',
			'7d158418-a28d-4a31-9549-c31c04112134'=>'MFTV_GAME_4267',
			'f4c1c26b-d621-47c1-88e3-70f36e5c9f04'=>'MFTV_GAME_927',
			'07496018-69f5-42fa-9543-f57f69978d6b'=>'MFTV_GAME_443',
			'bf669a6c-ea74-43de-b841-bd125bc433a7'=>'MFTV_GAME_343',
			'c894569a-d000-42b7-8b1a-216a17f48487'=>'MFTV_GAME_322',
			'8713b8d0-e4a4-4010-accb-22c83b7c1080'=>'MFTV_GAME_336',
			
			'462541cb-68cb-4fd3-8515-a97937fe494d'=>'MEGAResp2022'
		);
		if(isset($promoCodes[$publicId])) $data=$promoCodes[$publicId];
		if($publicId=='b9ae7b27-54f3-4938-8338-195f0c4dd763'){
			if(($fp = fopen(SOURCE.'/yaplus.csv', 'r')) !== FALSE) {
				$issued=false;;
				while (($row = fgetcsv($fp, 0, ";")) !== FALSE) {
					if(!$issued && (!isset($row[1]) || !$row[1])){
						$data=$row[0];
						$row[1]=$guid;
						$issued=true;
					}
					$list[] = $row;
				}
				fclose($fp);
				$fp = fopen(SOURCE.'/yaplus.csv', 'w');
				foreach ($list as $fields) {
					fputcsv($fp, $fields, ';', '"');
				}
				fclose($fp);
			}
		}
		if($publicId=='43cb1157-ee4f-45f7-acdd-a80e8aa4e224'){
			if(($fp = fopen(SOURCE.'/ozon.csv', 'r')) !== FALSE) {
				$issued=false;;
				while (($row = fgetcsv($fp, 0, ";")) !== FALSE) {
					if(!$issued && (!isset($row[1]) || !$row[1])){
						$data=$row[0];
						$row[1]=$guid;
						$issued=true;
					}
					$list[] = $row;
				}
				fclose($fp);
				$fp = fopen(SOURCE.'/ozon.csv', 'w');
				foreach ($list as $fields) {
					fputcsv($fp, $fields, ';', '"');
				}
				fclose($fp);
			}
		}
		if($data){
			$coupons=Cache::get('coupons');
			if(!$coupons) $coupons=array();
			if(!isset($coupons[$publicId])) $coupons[$publicId]=array();
			$coupons[$publicId][]=array(
				'uid'=>$guid,
				'publicId'=>$publicId,
				'coupon'=>$data,
				'createdAt'=>date_format(date_create(date('Y-m-d H:i:s')), 'c')
			);
			Cache::set('coupons',$coupons);
		}
		return $data;
	}
}
Options::initOptions();
?>