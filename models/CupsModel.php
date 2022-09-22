<?
class Cups{
	public static function checkCups($data,$post){
		$data=Cups::checkMarathon($data,$data['totallength']);
		$data=Cups::checkMaxsnake($data,$post['maxsnake']);
		$data=Cups::checkInvulnerable($data,$post['invulnerable']);
		$data=Cups::checkSecretcup($data,$post['secretcup']);
		return $data;
	}
	public static function checkMarathon($data,$maxlength){
		for($i=1;$i<4;$i++){
			if($maxlength>=Options::$data['cups']['marathon'.$i]['target'] && !$data['cups']['marathon'.$i]['awardRequest']){
				$data['cups']['marathon'.$i]['completed']=1;
				$data['cups']['marathon'.$i]['completedAt']=date_format(date_create(date('Y-m-d H:i:s')), 'c');
				$data['cups']['marathon'.$i]['completedResult']=(int)$maxlength;
				break;
			}
		}
		return $data;
	}
	public static function checkMaxsnake($data,$maxsnake){
		for($i=1;$i<4;$i++){
			if($maxsnake>=Options::$data['cups']['maxsnake'.$i]['target'] && !$data['cups']['maxsnake'.$i]['awardRequest']){
				$data['cups']['maxsnake'.$i]['completed']=1;
				$data['cups']['maxsnake'.$i]['completedAt']=date_format(date_create(date('Y-m-d H:i:s')), 'c');
				$data['cups']['maxsnake'.$i]['completedResult']=(int)$maxsnake;
				break;
			}
		}
		return $data;
	}
	public static function checkInvulnerable($data,$maxlength){
		for($i=1;$i<4;$i++){
			if($maxlength>=Options::$data['cups']['invulnerable'.$i]['target'] && !$data['cups']['invulnerable'.$i]['awardRequest']){
				$data['cups']['invulnerable'.$i]['completed']=1;
				$data['cups']['invulnerable'.$i]['completedAt']=date_format(date_create(date('Y-m-d H:i:s')), 'c');
				$data['cups']['invulnerable'.$i]['completedResult']=(int)$maxlength;
				break;
			}
		}
		return $data;
	}
	public static function checkSecretcup($data,$secretcup){
		if(!$data['cups']['secretcup'.$secretcup]['completed'] && !$data['cups']['secretcup'.$secretcup]['awardRequest']){
			$data['cups']['secretcup'.$secretcup]['completed']=1;
			$data['cups']['secretcup'.$secretcup]['completedAt']=date_format(date_create(date('Y-m-d H:i:s')), 'c');
			$data['cups']['secretcup'.$secretcup]['completedResult']='Done';
		}
		return $data;
	}
	public static function checkPersistent($data){
		for($i=1;$i<4;$i++){
			if($data['longLogin']==Options::$data['cups']['persistent'.$i]['target'] && !$data['cups']['secretcup'.$secretcup]['awardRequest']){
				$data['cups']['persistent'.$i]['completed']=1;
				$data['cups']['persistent'.$i]['completedAt']=date_format(date_create(date('Y-m-d H:i:s')), 'c');
				$data['cups']['persistent'.$i]['completedResult']='Заходить в игру '.Options::$data['cups']['persistent'.$i]['target'].' дней подряд.';
			}
		}
		return $data;
	}
	public static function checkSupercup($data){
		$check=1;
		foreach(Options::$data['cups'] as $key=>$item){
			if($key!='supercup' && !$data['cups'][$key]['awardRequest']){
				$check=0;
			}
		}
		if($check){
			$data['cups']['supercup']['completed']=1;
			$data['cups']['supercup']['completedAt']=date_format(date_create(date('Y-m-d H:i:s')), 'c');
			$data['cups']['supercup']['completedResult']='Done';
		}
		return $data;
	}
	public static function setAward($post){
		$data=Cache::get('client_'.$post['guid']);
		if(isset($data['cups'][$post['target']]['awardPublicId']) && $data['cups'][$post['target']]['completed'] && !$data['cups'][$post['target']]['awardRequest']){
			$data['lives']+=Options::$data['cups'][$post['target']]['award']['lives'];
			$data['boosters']['magnet']+=Options::$data['cups'][$post['target']]['award']['magnet'];
			$data['boosters']['shield']+=Options::$data['cups'][$post['target']]['award']['shield'];
			$data['boosters']['track']+=Options::$data['cups'][$post['target']]['award']['track'];
			$data['cups'][$post['target']]['awardRequest']=1;
			$data['cups'][$post['target']]['awardData']=Options::$data['cups'][$post['target']]['award'];
			Clients::edit($data);
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
		}
		return array(
			"status"=>409,
			"error"=>array(
				"errorCode"=>"Conflict",
				"userMessage"=>"Приз уже выбыл выдан или кубок не взят"
			)
		);
	}
	public static function setSupercup($post){
		$data=Cache::get('client_'.$post['guid']);
		if(isset($data['id']) && $data['cups']['supercup']['awardRequest']!=1){
			
			$data['cups']['supercup']['awardRequest']=1;
			$data['cups']['supercup']['awardData']=Options::$data['cups']['supercup']['award'];
			$data['cups']['supercup']['awardPublicId']=Options::$data['cups']['supercup']['publicId'];
			$data['cups']['supercup']['awardData']['promo']=Options::getPromo($post['guid'],Options::$data['cups']['supercup']['publicId']);
			Cache::set('client_'.$post['guid'],$data);
			return array(
				"status"=>202,
				"description"=>"Award set",
				"action"=>"nothing",
				"awardData"=>$data['cups']['supercup']['awardData']
			);
			/*
			$data['cups']['supercup']['awardRequest']=1;
			$data['cups']['supercup']['awardData']=array(
				'name'=>'Приз жизни +10, магниты +10, защита +10',
				'award'=>array('lives'=>10,'magnet'=>10,'shield'=>10)
			);
			$data['lives']+=10;
			$data['boosters']['magnet']+=10;
			$data['boosters']['shield']+=10;
			Clients::edit($data);
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
			*/
		}else{
			return array(
				"status"=>409,
				"error"=>array(
					"errorCode"=>"Conflict",
					"userMessage"=>"Приз уже выбыл выдан"
				)
			);
		}
	}
}
?>