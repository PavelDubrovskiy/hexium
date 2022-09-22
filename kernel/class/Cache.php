<?
class Cache {
	public static $mc;
	function __construct(){
		if(CACHE==1){
			Cache::$mc=new Memcached();
			Cache::$mc->addServer("127.0.0.1", 11211);
		}
	}
	public static function set($name,$value,$type='memcached'){
		if(CACHE==1){
			if($type=='memcached'){
				$value=serialize($value);
				file_put_contents(SOURCE.CACHE_DIR.CACHEPREFIX.$name.'.ch',$value);
				Cache::$mc->set(CACHEPREFIX.$name,$value);
			}else{
				file_put_contents($_SERVER['DOCUMENT_ROOT'].CACHE_DIR.$name.'.ch',serialize($value));
			}
		}
	}
	public static function get($name,$type='memcached'){
		if(CACHE==1){
			if($type=='memcached'){
				$data=Cache::$mc->get(CACHEPREFIX.$name);
				if(!$data) $data=file_get_contents(SOURCE.CACHE_DIR.CACHEPREFIX.$name.'.ch');
				$data=unserialize($data);
				return $data;
			}else{
				if(file_exists($_SERVER['DOCUMENT_ROOT'].CACHE_DIR.$name.'.ch')){
					return unserialize(file_get_contents($_SERVER['DOCUMENT_ROOT'].CACHE_DIR.$name.'.ch'));
				}else{
					return false;
				}
			}
		}else{
			return false;
		}
	}
	public static function del($name,$type='memcached'){
		if(CACHE==1){
			if($type=='memcached'){
				if(file_exists(SOURCE.CACHE_DIR.CACHEPREFIX.$name.'.ch')) unlink(SOURCE.CACHE_DIR.CACHEPREFIX.$name.'.ch');
				Cache::$mc->delete(CACHEPREFIX.$name);
			}else{
				if(file_exists($_SERVER['DOCUMENT_ROOT'].CACHE_DIR.$name.'.ch')){
					unlink($_SERVER['DOCUMENT_ROOT'].CACHE_DIR.$name.'.ch');
				}else{
					return false;
				}
			}
		}else{
			return false;
		}
	}
	public static function getCountCache(){
		$data=0;
		$d = dir($_SERVER['DOCUMENT_ROOT'].CACHE_DIR);
		while (false !== ($entry = $d->read())) {
			if($entry!='.' && $entry!='..'){
				$data++;
			}
		}
		return $data;
	}
	public static function clear($name,$type='memcached'){
		if(CACHE==1){
			if($type=='memcached'){
				Cache::$mc->delete($name);
				return true;
			}else{
				if(file_exists($_SERVER['DOCUMENT_ROOT'].CACHE_DIR.$name.'.ch')){
					unlink($_SERVER['DOCUMENT_ROOT'].CACHE_DIR.$name.'.ch');
					return true;
				}else{
					return false;
				}
			}
		}else{
			return false;
		}
	}
	public static function clearCache(){
		$data=0;
		$d = dir($_SERVER['DOCUMENT_ROOT'].CACHE_DIR);
		while (false !== ($entry = $d->read())) {
			if($entry!='.' && $entry!='..'){
				unlink($_SERVER['DOCUMENT_ROOT'].CACHE_DIR.$entry);
			}
		}
	}
	public static function clearOneCache(){
		$path=$_SERVER['DOCUMENT_ROOT'].CACHE_DIR.$_POST['tab'].$_POST['id'].'.ch';
		if(file_exists($path)) unlink($path);
	}
}
new Cache;