<?
class Summarizing{
	public function setSummarizing(){
		$w=date('w');
		$yesterday=date('Y-m-d',strtotime('-1 day')).' 00:00:00';
		$client_list=array();
		$i=1;
		if($handle = opendir(SOURCE.CACHE_DIR)) {
		  while (false !== ($file = readdir($handle)))   {
			if ($file != "." && $file != ".." && $file!='megafon_client_list.ch' && strpos($file,'megafon_client_')!==false)
			{
				$guid=str_replace('megafon_client_','',$file);
				$guid=str_replace('.ch','',$guid);
				$client_list[$guid]=$guid;
			}
		  }
		  closedir($handle);
		}
		foreach($client_list as $guid){
			$data=Cache::get('client_'.$guid);
			$data['longLogin']=21;
			if($data['lives']<3){
				$data['lives']=3;
				if($w==0){
					$data['bestlengthday']=0;
					$data['bestlengthweek']=0;
				}else{
					$data['bestlengthday']=0;
				}
				Cache::set('client_'.$guid,$data);
			}
			/*if($data['longLogin']==1 && $data['lives']<3) $data['lives']=3;
			if($data['longLogin']==2 && $data['lives']<4) $data['lives']=4;
			if($data['longLogin']==3 && $data['lives']<5) $data['lives']=5;
			if($data['longLogin']>=4 && $data['lives']<6) $data['lives']=6;
			if(date('Y-m-d H:i:s',strtotime($data['lastLogin']))>=$yesterday){
				$data['longLogin']++;
			}else{
				$data['longLogin']=1;
			}*/
		}
		return true;
	}
	public function setSummarizingTop50(){
		$w=date('w');
		$yesterday=date('Y-m-d',strtotime('-1 day')).' 00:00:00';
		$PlaceTop50PerDay=Cache::get('PlaceTop50PerDay');
		$sql='SELECT guid, lengthday FROM {{clients_length}} WHERE guid NOT IN (\''.implode('\',\'',$PlaceTop50PerDay).'\') ORDER BY lengthday DESC LIMIT 50';
		$client_list1=DB::getAll($sql);
		$place=1;
		foreach($client_list1 as $item){
			$guid=$item['guid'];
			$data=Cache::get('client_'.$guid);
			$history=array(
				'time'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'PlaceTop50PerDay'=>$place
			);
			Clients::setHistory($guid,'rating',$history);
			if($data['rating']['PlaceTop50PerDay']['awardRequest']==0){
				$data['rating']['PlaceTop50PerDay']=array(
					'place'=>$place,
					'completed'=>1,
					'completedAt'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
					'awardRequest'=>0,
					'awardPublicId'=>'',
					'awardData'=>array()
				);
				$history=$data['rating']['PlaceTop50PerDay'];
				$history['name']='Попал в ежедневный рейтинг топ 50';
				Clients::setHistory($guid,'rating',$history);
			}
			Cache::set('client_'.$guid,$data);
			$PlaceTop50PerDay[$guid]=$guid;
			$place++;
		}
		Cache::set('PlaceTop50PerDay',$PlaceTop50PerDay);
		if($w==0){
			$place=1;
			$PlaceTop50PerWeek=Cache::get('PlaceTop50PerWeek');
			$sql='SELECT guid, lengthweek FROM {{clients_length}} WHERE guid NOT IN (\''.implode('\',\'',$PlaceTop50PerWeek).'\') ORDER BY lengthweek DESC LIMIT 50';
			$client_list2=DB::getAll($sql);
			foreach($client_list2 as $item){
				$guid=$item['guid'];
				$data=Cache::get('client_'.$guid);
				$history=array(
					'time'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
					'PlaceTop50PerWeek'=>$place
				);
				Clients::setHistory($guid,'rating',$history);
				$data['rating']['PlaceTop50PerWeek']=array(
					'place'=>$place,
					'completed'=>1,
					'completedAt'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
					'awardRequest'=>0,
					'awardPublicId'=>'',
					'awardData'=>array()
				);
				$history=$data['rating']['PlaceTop50PerDay'];
				$history['name']='Попал в еженедельный рейтинг топ 50';
				Clients::setHistory($guid,'rating',$history);
				Cache::set('client_'.$guid,$data);
				$PlaceTop50PerDay[$guid]=$guid;
				$place++;
			}
			Cache::set('PlaceTop50PerWeek',$PlaceTop50PerWeek);
		}
		if($w==0){
			$sql='
				UPDATE {{clients_length}} 
				SET lengthweek=0
			';
			DB::exec($sql,$dataSql);
		}else{
			$sql='
				UPDATE {{clients_length}} 
				SET lengthday=0
			';
			DB::exec($sql,$dataSql);
		}
		return true;
	}
	/*public function setSummarizingTop50(){
		$w=date('w');
		$yesterday=date('Y-m-d',strtotime('-1 day')).' 00:00:00';
		$client_list=array();
		$client_list1=Summarizing::getTop50PerDayAll();
		$client_list2=Summarizing::getTop50PerWeekAll();
		foreach($client_list1 as $item){
			$client_list[$item['uid']]=$item['uid'];
		}
		foreach($client_list2 as $item){
			$client_list[$item['uid']]=$item['uid'];
		}
		foreach($client_list as $guid){
			$data=Cache::get('client_'.$guid);
			$PlaceTop50PerDay=Summarizing::getPlaceTop50PerDay($guid);
			$PlaceTop50PerWeek=Summarizing::getPlaceTop50PerWeek($guid);
			$history=array(
				'time'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'PlaceTop50PerDay'=>$PlaceTop50PerDay,
				'PlaceTop50PerWeek'=>$PlaceTop50PerWeek
			);
			Clients::setHistory($guid,'rating',$history);
			if($w==0 && $PlaceTop50PerWeek<=50){
				$data['rating']['PlaceTop50PerWeek']=array(
					'place'=>$PlaceTop50PerWeek,
					'completed'=>1,
					'completedAt'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
					'awardRequest'=>0,
					'awardPublicId'=>'',
					'awardData'=>array()
				);
				$history=$data['rating']['PlaceTop50PerDay'];
				$history['name']='Попал в еженедельный рейтинг топ 50';
				Clients::setHistory($guid,'rating',$history);
			}
			if($PlaceTop50PerDay<=50 && $data['rating']['PlaceTop50PerDay']['awardRequest']==0){
				$data['rating']['PlaceTop50PerDay']=array(
					'place'=>$PlaceTop50PerDay,
					'completed'=>1,
					'completedAt'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
					'awardRequest'=>0,
					'awardPublicId'=>'',
					'awardData'=>array()
				);
				$history=$data['rating']['PlaceTop50PerDay'];
				$history['name']='Попал в ежедневный рейтинг топ 50';
				Clients::setHistory($guid,'rating',$history);
			}
			Cache::set('client_'.$guid,$data);
		}
		if($w==0){
			$sql='
				UPDATE {{clients_length}} 
				SET lengthweek=0
			';
			DB::exec($sql,$dataSql);
		}else{
			$sql='
				UPDATE {{clients_length}} 
				SET lengthday=0
			';
			DB::exec($sql,$dataSql);
		}
		return true;
	}*/
	public function getTop50PerDay($guid=''){
		if(isset($guid)){
			$client=Cache::get('client_'.$guid);
			if($client['rating']['PlaceTop50PerDay']['completed']==0){
				$PlaceTop50PerDay=Cache::get('PlaceTop50PerDay');
				$sql='SELECT lengthday, name FROM {{clients_length}} WHERE guid NOT IN (\''.implode('\',\'',$PlaceTop50PerDay).'\') ORDER BY lengthday DESC LIMIT 50';
				return DB::getAll($sql);
			}
		}
		$sql='SELECT lengthday, name FROM {{clients_length}} ORDER BY lengthday DESC LIMIT 50';
		return DB::getAll($sql);
	}
	public function getTop50PerWeek($guid=''){
		if(isset($guid)){
			$client=Cache::get('client_'.$guid);
			if($client['rating']['PlaceTop50PerWeek']['completed']==0){
				$PlaceTop50PerWeek=Cache::get('PlaceTop50PerWeek');
				$sql='SELECT lengthweek, name FROM {{clients_length}} WHERE guid NOT IN (\''.implode('\',\'',$PlaceTop50PerWeek).'\') ORDER BY lengthweek DESC LIMIT 50';
				return DB::getAll($sql);
			}
		}
		$sql='SELECT lengthweek, name FROM {{clients_length}} ORDER BY lengthweek DESC LIMIT 50';
		return DB::getAll($sql);
	}
	public function getTop50PerDayAll(){
		$sql='SELECT guid AS uid, lengthday AS length, row_number() OVER () AS place FROM {{clients_length}} ORDER BY lengthday DESC LIMIT 50';
		return DB::getAll($sql);
	}
	public function getTop50PerWeekAll(){
		$sql='SELECT guid AS uid, lengthweek AS length, row_number() OVER () AS place FROM {{clients_length}} ORDER BY lengthweek DESC LIMIT 50';
		return DB::getAll($sql);
	}
	public function getPlaceTop50PerDay($guid){
		if(isset($guid)){
			$client=Cache::get('client_'.$guid);
			if($client['rating']['PlaceTop50PerDay']['completed']==0){
				$PlaceTop50PerDay=Cache::get('PlaceTop50PerDay');
				$sql='
					WITH summary AS (
						SELECT guid, lengthday, ROW_NUMBER() OVER (ORDER BY lengthday DESC) AS r 
						FROM {{clients_length}} WHERE guid NOT IN (\''.implode('\',\'',$PlaceTop50PerDay).'\')) 
					SELECT r FROM summary WHERE guid=\''.$guid.'\'
				';
				$row=DB::getRow($sql);
				return $row['r'];
			}
		}
		$sql='
			WITH summary AS (
				SELECT guid, lengthday, ROW_NUMBER() OVER (ORDER BY lengthday DESC) AS r 
				FROM {{clients_length}}) 
			SELECT r FROM summary WHERE guid=\''.$guid.'\'
		';
		$row=DB::getRow($sql);
		return $row['r'];
	}
	public function getPlaceTop50PerWeek($guid){
		if(isset($guid)){
			$client=Cache::get('client_'.$guid);
			if($client['rating']['PlaceTop50PerWeek']['completed']==0){
				$PlaceTop50PerWeek=Cache::get('PlaceTop50PerWeek');
				$sql='
					WITH summary AS (
						SELECT guid, lengthweek, ROW_NUMBER() OVER (ORDER BY lengthweek DESC) AS r 
						FROM {{clients_length}} WHERE guid NOT IN (\''.implode('\',\'',$PlaceTop50PerWeek).'\')) 
					SELECT r FROM summary WHERE guid=\''.$guid.'\'
				';
				$row=DB::getRow($sql);
				return $row['r'];
			}
		}
		$sql='
			WITH summary AS (
				SELECT guid, lengthweek, ROW_NUMBER() OVER (ORDER BY lengthweek DESC) AS r 
				FROM {{clients_length}}) 
			SELECT r FROM summary WHERE guid=\''.$guid.'\'
		';
		$row=DB::getRow($sql);
		return $row['r'];
	}
	public static function setRating($guid,$length){
		if($guid && $length){
			$length=(int)$length;
			$data=Cache::get('client_'.$guid);
			//print $guid.' '.$length.' '.$data['bestlengthday'];
			$update=true; //false
			/*if($data['bestlengthday']<$length){
				$update=true;
				$data['bestlengthday']=$length;
			}
			if($data['bestlengthweek']<$length){
				$update=true;
				$data['bestlengthweek']=$length;
			}*/
			$data['bestlengthday']+=$length;
			$data['bestlengthweek']+=$length;
			if($update){
				$dataSql=array(
					'guid'=>$guid,
					'lengthday'=>$data['bestlengthday'],
					'lengthweek'=>$data['bestlengthweek']
				);
				$sql='
					UPDATE {{clients_length}} 
					SET lengthday=:lengthday,
						lengthweek=:lengthweek
					WHERE guid=:guid
				';
				DB::set($sql,$dataSql);
			}
			Cache::set('client_'.$guid,$data);
		}
	}
}
?>