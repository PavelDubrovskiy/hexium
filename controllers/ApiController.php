<?
class ApiController{
	function __construct(){
		/*$body = file_get_contents('php://input');
		ob_start();
			print '--------------'.date('H:i:s d.m.Y').'-------------'."\n";
			print 'POST:'."\n";
			print_r($_POST);
			print 'GET:'."\n";
			print_r($_GET);
			print '-------------- BODY php://input ---------------'."\n"."\n"."\n";
			print $body;
			print '-------------- /BODY php://input --------------'."\n"."\n"."\n";
			print 'REMOTE_ADDR: '.$_SERVER['REMOTE_ADDR']."\n";
			print 'REQUEST_URI: '.$_SERVER['REQUEST_URI']."\n";
			print 'HTTP_REFERER: '.$_SERVER['HTTP_REFERER']."\n";
			print 'PHP_AUTH_USER: '.$_SERVER['PHP_AUTH_USER']."\n";
			print 'PHP_AUTH_PW: '.$_SERVER['PHP_AUTH_PW']."\n";
			print 'HTTP_AUTHORIZATION: '.$_SERVER['HTTP_AUTHORIZATION']."\n";
			print 'REMOTE_USER: '.$_SERVER['REMOTE_USER']."\n";
			print 'REQUEST_METHOD: '.$_SERVER['REQUEST_METHOD']."\n";
			print 'HTTP_ORIGIN: '.$_SERVER['HTTP_ORIGIN']."\n";
			print '-----------------------------------------'."\n"."\n"."\n";
			$str=ob_get_contents();
		ob_end_clean();
		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/access.log',$str,FILE_APPEND);*/
		if($_SERVER['REQUEST_METHOD']=='OPTIONS') die('pong');
		DB::escapePost();
		DB::escapeGet();
		if(!in_array(Funcs::$uri[1],array('version','summarizing','summarizingTop50','reportXLSX','lives','info','levels','tasks','requests','purchases','rating',
			'setSettings','getSettings','getTasksList','getAwardsList','clearAllClients','ratingListPerDay','ratingListPerWeek','coupons','getHash'))){
			Api::checkAppkey();
		}elseif(in_array(Funcs::$uri[1],array('setSettings','getSettings'))){
			Api::checkSettingsAppkey();
		}
	}
	/**FRONTEND**/
	function version(){
		header('Content-Type: application/json; charset=utf-8');
		$data=array(
			'Data'=>array('Version'=>'1.1.0'),
			'ErrorMsg'=>'',
			'Success'=>'true',
			'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
			'RequestID'=>Api::guidv4()
		);
		print json_encode($data);
	}
	function getClient(){
		$data=Clients::getClient($_GET['guid'],false,$_GET);
		if($data){
			header('Content-Type: application/json; charset=utf-8');
			$data=array(
				'Data'=>$data,
				'ErrorMsg'=>'',
				'Success'=>'true',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			);
			print json_encode($data);
		}else{
			header('Content-Type: application/json; charset=utf-8');
			header("HTTP/1.0 404 Not Found");
			print json_encode(array(
				'Success'=>'false',
				'ErrorMsg'=>'Check client guid',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			));
			die;
		}		
	}
	function clearClient(){
		$data=Clients::clearClient($_GET['guid']);
		if($data){
			header('Content-Type: application/json; charset=utf-8');
			$data=array(
				'Data'=>$data,
				'ErrorMsg'=>'',
				'Success'=>'true',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			);
			print json_encode($data);
		}else{
			header('Content-Type: application/json; charset=utf-8');
			header("HTTP/1.0 404 Not Found");
			print json_encode(array(
				'Success'=>'false',
				'ErrorMsg'=>'Check client guid',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			));
			die;
		}		
	}
	function clearAllClients(){
		if($_GET['guid']=='89b21c3d-483f-42c4-a010-c3e9b22461c6fff' && $_GET['checksum']=='23819250b5e01984d23c9ebb7a1110289e7a4767fff'){
			$data=Clients::clearAllClients();
			if($data){
				header('Content-Type: application/json; charset=utf-8');
				$data=array(
					'Data'=>$data,
					'ErrorMsg'=>'',
					'Success'=>'true',
					'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
					'RequestID'=>Api::guidv4()
				);
				print json_encode($data);
			}else{
				header('Content-Type: application/json; charset=utf-8');
				header("HTTP/1.0 404 Not Found");
				print json_encode(array(
					'Success'=>'false',
					'ErrorMsg'=>'Check client guid',
					'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
					'RequestID'=>Api::guidv4()
				));
				die;
			}
		}
	}
	function getHash(){
		if($_GET['checksum']=='23819250b5e01984d23c9ebb7a1110289e7a4767fff'){
			print sha1($_GET['guid'].SECRET);
		}
	}
	function setStart(){
		$data=Clients::setStart($_POST);
		if($data){
			header('Content-Type: application/json; charset=utf-8');
			$data=array(
				'Data'=>$data,
				'ErrorMsg'=>'',
				'Success'=>'true',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			);
			print json_encode($data);
		}else{
			header('Content-Type: application/json; charset=utf-8');
			header("HTTP/1.0 406 Not Acceptable");
			print json_encode(array(
				'Success'=>'false',
				'ErrorMsg'=>'No more lives',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			));
			die;
		}
	}
	function setWon(){
		if(is_numeric($_POST['length'])){
			$data=Clients::setWon($_POST);
			header('Content-Type: application/json; charset=utf-8');
			$data=array(
				'Data'=>$data,
				'ErrorMsg'=>'',
				'Success'=>'true',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			);
			print json_encode($data);
		}else{
			header('Content-Type: application/json; charset=utf-8');
			header("HTTP/1.0 404 Not Found");
			print json_encode(array(
				'Success'=>'false',
				'ErrorMsg'=>'Check length data',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			));
			die;
		}
	}
	function setFail(){
		if(is_numeric($_POST['length'])){
			$data=Clients::setFail ($_POST);
			header('Content-Type: application/json; charset=utf-8');
			$data=array(
				'Data'=>$data,
				'ErrorMsg'=>'',
				'Success'=>'true',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			);
			print json_encode($data);
		}else{
			header('Content-Type: application/json; charset=utf-8');
			header("HTTP/1.0 404 Not Found");
			print json_encode(array(
				'Success'=>'false',
				'ErrorMsg'=>'Check length data',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			));
			die;
		}
	}
	function getRaiting(){
		header('Content-Type: application/json; charset=utf-8');
		$data=array(
			'Data'=>array(
				'myPlaceTop50PerDay'=>Summarizing::getPlaceTop50PerDay($_GET['guid']),
				'myPlaceTop50PerWeek'=>Summarizing::getPlaceTop50PerWeek($_GET['guid']),
				'gamersTop50PerDay'=>Summarizing::getTop50PerDay($_GET['guid']),
				'gamersTop50PerWeek'=>Summarizing::getTop50PerWeek($_GET['guid']),
			),
			'ErrorMsg'=>'',
			'Success'=>'true',
			'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
			'RequestID'=>Api::guidv4()
		);
		print json_encode($data);
	}
	function setComment(){
		$data=Clients::setComment($_POST);
		header('Content-Type: application/json; charset=utf-8');
		$data=array(
			'Data'=>array('Description'=>'Comment set'),
			'ErrorMsg'=>'',
			'Success'=>'true',
			'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
			'RequestID'=>Api::guidv4()
		);
		print json_encode($data);
	}
	function getTasksList(){
		$token=Megafon::getToken();
		if(isset($token['access_token']) && $token['access_token']){
			$data=Megafon::getTasksList($token['access_token'],$_GET['refresh']);
			header('Content-Type: application/json; charset=utf-8');
			$data=array(
				'Data'=>$data,
				'ErrorMsg'=>'',
				'Success'=>'true',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			);
			print json_encode($data);
		}else{
			header('Content-Type: application/json; charset=utf-8');
			header("HTTP/1.0 404 Not Found");
			print json_encode(array(
				'Success'=>'false',
				'ErrorMsg'=>'Token not found',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			));
			die;
		}
	}
	
	//	CRON
	//	10 */1 * * * wget https://megafon-api.paladin-mobile.ru/api/v1/getAwardsList?refresh=1 >> /dev/null
	
	function getAwardsList(){
		$token=Megafon::getToken();
		if(isset($token['access_token']) && $token['access_token']){
			$data=Megafon::getAwardsList($token['access_token'],$_GET['refresh']);
			header('Content-Type: application/json; charset=utf-8');
			$data=array(
				'Data'=>$data,
				'ErrorMsg'=>'',
				'Success'=>'true',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			);
			print json_encode($data);
		}else{
			header('Content-Type: application/json; charset=utf-8');
			header("HTTP/1.0 404 Not Found");
			print json_encode(array(
				'Success'=>'false',
				'ErrorMsg'=>'Token not found',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			));
			die;
		}
	}
	function getAward(){
		$token=Megafon::getToken();
		if(isset($token['access_token']) && $token['access_token'] && $_POST['target']){
			$data=Megafon::getAward($token['access_token'],$_POST);
			header('Content-Type: application/json; charset=utf-8');
			$data=array(
				'Data'=>$data,
				'ErrorMsg'=>'',
				'Success'=>'true',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			);
			print json_encode($data);
		}else{
			header('Content-Type: application/json; charset=utf-8');
			header("HTTP/1.0 404 Not Found");
			print json_encode(array(
				'Success'=>'false',
				'ErrorMsg'=>'Check target data',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			));
			die;
		}
	}
	function setAward(){
		if($_POST['target'] && $_POST['type']){
			if($_POST['type']=='target'){
				$token=Megafon::getToken();
				if(isset($token['access_token']) && $token['access_token']) $data=Megafon::setAward($token['access_token'],$_POST);
				else $data=array('error'=>'token not found');
			}elseif($_POST['type']=='goods'){
				$token=Megafon::getToken();
				if(isset($token['access_token']) && $token['access_token']) $data=Megafon::setGoods($token['access_token'],$_POST);
				else $data=array('error'=>'token not found');
			}elseif($_POST['type']=='cups'){
				if($_POST['target']=='supercup'){
					$data=Cups::setSupercup($_POST);
				}else{
					$data=Cups::setAward($_POST);
				}		
			}elseif($_POST['type']=='tasks'){
				$token=Megafon::getToken();
				if(isset($token['access_token']) && $token['access_token']) $data=Megafon::setTasks($token['access_token'],$_POST);
				else $data=array('error'=>'token not found');
			}elseif($_POST['type']=='rating'){
				$token=Megafon::getToken();
				if(isset($token['access_token']) && $token['access_token']) $data=Megafon::setRating($token['access_token'],$_POST);
				else $data=array('error'=>'token not found');
			}
			header('Content-Type: application/json; charset=utf-8');
			if(!isset($data['error'])){
				$data=array(
					'Data'=>$data,
					'ErrorMsg'=>'',
					'Success'=>'true',
					'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
					'RequestID'=>Api::guidv4()
				);
				print json_encode($data);
			}else{
				//header("HTTP/1.0 404 Not Found");
				print json_encode(array(
					'Data'=>$data,
					'Success'=>'false',
					'ErrorMsg'=>'Server error',
					'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
					'RequestID'=>Api::guidv4()
				));
				die;
			}
		}else{
			header('Content-Type: application/json; charset=utf-8');
			//header("HTTP/1.0 404 Not Found");
			print json_encode(array(
				'Success'=>'false',
				'ErrorMsg'=>'Check target data',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			));
			die;
		}
	}
	function getOptions(){
		$data=Options::$data;
		header('Content-Type: application/json; charset=utf-8');
		$data=array(
			'Data'=>$data,
			'ErrorMsg'=>'',
			'Success'=>'true',
			'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
			'RequestID'=>Api::guidv4()
		);
		print json_encode($data);
	}
	function getMissions(){
		$data=Clients::getMissions($_GET['guid']);
		header('Content-Type: application/json; charset=utf-8');
		$data=array(
			'Data'=>$data['tasks'],
			'ErrorMsg'=>'',
			'Success'=>'true',
			'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
			'RequestID'=>Api::guidv4()
		);
		print json_encode($data);
	}
	/**MEGAFON**/
	function info(){
		if(isset($_GET['uid'])){
			$data=Clients::getClient($_GET['uid'],true);
			header('Content-Type: application/json; charset=utf-8');
			print json_encode($data);
		}else{
			header('Content-Type: application/json; charset=utf-8');
			header("HTTP/1.0 404 Not Found");
			print json_encode(array(
				'Success'=>'false',
				'ErrorMsg'=>'User not found',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			));
			die;
		}
	}
	function levels(){
		if(isset($_GET['uid'])){
			$data=Clients::getClient($_GET['uid']);
			header('Content-Type: application/json; charset=utf-8');
			print json_encode($data['targets']);
		}else{
			header('Content-Type: application/json; charset=utf-8');
			header("HTTP/1.0 404 Not Found");
			print json_encode(array(
				'Success'=>'false',
				'ErrorMsg'=>'User not found',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			));
			die;
		}
	}
	function tasks(){
		if(isset($_GET['uid'])){
			$data=Clients::getClient($_GET['uid']);
			header('Content-Type: application/json; charset=utf-8');
			print json_encode($data['tasks']);
		}else{
			header('Content-Type: application/json; charset=utf-8');
			header("HTTP/1.0 404 Not Found");
			print json_encode(array(
				'Success'=>'false',
				'ErrorMsg'=>'User not found',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			));
			die;
		}
	}
	function requests(){
		if(isset($_GET['uid'])){
			$data=Clients::getHistory($_GET['uid']);
			header('Content-Type: application/json; charset=utf-8');
			print json_encode($data['requests']);
		}else{
			header('Content-Type: application/json; charset=utf-8');
			header("HTTP/1.0 404 Not Found");
			print json_encode(array(
				'Success'=>'false',
				'ErrorMsg'=>'User not found',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			));
			die;
		}
	}
	function purchases(){
		if(isset($_GET['uid'])){
			$data=Clients::getHistory($_GET['uid']);
			header('Content-Type: application/json; charset=utf-8');
			print json_encode($data['purchases']);
		}else{
			header('Content-Type: application/json; charset=utf-8');
			header("HTTP/1.0 404 Not Found");
			print json_encode(array(
				'Success'=>'false',
				'ErrorMsg'=>'User not found',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			));
			die;
		}
	}
	function rating(){
		if(isset($_GET['uid'])){
			$data=Clients::getHistory($_GET['uid']);
			header('Content-Type: application/json; charset=utf-8');
			print json_encode($data['rating']);
		}else{
			header('Content-Type: application/json; charset=utf-8');
			header("HTTP/1.0 404 Not Found");
			print json_encode(array(
				'Success'=>'false',
				'ErrorMsg'=>'User not found',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			));
			die;
		}
	}
	function ratingListPerDay(){
		$data=Summarizing::getTop50PerDayAll();
		header('Content-Type: application/json; charset=utf-8');
		print json_encode($data);
	}
	function ratingListPerWeek(){
		$data=Summarizing::getTop50PerWeekAll();
		header('Content-Type: application/json; charset=utf-8');
		print json_encode($data);
	}
	function coupons(){
		$data=Cache::get('coupons');
		header('Content-Type: application/json; charset=utf-8');
		print json_encode($data[$_GET['publicId']]);
	}
	function lives(){
		if(isset($_POST['uid'])){
			$data=Clients::lives($_POST);
			header('Content-Type: application/json; charset=utf-8');
			$data=array(
				'ErrorMsg'=>'',
				'Success'=>'true',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			);
			print json_encode($data);
		}else{
			header('Content-Type: application/json; charset=utf-8');
			header("HTTP/1.0 404 Not Found");
			print json_encode(array(
				'Success'=>'false',
				'ErrorMsg'=>'User not found',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			));
			die;
		}
	}
	/***
		CRON
		59 23 * * * wget https://megafon-api.paladin-mobile.ru/api/v1/summarizing >> /dev/null
	**/
	function summarizing(){
		$sdate = date('Y-m-d H:i:s');
		Cache::set('summarizingLog',array('fail'=>date('Y-m-d H:i:s')));
		$data=Summarizing::setSummarizing($_GET);
		if($data){
			$cache=array(
				'sdate'=>$sdate,
				'fdate'=>date('Y-m-d H:i:s')
			);
			Cache::set('summarizingLog',$cache);
			header('Content-Type: application/json; charset=utf-8');
			$data=array(
				'Data'=>array("description"=>"Summarizing done"),
				'ErrorMsg'=>'',
				'Success'=>'true',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			);
			print json_encode($data);
		}else{
			header('Content-Type: application/json; charset=utf-8');
			header("HTTP/1.0 404 Not Found");
			print json_encode(array(
				'Success'=>'false',
				'ErrorMsg'=>'Check length data',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			));
			die;
		}
	}
	function summarizingTop50(){
		$sdate = date('Y-m-d H:i:s');
		Cache::set('summarizingTop50Log',array('fail'=>date('Y-m-d H:i:s')));
		$data=Summarizing::setSummarizingTop50($_GET);
		if($data){
			$cache=array(
				'sdate'=>$sdate,
				'fdate'=>date('Y-m-d H:i:s')
			);
			Cache::set('summarizingTop50Log',$cache);
			header('Content-Type: application/json; charset=utf-8');
			$data=array(
				'Data'=>array("description"=>"Summarizing Top 50 done"),
				'ErrorMsg'=>'',
				'Success'=>'true',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			);
			print json_encode($data);
		}else{
			header('Content-Type: application/json; charset=utf-8');
			header("HTTP/1.0 404 Not Found");
			print json_encode(array(
				'Success'=>'false',
				'ErrorMsg'=>'Check length data',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			));
			die;
		}
	}
	/***
		CRON
		30 23 * * * wget https://megafon-api.paladin-mobile.ru/api/v1/reportXLSX >> /dev/null
	**/
	function reportXLSX(){
		$sdate = date('Y-m-d H:i:s');
		Cache::set('reportXLSXLog',array('fail'=>date('Y-m-d H:i:s')));
		$data=Reports::reportXLSX();
		if($data){
			$cache=array(
				"filename"=>$data,
				'sdate'=>$sdate,
				'fdate'=>date('Y-m-d H:i:s')
			);
			Cache::set('reportXLSXLog',$cache);
			header('Content-Type: application/json; charset=utf-8');
			$data=array(
				'Data'=>array(
					"description"=>"Report done",
					"filename"=>$data,
					'sdate'=>$sdate,
					'fdate'=>$cache['fdate']		
				),
				'ErrorMsg'=>'',
				'Success'=>'true',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			);
			print json_encode($data);
		}else{
			header('Content-Type: application/json; charset=utf-8');
			header("HTTP/1.0 404 Not Found");
			print json_encode(array(
				'Success'=>'false',
				'ErrorMsg'=>'Check length data',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			));
			die;
		}
	}
	/**SETTINGS**/
	function setSettings(){
		unset($_POST['checksum']);
		$data=Settings::setSettings($_POST);
		if($data){
			header('Content-Type: application/json; charset=utf-8');
			$data=array(
				'Data'=>array("description"=>"Settings set"),
				'ErrorMsg'=>'',
				'Success'=>'true',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			);
			print json_encode($data);
		}else{
			header('Content-Type: application/json; charset=utf-8');
			header("HTTP/1.0 404 Not Found");
			print json_encode(array(
				'Success'=>'false',
				'ErrorMsg'=>'Check settings data',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'RequestID'=>Api::guidv4()
			));
			die;
		}
	}
	function getSettings(){
		$data=Settings::getSettings();
		header('Content-Type: application/json; charset=utf-8');
		$data=array(
			'Data'=>$data,
			'ErrorMsg'=>'',
			'Success'=>'true',
			'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
			'RequestID'=>Api::guidv4()
		);
		print json_encode($data);
	}
}
?>