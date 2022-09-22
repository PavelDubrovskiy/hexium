<?php
require SOURCE.'/kernel/lib/Excel2/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Reports{
	public static function reportXLSX(){
		$data=array(
			'Количество игроков'=>0,
			'Кол-во входов в игру'=>0,
			'Кол-во регистраций'=>0,
			'Avg. DAU'=>0,
			'Avg. WAU'=>0,
			'Кол-во активных игроков (сыграли 8 и более раз)'=>0,
			'Кол-во активных в течение недели игроков (сыграли 8 и более раз за последнюю неделю)'=>0,
			'Количество сыгранных игр'=>0,
			'Среднее количество игр на абонента'=>0,
			'Всего выполнено заданий'=>0,
			'Среднее количество выполненных заданий на абонента'=>0,
			'Положите 50 ₽ на счёт'=>0,
			'Подключите автоплатёж'=>0,
			'Укажите дату рождения'=>0,
			'Приведите друга'=>0,
			'Добавьте свой e-mail'=>0,
			'Подключите МегаФон Плюс'=>0,
			'Дождитесь перехода друга'=>0,
			'Добавьте свою карту'=>0,
			'уникальные примененные коды'=>0, //???
			'Кол-во платящих игроков'=>0,
			'ARPPU (ср. доход на 1 плаятещего пользователя)'=>0,
			'Средний чек одной покупки'=>0,
			'Всего выдано подарков'=>0, // Тоже самое что и активировано ???
			'Подарков активировано успешно (202)'=>0, //Исключаем запросы на покупку ???
			'Подарков активировано неуспешно (422)'=>0, //Исключаем запросы на покупку ???
			'Ошибка активации (5xx)'=>0, // Исключаем запросы на покупку ???
			'Регистрации'=>array(),
			'Игры'=>array(),
			'Покупки'=>array(),
			'Задания'=>array(),
			'Призы'=>array(),
		);
		$tasks=array('Положите 50 ₽ на счёт'=>"PS50",'Подключите автоплатёж'=>"AUTOP",'Укажите дату рождения'=>"BIRTHDATE",'Приведите друга'=>"INVITEFRIEND",
            'Добавьте свой e-mail'=>"EMAIL",'Подключите МегаФон Плюс'=>"MPLUS",'Дождитесь перехода друга'=>"WAITFRIEND",'Добавьте свою карту'=>"CREDITCARD");
			
		$cdate=date('Y-m-d');
		//$cdate='2022-04-27';
		
		$date1 = new DateTime('2022-04-28');
		$date2 = new DateTime($cdate);
		$interval = $date1->diff($date2);
		$days=$interval->days;
		$days7=$days%7;
		
		//$statistic=Cache::get('statistic');
		$statistic=Cache::get('statistic'.$cdate);

		//$list=Cache::get('client_list');
		if($handle = opendir(SOURCE.CACHE_DIR)) {
		  while (false !== ($file = readdir($handle)))   {
			if ($file != "." && $file != ".." && $file!='megafon_client_list.ch' && strpos($file,'megafon_client_')!==false)
			{
				$guid=str_replace('megafon_client_','',$file);
				$guid=str_replace('.ch','',$guid);
				$list[$guid]=$guid;
			}
		  }
		  closedir($handle);
		}
		$data['Количество игроков']=count($list);
		$data['Кол-во входов в игру']=$statistic['Кол-во входов в игру'];
		$data['Кол-во регистраций']=count($list);
		foreach($statistic['Игры'] as $key=>$items){
			foreach($items as $item){
				$data['Количество сыгранных игр']+=$item;
			}
		}
		if($data['Количество игроков']) $data['Среднее количество игр на абонента']=round($data['Количество сыгранных игр']/$data['Количество игроков'],2);
		$amount=0;
		$amountCount=0;
		$puruser=array();
		foreach($list as $guid){
			$client=Cache::get('client_'.$guid);
			$data['Всего выполнено заданий']+=count($client['tasks']);
			$completedTasksToday=array();
			foreach($tasks as $key=>$item){
				if(isset($client['tasks'][$item])){
					$data[$key]++;
					if($cdate==date('Y-m-d',strtotime($client['tasks'][$item]['completedAt']))){
						$completedTasksToday[$key]=1;
					}
				}
			}
			if(!empty($completedTasksToday)){
				$completedTasksToday['guid']=$client['guid'];
				$data['Задания'][]=$completedTasksToday;
			}
			foreach($client['targets'] as $key=>$item){
				if($cdate==date('Y-m-d',strtotime($item['completedAt']))){
					$data['Призы'][]=array(
						'guid'=>$client['guid'],
						'awardRequest'=>$item['awardRequest'],
						'publicId'=>$item['awardPublicId']
					);
				}
			}
			$history=Cache::get('history_'.$guid);
			$paymentCount=0;
			$paymentAmount=0;
			if(count($history['purchases'])>0){
				$data['Кол-во платящих игроков']++;
				foreach($history['purchases'] as $item){
					if($item["status"]=="Done"){
						$puruser[$guid]=$guid;
						//if(!isset($puruser[$guid])) $puruser[$guid]=array('date'=>$client['cdate']);
						$amount+=$item['data']['price'];
						$amountCount++;
						if($item['status']=='Done' && $cdate==date('Y-m-d',strtotime($item['time']))){
							$paymentCount++;
							$paymentAmount+=$item['data']['price'];
						}
					}
				}
			}
			if($paymentCount){
				$data['Покупки'][]=array(
					'guid'=>$client['guid'],
					'кол-во покупок'=>$paymentCount,
					'сумма покупок'=>$paymentAmount
				);
			}
			foreach($history['requests'] as $item){
				if($item['type']=='award'){
					$data['Всего выдано подарков']++;
					if($item['status']==202){
						$data['Подарков активировано успешно (202)']++;
					}elseif($item['status']==422){
						$data['Подарков активировано неуспешно (422)']++;
					}else{
						$data['Ошибка активации (5xx)']++;
					}
				}
			}
			if($cdate==date('Y-m-d',strtotime($client['createdAt']))){
				$data['Регистрации'][]=array('guid'=>$client['guid']);
			}
			if(isset($statistic['Игры'][$cdate][$guid])){
				$levelId=0;
				foreach($client['targets'] as $item){
					if($item['completed'] && $levelId<$item['levelId']) $levelId=$item['levelId'];
				}
				$data['Игры'][]=array(
					'guid'=>$client['guid'],
					'кол-во игр'=>$statistic['Игры'][$cdate][$guid],
					'уровень, на котором остановился игрок'=>$levelId,
					'Макс уровень'=>count($client['targets'])
				);
			}
			if($cdate==date('Y-m-d',strtotime($client['lastLogin']))){
				$data['Avg. DAU']++;
			}
			
			if(date('Y-m-d',strtotime($cdate.' -'.$days7.' days'))<=date('Y-m-d',strtotime($client['lastLogin']))){
				$data['Avg. WAU']++;
			}
			if(isset($statistic['ИгрыИгрока'][$guid])){
				$playEight=0;
				foreach($statistic['ИгрыИгрока'][$guid] as $date=>$item){
					$playEight+=$item;
				}
				if($playEight>7) $data['Кол-во активных игроков (сыграли 8 и более раз)']++;
			}
		}
		$statisticList=array();
		for($i=0;$i<7;$i++){
			$idate=date('Y-m-d',strtotime($cdate.' -'.$i.' days'));
			$statisticList[]=Cache::get('statistic'.$idate);
		}
		$playEightWeek=0;
		$players=array();
		foreach($statisticList as $statisticItems){
			foreach($statisticItems['ИгрыИгрока'] as $uid=>$item){
				if(!isset($players[$uid])) $players[$uid]=0;
				$players[$uid]+=end($item);
			}
		}
		foreach($players as $player){
			if($player>8) $playEightWeek++;
		}
		$data['Кол-во активных в течение недели игроков (сыграли 8 и более раз за последнюю неделю)']=$playEightWeek;
			
		$data['Кол-во платящих игроков']=count($puruser);
		if($data['Количество игроков']) $data['Среднее количество выполненных заданий на абонента']=round($data['Всего выполнено заданий']/$data['Количество игроков'],2);
		if($data['Кол-во платящих игроков']) $data['ARPPU (ср. доход на 1 плаятещего пользователя)']=round($amount/$data['Кол-во платящих игроков'],2);
		if($amountCount) $data['Средний чек одной покупки']=round($amount/$amountCount,2);
		//print_r($data);
		
		$AvgDAU=Cache::get('AvgDAU');
		$dau=0;
		foreach($AvgDAU as $item){
			$dau+=$item;
		}
		$dau+=$data['Avg. DAU'];
		$AvgDAU[$cdate]=round($dau/(count($AvgDAU)+1));
		Cache::set('AvgDAU',$AvgDAU);
		$data['Avg. DAU']=$AvgDAU[$cdate];
		
		$AvgWAU=Cache::get('AvgWAU');
		$wau=0;
		foreach($AvgWAU as $item){
			$wau+=$item;
		}
		$wau+=$data['Avg. WAU'];
		if($days7==6){
			$AvgWAU[$cdate]=$data['Avg. WAU'];
			Cache::set('AvgWAU',$AvgWAU);
		}
		$data['Avg. WAU']=$wau/3;
		$filename=Reports::getStatisticFile($data,$cdate);
		return $filename;
		//return $data;
	}
	public static function getStatisticFile($post,$cdate){
		/*header('Content-Type: text/html; charset=utf-8');
		header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', FALSE);
		header('Pragma: no-cache');
		header('Content-transfer-encoding: binary');
		header('Content-Disposition: attachment; filename=statistic.xlsx');
		header('Content-Type: application/octet-stream');*/
		//$spreadsheet = new Spreadsheet();
		$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
		$spreadsheet = $reader->load(SOURCE.'/report_eng.xlsx');
		//$as=$spreadsheet->getActiveSheet();
		
		$frow=4;
		
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('B3', $post['Количество игроков']);
		$sheet->setCellValue('C3', $post['Кол-во входов в игру']);
		$sheet->setCellValue('D3', $post['Кол-во регистраций']);
		$sheet->setCellValue('E3', $post['Avg. DAU']);
		$sheet->setCellValue('F3', $post['Avg. WAU']);
		$sheet->setCellValue('G3', $post['Кол-во активных игроков (сыграли 8 и более раз)']);
		$sheet->setCellValue('H3', $post['Кол-во активных в течение недели игроков (сыграли 8 и более раз за последнюю неделю)']);
		$sheet->setCellValue('I3', $post['Количество сыгранных игр']);
		$sheet->setCellValue('J3', $post['Среднее количество игр на абонента']);
		$sheet->setCellValue('K3', $post['Всего выполнено заданий']);
		$sheet->setCellValue('L3', $post['Среднее количество выполненных заданий на абонента']);
		$sheet->setCellValue('M3', $post['Положите 50 ₽ на счёт']);
		$sheet->setCellValue('N3', $post['Подключите автоплатёж']);
		$sheet->setCellValue('O3', $post['Укажите дату рождения']);
		$sheet->setCellValue('P3', $post['Приведите друга']);
		$sheet->setCellValue('Q3', $post['Добавьте свой e-mail']);
		$sheet->setCellValue('R3', $post['Подключите МегаФон Плюс']);
		$sheet->setCellValue('S3', $post['Дождитесь перехода друга']);
		$sheet->setCellValue('T3', $post['Добавьте свою карту']);
		$sheet->setCellValue('U3', '');
		$sheet->setCellValue('V3', '');
		$sheet->setCellValue('W3', $post['Кол-во платящих игроков']);
		$sheet->setCellValue('X3', $post['ARPPU (ср. доход на 1 плаятещего пользователя)']);
		$sheet->setCellValue('Y3', $post['Средний чек одной покупки']);
		$sheet->setCellValue('Z3', $post['Всего выдано подарков']);
		$sheet->setCellValue('AA3', $post['Подарков активировано успешно (202)']);
		$sheet->setCellValue('AB3', $post['Подарков активировано неуспешно (422)']);
		$sheet->setCellValue('AC3', $post['Ошибка активации (5xx)']);
		$sheet = $spreadsheet->getSheet(1);
		foreach($post['Регистрации'] as $i=>$item){
			$sheet->setCellValue('A'.($i+$frow), 'paladin01');
			$sheet->setCellValue('B'.($i+$frow), $item['guid']);
			$sheet->setCellValue('C'.($i+$frow), $cdate);
		}
		$sheet = $spreadsheet->getSheet(2);
		foreach($post['Игры'] as $i=>$item){
			$sheet->setCellValue('A'.($i+$frow), 'paladin01');
			$sheet->setCellValue('B'.($i+$frow), $item['guid']);
			$sheet->setCellValue('C'.($i+$frow), $cdate);
			$sheet->setCellValue('D'.($i+$frow), $item['кол-во игр']);
			$sheet->setCellValue('E'.($i+$frow), $item['уровень, на котором остановился игрок']);
			$sheet->setCellValue('F'.($i+$frow), $item['Макс уровень']);
		}
		$sheet = $spreadsheet->getSheet(3);
		foreach($post['Покупки'] as $i=>$item){
			$sheet->setCellValue('A'.($i+$frow), 'paladin01');
			$sheet->setCellValue('B'.($i+$frow), $item['guid']);
			$sheet->setCellValue('C'.($i+$frow), $cdate);
			$sheet->setCellValue('D'.($i+$frow), $item['кол-во покупок']);
			$sheet->setCellValue('E'.($i+$frow), $item['сумма покупок']);
		}
		$sheet = $spreadsheet->getSheet(4);
		foreach($post['Задания'] as $i=>$item){
			$sheet->setCellValue('A'.($i+$frow), 'paladin01');
			$sheet->setCellValue('B'.($i+$frow), $item['guid']);
			$sheet->setCellValue('C'.($i+$frow), $cdate);
			$sheet->setCellValue('D'.($i+$frow), $item['Положите на счёт от 50 ₽']);
			$sheet->setCellValue('E'.($i+$frow), $item['Подключите автоплатёж']);
			$sheet->setCellValue('F'.($i+$frow), $item['Укажите дату рождения']);
			$sheet->setCellValue('G'.($i+$frow), $item['Приведите друга']);
			$sheet->setCellValue('H'.($i+$frow), $item['Добавьте свой e-mail']);
			$sheet->setCellValue('I'.($i+$frow), $item['Подключите МегаФон Плюс']);
			$sheet->setCellValue('J'.($i+$frow), $item['Дождитесь перехода друга']);
			$sheet->setCellValue('K'.($i+$frow), $item['Добавьте свою карту']);
		}
		$sheet = $spreadsheet->getSheet(5);
		foreach($post['Призы'] as $i=>$item){
			$sheet->setCellValue('A'.($i+$frow), 'paladin01');
			$sheet->setCellValue('B'.($i+$frow), $item['guid']);
			$sheet->setCellValue('C'.($i+$frow), $cdate);
			$sheet->setCellValue('D'.($i+$frow), $item['publicId']);
			$sheet->setCellValue('E'.($i+$frow), $item['awardRequest']);
		}
		//$writer = new Xlsx($spreadsheet);
		//$writer->save('php://output');
		$name=date('dmy',strtotime($cdate)).'_paladin_1_eng.xlsx';
		file_put_contents(SOURCE.'/html/u/'.$name,'');
		chmod(SOURCE.'/html/u/'.$name,0777);
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
		$writer->save(SOURCE.'/html/u/'.$name);
		if(isset($_GET['simple'])) return $name;
		sleep(3);
		try{
			$connection = ssh2_connect(MEGAFON_SFTP_url, 22);
			ssh2_auth_password($connection, MEGAFON_SFTP_login, MEGAFON_SFTP_password);
			$sftp = ssh2_sftp($connection);
			$sftpSend = @file_get_contents(SOURCE.'/html/u/'.$name);
			$sftpStream = @fopen('ssh2.sftp://'.$sftp.'/'.$name, 'w');
			if (@fwrite($sftpStream, $sftpSend) === false) {
				throw new Exception("Could not send data from file: $srcFile.");
			}
			fclose($sftpStream);
			return $name;
		}catch (Exception $e){
			echo $e->getMessage() . "\n";
		}
	}
	public static function dfe($num){
		$data='';
		if(floor($num/26)==0){
			$data.=chr($num+65);
		}elseif(floor($num/26/26)==0){
			$data.=chr(floor($num/26-1)%26+65).chr($num%26+65);
		}elseif(floor($num/26/26/26)==0){
			$data.=chr(floor($num/26/26-1)%26+65).chr(floor($num/26-1)%26+65).chr($num%26+65);
		}
		return $data;
	}
	public static function report2(){
		die;
		ini_set('max_execution_time', 900);
		$list=array();
		$tasks=array();
		
		$targets=array('300'=>0,'600'=>0,'900'=>0,'1200'=>0,'1500'=>0,'1800'=>0,'2100'=>0,'2400'=>0,'2700'=>0,'3000'=>0,'3500'=>0,'4000'=>0,
			'4500'=>0,'5000'=>0,'5500'=>0,'6000'=>0,'6500'=>0,'7000'=>0,'8000'=>0,'9000'=>0,'10000'=>0,'12000'=>0,'14000'=>0,'16000'=>0,
			'18000'=>0,'20000'=>0,'22500'=>0,'25000'=>0,'27500'=>0,'30000'=>0,'32500'=>0);
		$targetsRev=$targets;
		krsort($targetsRev);		
		if($handle = opendir(SOURCE.CACHE_DIR)) {
		  while (false !== ($file = readdir($handle)))   {
			if ($file != "." && $file != ".." && $file != 'megafon_settings.ch' && $file!='megafon_client_list.ch' && strpos($file,'megafon_client_')!==false)
			{
				$guid=str_replace('megafon_client_','',$file);
				$guid=str_replace('.ch','',$guid);
				if($guid!='settings'){
					//$list[$guid]=$guid;
					$client=Cache::get('client_'.$guid);
					foreach($targetsRev as $key=>$item){
						if($client['targets'][$key]['completed']==1){
							$targets[$key]++;
							break;
						}
					}
					/*foreach($client['targets'] as $key=>$item){
						if(!isset($tasks[$item['taskData']['name']])) $tasks[$item['taskData']['name']]=1;
						else $tasks[$item['taskData']['name']]++; 
					}*/
				}
			}
		  }
		  closedir($handle);
		}
		/*
		foreach($tasks as $key=>$item){
			print $key.';'.$item."\n";
		}*/
		$i=1;
		foreach($targets as $item){
			print $i.';'.$item."\n";
			$i++;
		}
		
		/*$sdate='2022-04-27';
		$day=1;
		while($cdate<'2022-06-09'){
			$cdate=date('Y-m-d',strtotime($sdate.' +'.$day.' days'));
			print $cdate.' '."\n";
			
			$statistic=Cache::get('statistic'.$cdate);
			if(is_array($statistic)){
				print 'isset';
				foreach($statistic['Игры'][$cdate] as $guid=>$item){
					if(!isset($list[$guid])) $list[$guid]=$item;
					else $list[$guid]+=$item;
				}
			}else{
				//print 'file '.date('dmy',strtotime($cdate)).'_paladin_1.csv';
				if(($fp = fopen(SOURCE.'/'.date('dmy',strtotime($cdate)).'_paladin_1.csv', 'r')) !== FALSE) {
					$ri=0;
					while (($row = fgetcsv($fp, 0, ";")) !== FALSE) {
						if($ri>1){
							$guid=$row[1];
							$item=$row[3];
							if(!isset($list[$guid])) $list[$guid]=$item;
							else $list[$guid]+=$item;
						}
						$ri++;
					}
					fclose($fp);
				}
			}
			print "\n";
			$day++;
		}
		//
		print count($list);
		$fp = fopen(SOURCE.'/report2.csv', 'w');
		foreach($list as $key=>$item){
			fputcsv($fp, array($key,$item), ';', '"');
		}
		fclose($fp);
		*/
		//print_r(Cache::get('statistic2022-05-07'));
		//print strtotime(date('Y-m-d H:i:s'))-$start; print ' sec';
	}
}
?>