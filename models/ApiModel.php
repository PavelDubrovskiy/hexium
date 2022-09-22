<?
class Api{
	public static function checkAppkey(){
		$guid='';
		if(isset($_GET['guid'])) $guid=$_GET['guid'];
		if(isset($_POST['guid'])) $guid=$_POST['guid'];
		$checksum='';
		if(isset($_GET['checksum'])) $checksum=$_GET['checksum'];
		if(isset($_POST['checksum'])) $checksum=$_POST['checksum'];
		if(isset($_GET['checkSum'])) $checksum=$_GET['checkSum'];
		if(isset($_POST['checkSum'])) $checksum=$_POST['checkSum'];
		if(sha1($guid.SECRET)==$checksum){
			return true;
		}else{
			header('Content-Type: application/json; charset=utf-8');
			header('HTTP/1.0 401 Unauthorized');
			print json_encode(array(
				'Success'=>'false',
				'ErrorMsg'=>'Wrong checksum',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'ID'=>Api::guidv4()
			));
			die;
		}
	}
	public static function checkSettingsAppkey(){
		$checksum='';
		if(isset($_GET['checksum'])) $checksum=$_GET['checksum'];
		if(isset($_POST['checksum'])) $checksum=$_POST['checksum'];
		if(isset($_GET['checkSum'])) $checksum=$_GET['checkSum'];
		if(isset($_POST['checkSum'])) $checksum=$_POST['checkSum'];
		if(sha1(ADMIN_LOGIN.ADMIN_PASSWORD)==$checksum){
			return true;
		}else{
			header('Content-Type: application/json; charset=utf-8');
			header('HTTP/1.0 401 Unauthorized');
			print json_encode(array(
				'Success'=>'false',
				'ErrorMsg'=>'Wrong checksum',
				'Generated'=>date_format(date_create(date('Y-m-d H:i:s')), 'c'),
				'ID'=>Api::guidv4()
			));
			die;
		}
	}
	public static function guidv4(){
		if (function_exists('com_create_guid') === true)
			return trim(com_create_guid(), '{}');

		$data = openssl_random_pseudo_bytes(16);
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}
}
?>