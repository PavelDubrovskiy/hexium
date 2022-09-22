<?
class Clients{
	public static $data=array(
		'id'=>'',
		'guid'=>'',
		'lastLogin'=>'',
		'prevLogin'=>'',
		'longLogin'=>1,
		'createdAt'=>'',
		'lives'=>3,
		'totallength'=>0,
		'bestlengthday'=>0,
		'bestlengthweek'=>0,
		'comment'=>array(),
		'boosters'=>array(
			'magnet'=>0,
			'shield'=>0,
			'track'=>0,
		),
		'targets'=>array(),
		'tasks'=>array(),
		'cups'=>array(),
		'rating'=>array(
			'PlaceTop50PerDay'=>array('place'=>0,'completed'=>0,'completedAt'=>'','awardRequest'=>0,'awardPublicId'=>'','awardData'=>array()),
			'PlaceTop50PerWeek'=>array('place'=>0,'completed'=>0,'completedAt'=>'','awardRequest'=>0,'awardPublicId'=>'','awardData'=>array()),
		)
	);
	public static function getClient($guid,$wide=false,$post=array()){
		if($post['loading']){
			$statistic=Cache::get('statistic');
			if(isset($statistic['Кол-во входов в игру'])) $statistic['Кол-во входов в игру']++;
			else  $statistic['Кол-во входов в игру']=1;
			Cache::set('statistic',$statistic);
			
			$statistic=Cache::get('statistic'.date('Y-m-d'));
			if(isset($statistic['Кол-во входов в игру'])) $statistic['Кол-во входов в игру']++;
			else  $statistic['Кол-во входов в игру']=1;
			Cache::set('statistic'.date('Y-m-d'),$statistic);
		}
		$data=Cache::get('client_'.$guid);
		if(isset($data['id']) && !$wide){
			$data['prevLogin']=$data['lastLogin'];
			$data['lastLogin']=date_format(date_create(date('Y-m-d H:i:s')), 'c');
			$data=Clients::prepareTargets($data);
		}elseif(!isset($data['id'])){
			$sql='SELECT id, data FROM {{clients}} WHERE guid=\''.$guid.'\'';
			$row=DB::getRow($sql);
			if(isset($row['data'])){
				$row['data']=json_decode($row['data'],true);
				$data=$row['data']['list'];
				if(!$wide){
					$data['prevLogin']=$data['lastLogin'];
					$data['lastLogin']=date_format(date_create(date('Y-m-d H:i:s')), 'c');
					$data=Clients::prepareTargets($data);
				}
			}
			if(!isset($data['id']) && !$wide){
				$id=Clients::add($guid);
				$data=Clients::$data;
				$data['id']=$id;
				$data['guid']=$guid;
				$data['lastLogin']=date_format(date_create(date('Y-m-d H:i:s')), 'c');
				$data['createdAt']=date_format(date_create(date('Y-m-d H:i:s')), 'c');
				$data=Clients::prepareTargets($data);
				$data=Clients::prepareCups($data);
				Clients::edit($data);
				Cache::set('client_'.$guid,$data);
				$client_list=Cache::get('client_list');
				$client_list[$guid]=$guid;
				Cache::set('client_list',$client_list);
			}
		}
		if(!$wide){
			$data=Cups::checkPersistent($data);
			$data=Cups::checkSupercup($data);
			if($post['loading']){
				$data=Clients::prepareCups($data);
				Clients::edit($data);
				Cache::set('client_'.$guid,$data);
				unset($data['history']);
			}
		}else{
			unset($data['targets']);
			unset($data['tasks']);
		}
		return $data;
	}
	public static function prepareTargets($data){
		$settings=Settings::getSettings();
		$i=0;
		if(!is_array($settings)) $settings=json_decode($settings,true);
		foreach($settings['targets'] as $key=>$item){
			if(!isset($data['targets'][$item])){
				$data['targets'][$item]=array('target'=>(int)$item,'levelId'=>$i*2+1,'completed'=>0,'completedAt'=>'','awardRequest'=>0,'awardPublicId'=>'','awardData'=>array());
			}else{
				$data['targets'][$item]['levelId']=$i*2+1;
			}
			$i++;
		}
		return $data;
	}
	public static function prepareCups($data){
		foreach(Options::$data['cups'] as $key=>$item){
			if(!isset($data['cups'][$key])){
				$data['cups'][$key]=array(
					'name'=>$item['name'],
					'stars'=>$item['stars'],
					'target'=>$item['target'],
					'completed'=>0,
					'completedAt'=>'',
					'completedResult'=>'',
					'awardRequest'=>0,
					'awardPublicId'=>'',
					'awardData'=>array()
				);
			}else{
				$data['cups'][$key]['target']=$item['target'];
			}
		}
		return $data;
	}
	public static function getMissions($guid){
		$data=Cache::get('client_'.$guid);
		$data=Clients::prepareTasks($data);
		Clients::edit($data);
		Cache::set('client_'.$guid,$data);
		return $data;
	}
	public static function prepareTasks($data){
		$token=Megafon::getToken();
		$completedTasks=Megafon::getCompletedTasks($token['access_token'],$data['guid']);
		$tasksList=Cache::get('megafon_TasksList');
		foreach($tasksList['result'] as $key=>$item){
			$name='';
			foreach(Options::$data['tasks'] as $keyTask=>$itemTask){
				if($itemTask['publicId']==$item['publicId']) $name=$keyTask;
			}
			if(!isset($data['tasks'][$item['publicId']]) && $name){
				if(isset($completedTasks[$item['publicId']]) && $completedTasks[$item['publicId']]['usedInCampaign']!=''){
					$data['tasks'][$name]=array('awardRequest'=>2,'completedAt'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),'awardData'=>'','taskData'=>$item,'usedInCampaign'=>$completedTasks[$item['publicId']]['usedInCampaign']);
				}elseif(isset($completedTasks[$item['publicId']])){
					$data['tasks'][$name]=array('awardRequest'=>0,'completedAt'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),'awardData'=>'','taskData'=>$item,'usedInCampaign'=>'');
				}
			}
		}
		return $data;
	}
	public static function clearClient($guid){
		/*
		Cache::del('client_'.$guid);
		Cache::del('history_'.$guid);
		$client_list=Cache::get('client_list');
		unset($client_list[$guid]);
		Cache::set('client_list',$client_list);
		$sql='DELETE FROM {{clients}} WHERE guid=\''.$guid.'\'';
		DB::exec($sql);
		$sql='DELETE FROM {{clients_length}} WHERE guid=\''.$guid.'\'';
		DB::exec($sql);
		//$sql='DELETE FROM {{history}} WHERE guid=\''.$guid.'\'';
		//DB::exec($sql);
		return true;
		*/
	}
	public static function clearAllClients(){
		/*
		$client_list=Cache::get('client_list');
		foreach($client_list as $guid=>$item){
			Clients::clearClient($guid);
		}
		Cache::del('statistic');
		return true;
		*/
	}
	public static function add($guid){
		$dataSql=array(
			'guid'=>$guid,
			'data'=>json_encode(array('list'=>array())),
		);
		$sql='
			INSERT INTO {{clients}}	(guid, data, cdate)
			VALUES (:guid, :data, NOW())
		';
		$id=DB::set($sql,$dataSql);
		$dataSql=array(
			'guid'=>$guid,
			'name'=>$id
		);
		$sql='
			INSERT INTO {{clients_length}} (guid, lengthday, lengthweek, name)
			VALUES (:guid, 0, 0,:name)
		';
		DB::set($sql,$dataSql);
		//$sql='INSERT INTO {{history}} (guid) VALUES (:guid)';
		//DB::set($sql,$dataSql);
		return $id;
	}
	public static function edit($data){
		$dataSql=array(
			'guid'=>$data['guid'],
			'data'=>json_encode(array('list'=>$data)),
		);
		$sql='
			UPDATE {{clients}} SET data=:data
			WHERE guid=:guid
		';
		DB::set($sql,$dataSql);
	}
	public static function setHistory($guid,$type,$post){
		$data=Cache::get('history_'.$guid);
		if(!$data){
			///
			if(!$data) $data=array();
		}
		if(!isset($data[$type]))$data[$type]=array();
		$data[$type][]=$post;
		Cache::set('history_'.$guid,$data);
	}
	public static function getHistory($guid){
		return Cache::get('history_'.$guid);
	}
	public static function setStart($post){
		$data=Cache::get('client_'.$post['guid']);
		if($data['lives']*1>0){
			$data['lives']--;
			if(isset($post['magnet']) && $post['magnet'] && $data['boosters']['magnet']>0) $data['boosters']['magnet']--;
			if(isset($post['shield']) && $post['shield'] && $data['boosters']['shield']>0) $data['boosters']['shield']--;
			
			$statistic=Cache::get('statistic');
			if(!isset($statistic['Игры'][date('Y-m-d')])) $statistic['Игры'][date('Y-m-d')]=array();
			if(!isset($statistic['Игры'][date('Y-m-d')][$post['guid']])) $statistic['Игры'][date('Y-m-d')][$post['guid']]=0;
			$statistic['Игры'][date('Y-m-d')][$post['guid']]++;
			if(!isset($statistic['ИгрыИгрока'][$post['guid']])) $statistic['ИгрыИгрока'][$post['guid']]=array();
			if(!isset($statistic['ИгрыИгрока'][$post['guid']][date('Y-m-d')])) $statistic['ИгрыИгрока'][$post['guid']][date('Y-m-d')]=0;
			$statistic['ИгрыИгрока'][$post['guid']][date('Y-m-d')]++;
			Cache::set('statistic',$statistic);
			
			$statistic=Cache::get('statistic'.date('Y-m-d'));
			if(!isset($statistic['Игры'][date('Y-m-d')])) $statistic['Игры'][date('Y-m-d')]=array();
			if(!isset($statistic['Игры'][date('Y-m-d')][$post['guid']])) $statistic['Игры'][date('Y-m-d')][$post['guid']]=0;
			$statistic['Игры'][date('Y-m-d')][$post['guid']]++;
			if(!isset($statistic['ИгрыИгрока'][$post['guid']])) $statistic['ИгрыИгрока'][$post['guid']]=array();
			if(!isset($statistic['ИгрыИгрока'][$post['guid']][date('Y-m-d')])) $statistic['ИгрыИгрока'][$post['guid']][date('Y-m-d')]=0;
			$statistic['ИгрыИгрока'][$post['guid']][date('Y-m-d')]++;
			Cache::set('statistic'.date('Y-m-d'),$statistic);
			
			Clients::edit($data);
			Cache::set('client_'.$post['guid'],$data);
			//unset($data['history']);
			return $data;
		}
		return false;
	}
	public static function setWon($post){
		Summarizing::setRating($post['guid'],$post['length']);
		$data=Cache::get('client_'.$post['guid']);
		if(isset($data['rewards'][$post['reward']]) && $data['rewards'][$post['reward']]==0){
			Clients::edit($data);
			Cache::set('client_'.$post['guid'],$data);
		}
		unset($data['history']);
		return $data;
	}
	public static function setFail($post){
		Summarizing::setRating($post['guid'],$post['length']);
		$data=Cache::get('client_'.$post['guid']);
		$data['totallength']+=$post['length'];
		$data=Cups::checkCups($data,$post);
		Clients::edit($data);
		Cache::set('client_'.$post['guid'],$data);
		unset($data['history']);
		return $data;
	}
	public static function setComment($post){
		$data=Cache::get('client_'.$post['guid']);
		if(isset($post['comment']) && is_array($post['comment'])){
			$data['comment']=$post['comment'];
		}elseif(isset($post['comment']) && trim($post['comment'])){
			$data['comment'][]=$post['comment'];
		}else{
			$data['comment']=array();
		}
		Clients::edit($data);
		Cache::set('client_'.$post['guid'],$data);
	}
	public static function lives($post){
		$data=Cache::get('client_'.$post['uid']);
		if(isset($post['lives']) && is_numeric($post['lives'])) $data['lives']=$data['lives']+round($post['lives']);
		if(isset($post['magnet']) && is_numeric($post['magnet'])) $data['boosters']['magnet']=$data['boosters']['magnet']+round($post['magnet']);
		if(isset($post['shield']) && is_numeric($post['shield'])) $data['boosters']['shield']=$data['boosters']['shield']+round($post['shield']);
		Clients::edit($data);
		Cache::set('client_'.$post['uid'],$data);
		return true;
	}
}
?>