<?
class Megafon{
	public static function getToken(){
		$data=Cache::get('megafon_token');
		if(isset($data['expdate']) && $data['expdate']>date_format(date_create(date('Y-m-d H:i:s')), 'c')){
			return $data;
		}else{
			$url = MEGAFON_tokensource;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,'username='.MEGAFON_username.'&password='.MEGAFON_password.'&grant_type=password&client_id='.MEGAFON_client_id.'&client_secret='.MEGAFON_client_secret);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$data = json_decode(curl_exec($ch),true);
			curl_close ($ch);
			if(isset($data['access_token'])){
				$data['expdate']=date_format(date_create(date('Y-m-d H:i:s',strtotime('+ '.$data['expires_in'].' seconds'))), 'c');
				Cache::set('megafon_token',$data);
				return $data;
			}
		}
	}
	public static function getTasksList($token,$refresh=0){
		$data=Cache::get('megafon_TasksList');
		if($data && !$refresh){
			return $data;
		}else{
			$url = MEGAFON_baseUrl.'/taskslist';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			$post=array('campaignId'=>MEGAFON_campaignId);
			$post = json_encode($post);
			$authorization = "Authorization: Bearer ".$token; 
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept: application/json',$authorization ));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$data = json_decode(curl_exec($ch),true);
			curl_close ($ch);
			if(isset($data['result'])){
				Cache::set('megafon_TasksList',$data);
				return $data;
			}
		}
	}
	public static function getAwardsList($token,$refresh=0){
		$data=Cache::get('megafon_AwardsList');
		if($data && !$refresh){
			return $data;
		}else{
			$url = MEGAFON_baseUrl.'/awardslist';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			$post=array('campaignId'=>MEGAFON_campaignId);
			$post = json_encode($post);
			$authorization = "Authorization: Bearer ".$token; 
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept: application/json',$authorization ));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$data = json_decode(curl_exec($ch),true);
			curl_close ($ch);
			if(isset($data['result'])){
				Cache::set('megafon_AwardsList',$data);
				return $data;
			}
		}
	}
	public static function getAward($token,$post){
		$data=Cache::get('client_'.$post['guid']);
		if(isset($data['targets'][$post['target']]['levelId']) && (!$data['targets'][$post['target']]['completed'] || isset($data['targets'][$post['target']]['excludeOfferId']))){
			$url = MEGAFON_baseUrl.'/playerawards';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			$levelId=$data['targets'][$post['target']]['levelId'];
			if($levelId==61) $levelId=60;
			if(isset($data['targets'][$post['target']]['excludeOfferId']) && $data['targets'][$post['target']]['excludeOfferId']){
				$postData=array('playerUid'=>$post['guid'],'campaignId'=>MEGAFON_campaignId,'levelId'=>$levelId,'excludeOfferId'=>$data['targets'][$post['target']]['excludeOfferId']);
				unset($data['targets'][$post['target']]['excludeOfferId']);
			}else{
				$postData=array('playerUid'=>$post['guid'],'campaignId'=>MEGAFON_campaignId,'levelId'=>$levelId);
			}
			$postData = json_encode($postData);
			$authorization = "Authorization: Bearer ".$token; 
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept: application/json',$authorization ));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$segments = json_decode(curl_exec($ch),true);
			curl_close ($ch);
			$publicId=$segments['segments'][0];
			if($publicId){
				$data['targets'][$post['target']]['awardPublicId']=$publicId;
				$award=0;
				$awards=Megafon::getAwardsList($token);
				if(isset($awards['result'])){
					foreach($awards['result'] as $item){
						if($item['publicId']==$publicId) $award=$item;
					}
				}
				if(!$award){
					$awards=Megafon::getAwardsList($token,1);
					if(isset($awards['result'])){
						foreach($awards['result'] as $item){
							if($item['publicId']==$publicId) $award=$item;
						}
					}
				}
				if($award){
					if($award['type']=='coupon'){
						$award['promo']=Options::getPromo($post['guid'],$publicId);
					}
					$data['targets'][$post['target']]['awardData']=$award;
					$data['targets'][$post['target']]['completed']=1;
					$data['targets'][$post['target']]['completedAt']=date_format(date_create(date('Y-m-d H:i:s')), 'c');
					Cache::set('client_'.$post['guid'],$data);
					return $award;
				}
			}
		}
		return '';
	}
	public static function setAward($token,$post){
		$data=Cache::get('client_'.$post['guid']);
		if(isset($data['targets'][$post['target']]['awardPublicId']) && !$data['targets'][$post['target']]['awardRequest']){
			if($post['awardRequest']==2){
				$data['targets'][$post['target']]['awardRequest']=2;
				Cache::set('client_'.$post['guid'],$data);
				return array(
					"status"=>202,
					"description"=>"Award not set",
					"action"=>"nothing"
				);
			}elseif($post['awardRequest']==1){
				if($data['targets'][$post['target']]['awardData']['sendNotify']==true){
					$url = MEGAFON_baseUrl.'/awardactivation';
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,$url);
					$postData=array('playerUid'=>$post['guid'],'campaignId'=>MEGAFON_campaignId,'publicId'=>$data['targets'][$post['target']]['awardPublicId']);
					$postData = json_encode($postData);
					$authorization = "Authorization: Bearer ".$token; 
					curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept: application/json', $authorization ));
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
					$response = json_decode(curl_exec($ch),true);
					$info = curl_getinfo($ch);
					curl_close ($ch);
					$guid=$post['guid'];
					$history=array(
						'time'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
						'status'=>$info['http_code'],
						'request'=>'awardactivation',
						'type'=>'award',
						'params'=>array('playerUid'=>$post['guid'],'campaignId'=>MEGAFON_campaignId,'publicId'=>$data['targets'][$post['target']]['awardPublicId']),
						'response'=>$response
					);
					Clients::setHistory($guid,'requests',$history);
					if($info['http_code']==200 || $info['http_code']==202){
						$data['targets'][$post['target']]['awardRequest']=1;
						Cache::set('client_'.$post['guid'],$data);
						return array(
							"status"=>202,
							"description"=>"Award set",
							"action"=>"nothing"
						);
					}else{
						if($info['http_code']==422){
							$data['targets'][$post['target']]['excludeOfferId']=$data['targets'][$post['target']]['awardPublicId'];
							Cache::set('client_'.$post['guid'],$data);
						}
						return array(
							"status"=>$info['http_code'],
							"error"=>$response
						);
					}
				}else{
					if(isset(Options::$awards['boosters'][$data['targets'][$post['target']]['awardData']['name']])){
						$data['lives']+=Options::$awards['boosters'][$data['targets'][$post['target']]['awardData']['name']]['award']['lives'];
						$data['boosters']['magnet']+=Options::$awards['boosters'][$data['targets'][$post['target']]['awardData']['name']]['award']['magnet'];
						$data['boosters']['shield']+=Options::$awards['boosters'][$data['targets'][$post['target']]['awardData']['name']]['award']['shield'];
						$data['targets'][$post['target']]['awardRequest']=1;
						Cache::set('client_'.$post['guid'],$data);
						return array(
							"status"=>202,
							"description"=>"Award set",
							"action"=>"update",
							"lives"=>$data['lives'],
							"magnet"=>$data['boosters']['magnet'],
							"shield"=>$data['boosters']['shield'],
							"track"=>$data['boosters']['track']
						);
					}else{
						$data['targets'][$post['target']]['awardRequest']=1;
						Cache::set('client_'.$post['guid'],$data);
						return array(
							"status"=>202,
							"description"=>"Award set",
							"action"=>"nothing"
						);
					}
				}
			}
		}
		return array(
			"status"=>409,
			"error"=>array(
				"errorCode"=>"Conflict",
				"userMessage"=>"Приз уже выбыл выдан"
			)
		);
	}
	public static function getCompletedTasks($token,$guid){
		$data=array();
		$url = MEGAFON_baseUrl.'/completedtasks';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		$postData=array('playerUid'=>$guid,'campaignId'=>MEGAFON_campaignId);
		$postData = json_encode($postData);
		$authorization = "Authorization: Bearer ".$token; 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept: application/json',$authorization ));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$tasks = json_decode(curl_exec($ch),true);
		$info = curl_getinfo($ch);
		curl_close ($ch);
		$history=array(
			'time'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
			'status'=>$info['http_code'],
			'request'=>'completedtasks',
			'params'=>array('playerUid'=>$guid,'campaignId'=>MEGAFON_campaignId),
			'response'=>$tasks
		);
		Clients::setHistory($guid,'requests',$history);
		if(isset($tasks['completedTasks']) && is_array($tasks['completedTasks'])){
			foreach($tasks['completedTasks'] as $item){
				$data[$item['publicId']]=$item;
			}
		}
		return $data;
	}
	public static function setGoods($token,$post){
		$data=Cache::get('client_'.$post['guid']);
		if(isset($data['id']) && isset(Options::$data['goods'][$post['target']]['publicId'])){
			$url = MEGAFON_baseUrl.'/awardactivation';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			$postData=array('playerUid'=>$post['guid'],'campaignId'=>MEGAFON_campaignId,'publicId'=>Options::$data['goods'][$post['target']]['publicId']);
			$postData = json_encode($postData);
			$authorization = "Authorization: Bearer ".$token; 
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept: application/json',$authorization ));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$response = json_decode(curl_exec($ch),true);
			$info = curl_getinfo($ch);
			curl_close ($ch);
			$guid=$post['guid'];
			$history=array(
				'time'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'status'=>$info['http_code'],
				'request'=>'awardactivation',
				'type'=>'payment',
				'params'=>array('playerUid'=>$post['guid'],'campaignId'=>MEGAFON_campaignId,'publicId'=>Options::$data['goods'][$post['target']]['publicId']),
				'response'=>$response
			);
			Clients::setHistory($guid,'requests',$history);
			if($info['http_code']==200 || $info['http_code']==202){
				$data['lives']+=Options::$data['goods'][$post['target']]['award']['lives'];
				$data['boosters']['magnet']+=Options::$data['goods'][$post['target']]['award']['magnet'];
				$data['boosters']['shield']+=Options::$data['goods'][$post['target']]['award']['shield'];
				Cache::set('client_'.$post['guid'],$data);
				$history=array(
					'time'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
					'status'=>'Done',
					'data'=>Options::$data['goods'][$post['target']]
				);
				Clients::setHistory($guid,'purchases',$history);
				return array(
					"status"=>202,
					"description"=>"Award set",
					"action"=>"update",
					"lives"=>$data['lives'],
					"magnet"=>$data['boosters']['magnet'],
					"shield"=>$data['boosters']['shield'],
					"track"=>$data['boosters']['track'],
				);
			}else{
				$history=array(
					'time'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
					'status'=>'Fail',
					'data'=>Options::$data['goods'][$post['target']],
					'error'=>'Server response status '.$info['http_code']
				);
				Clients::setHistory($guid,'purchases',$history);
				return array(
					"status"=>$info['http_code'],
					"error"=>$response
				);
			}
		}
		return array(
			"status"=>500,
			"error"=>array(
				"errorCode"=>"Server error",
				"userMessage"=>"Непредвиденная ошибка"
			)
		);
	}
	public static function setTasks($token,$post){
		$data=Cache::get('client_'.$post['guid']);
		if(isset($data['id']) && isset(Options::$data['tasks'][$post['target']]['publicId'])){
			$url = MEGAFON_baseUrl.'/marktask';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			$postData=array('playerUid'=>$post['guid'],'campaignId'=>MEGAFON_campaignId,'publicId'=>Options::$data['tasks'][$post['target']]['publicId']);
			$postData = json_encode($postData);
			$authorization = "Authorization: Bearer ".$token;
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept: application/json',$authorization ));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$response = json_decode(curl_exec($ch),true);
			$info = curl_getinfo($ch);
			curl_close ($ch);
			$guid=$post['guid'];
			$history=array(
				'time'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'status'=>$info['http_code'],
				'request'=>'marktask',
				'params'=>array('playerUid'=>$post['guid'],'campaignId'=>MEGAFON_campaignId,'publicId'=>Options::$data['tasks'][$post['target']]['publicId']),
				'response'=>$response
			);
			Clients::setHistory($guid,'requests',$history);
			if($info['http_code']==200 || $info['http_code']==202 || $info['http_code']==204){
				$data['lives']+=Options::$data['tasks'][$post['target']]['award']['lives'];
				$data['boosters']['magnet']+=Options::$data['tasks'][$post['target']]['award']['magnet'];
				$data['boosters']['shield']+=Options::$data['tasks'][$post['target']]['award']['shield'];
				Cache::set('client_'.$post['guid'],$data);
				return array(
					"status"=>202,
					"description"=>"Award set",
					"action"=>"update",
					"lives"=>$data['lives'],
					"magnet"=>$data['boosters']['magnet'],
					"shield"=>$data['boosters']['shield'],
					"track"=>$data['boosters']['track'],
				);
			}else{
				return array(
					"status"=>$info['http_code'],
					"error"=>$response
				);
			}
		}
		return array(
			"status"=>500,
			"error"=>array(
				"errorCode"=>"Server error",
				"userMessage"=>"Непредвиденная ошибка"
			)
		);
	}
	public static function setRating($token,$post){
		$data=Cache::get('client_'.$post['guid']);
		if(isset($data['id']) && $data['rating'][$post['target']]['completed']==1 && $data['rating'][$post['target']]['awardRequest']!=1){
			if($post['awardRequest']==2){
				$data['rating'][$post['target']]['awardRequest']=2;
				Cache::set('client_'.$post['guid'],$data);
				return array(
					"status"=>202,
					"description"=>"Award not set",
					"action"=>"nothing"
				);
			}elseif($post['awardRequest']==1){
				$url = MEGAFON_baseUrl.'/awardactivation';
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,$url);
				$postData=array('playerUid'=>$post['guid'],'campaignId'=>MEGAFON_campaignId,'publicId'=>Options::$data['ratings'][$post['target']]['publicId']);
				$postData = json_encode($postData);
				$authorization = "Authorization: Bearer ".$token; 
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept: application/json', $authorization ));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				$response = json_decode(curl_exec($ch),true);
				$info = curl_getinfo($ch);
				curl_close ($ch);
				$guid=$post['guid'];
				$history=array(
					'time'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
					'status'=>$info['http_code'],
					'request'=>'awardactivation',
					'type'=>'award',
					'params'=>array('playerUid'=>$post['guid'],'campaignId'=>MEGAFON_campaignId,'publicId'=>Options::$data['ratings'][$post['target']]['publicId']),
					'response'=>$response
				);
				Clients::setHistory($guid,'requests',$history);
				if($info['http_code']==200 || $info['http_code']==202){
					$data['rating'][$post['target']]['awardRequest']=1;
					$data['rating'][$post['target']]['awardPublicId']=Options::$data['ratings'][$post['target']]['publicId'];
					$data['rating'][$post['target']]['awardData']=Options::$data['ratings'][$post['target']]['award'];
					Clients::edit($data);
					Cache::set('client_'.$post['guid'],$data);
					$history=$data['rating'][$post['target']];
					if($post['target']=='PlaceTop50PerDay') $history['name']='Получил подарок за ежедневный рейтинг топ 50';
					else $history['name']='Получил подарок за еженедельный рейтинг топ 50';
					Clients::setHistory($guid,'rating',$history);
					return array(
						"status"=>202,
						"description"=>"Award set",
						"action"=>"nothing"
					);
				}else{
					return array(
						"status"=>$info['http_code'],
						"error"=>$response
					);
				}
			}
		}
		return array(
			"status"=>409,
			"error"=>array(
				"errorCode"=>"Conflict",
				"userMessage"=>"Приз уже выбыл выдан"
			)
		);
	}
}
?>