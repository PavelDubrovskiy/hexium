<?php
class Settings{
	public function setSettings($post){
		$isAllRequired=true;
		if(!isset($post['targets']) || !is_array($post['targets']) || empty($post['targets'])) $isAllRequired=false;
		if(!isset($post['marathon']) || !is_array($post['marathon']) || count($post['marathon'])!=3) $isAllRequired=false;
		if(!isset($post['maxsnake']) || !is_array($post['maxsnake']) || count($post['maxsnake'])!=3) $isAllRequired=false;
		if(!isset($post['invulnerable']) || !is_array($post['invulnerable']) || count($post['invulnerable'])!=3) $isAllRequired=false;
		if($isAllRequired){
			$data=Cache::get('client_settings');
			if(!$data){
				$sql='SELECT data FROM {{settings}} WHERE id=1';
				$data=DB::getOne($sql);
			}
			if(!$data){
				$dataSql=array('data'=>json_encode($post));
				$sql='
					INSERT INTO {{settings}} (data)
					VALUES (:data)
				';
				DB::set($sql,$dataSql);
			}else{
				$dataSql=array('data'=>json_encode($post));
				$sql='
					UPDATE {{settings}} 
					SET data=:data
					WHERE id=1
				';
				DB::set($sql,$dataSql);
			}
			Cache::set('client_settings',$post);
			return 1;
		}
	}
	public function getSettings(){
		$data=Cache::get('client_settings');
		if(!$data){
			$sql='SELECT data FROM {{settings}} WHERE id=1';
			$data=DB::getOne($sql);
		}
		return $data;
	}
}
?>