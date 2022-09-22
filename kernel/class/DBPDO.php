<?
class DB{
	public static $sql;
	public static $count=0;
	public static $pdo;
	public static $error=false;
	public function __construct(){
		try{
			DB::$pdo = new PDO('mysql:host='.BDHOST.';dbname='.BDNAME.'', BDLOGIN, BDPASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
			DB::$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		}catch(PDOException $e){
			//print $e->getMessage();
			print 'Can\'t connect to database';
			die;
		}
	}
	public function getOne($sql,$exec=0){
		$sql=DB::prefix($sql,$exec);
		try{
			$row=DB::$pdo->query($sql);
			if($row){
				$row->setFetchMode(PDO::FETCH_NUM);  
				$row=$row->fetch();
				return $row[0];
			}else{
				return '';
			}
		}catch(PDOException $e){
			die(DB::error($sql,debug_backtrace()));
		}
	}
	public function getRow($sql,$exec=0){
		$sql=DB::prefix($sql,$exec);
		try{
			$row=DB::$pdo->query($sql);
			$row=$row->fetch(PDO::FETCH_ASSOC);
			if($row){
				return $row;
			}else{
				return '';
			}
		}catch(PDOException $e){
			die(DB::error($sql,debug_backtrace()));
		}
	}
	public function getAll($sql,$one='',$exec=0){
		$data=array();
		$sql=DB::prefix($sql,$exec);
		try{
			$q=DB::$pdo->query($sql);
			if(!empty($q)){
				$q=$q->fetchAll(PDO::FETCH_ASSOC);
				if(!empty($q)){
					foreach($q as $row){
						if($one==''){
							$data[]=$row;
						}else{
							$data[]=$row[$one];
						}
					}
					return $data;
				}else{
					return array();
				}
			}else{
				return array();
			}
		}catch(PDOException $e){
			die(DB::error($sql,debug_backtrace()));
		}
	}
	public function getPagi($sql,$one='',$exec=0){
		$perpage=0;
		if(Funcs::$OneSSA){
			$perpage=$_SESSION['user']['perpage'];
		}else{
			if($_SESSION['perpage'][end(Funcs::$uri)]){
				$perpage=$_SESSION['perpage'][end(Funcs::$uri)];
			}
			if($perpage==0){
				$perpage=$_SESSION['perpage'][reset(Funcs::$uri)];
			}
			if($perpage==0){
				$perpage=10;
			}
		}
		$page=1;
		if(isset($_GET['p'])) $page=$_GET['p'];
		$data=array();
		$sql=DB::prefix($sql,$exec);
		$sqlcount=substr($sql,strpos($sql,' FROM '),strlen($sql));
		$tab=substr($sqlcount,strpos($sqlcount,' ',1),strpos($sqlcount,' ',6)-1);
		if(strpos($sql,'SELECT DISTINCT')!==false){
			$sqlcount='SELECT COUNT(DISTINCT '.$tab.'.id) '.$sqlcount;
		}else{
			$sqlcount='SELECT count(*) '.$sqlcount;
		}
		if(strpos($sqlcount,'ORDER BY')!==false)$sqlcount=substr($sqlcount,0,strpos($sqlcount,'ORDER BY'));
		/*try{
			if(class_exists(PaginationWidget))PaginationWidget::$count=DB::getOne($sqlcount,1);
		}catch (Exception $e) {print '';}*/
		$sql=$sql.' LIMIT '.(($page-1)*$perpage).','.$perpage.'';
		try{
			$q=DB::$pdo->query($sql);
			if(!empty($q)){
				$q=$q->fetchAll(PDO::FETCH_ASSOC);
				if(!empty($q)){
					foreach($q as $row){
						if($one==''){
							$data[]=$row;
						}else{
							$data[]=$row[$one];
						}
					}
					return $data;
				}else{
					return array();
				}
			}else{
				return array();
			}
		}catch(PDOException $e){
			die(DB::error($sql,debug_backtrace()));
		}
		return $data;
	}
	public function exec($sql){
		try{
			$sql=DB::prefix($sql,1);
			DB::$pdo->exec($sql);
			return DB::$pdo->lastInsertId();
		}catch(PDOException $e){
			die(DB::error($sql,debug_backtrace()));
		}
	}
	public function set($sql,$data){
		foreach($data as $i=>$item) if($item===NULL) $data[$i]='';
		$sql=DB::prefix($sql,1);
		try{
			$pdo=DB::$pdo->prepare($sql);
			try{
				$pdo->execute($data);
				return DB::$pdo->lastInsertId();
			}catch(PDOException $e){
				die(DB::error($sql,debug_backtrace(),$e->getMessage()));
			}
		}catch(PDOException $e){
			die(DB::error($sql,debug_backtrace(),$e->getMessage()));
		}
	}
	public function prefix($sql,$exec=0){
		if(strpos($sql,'JOIN')!==false || strpos($sql,'ALTER TABLE')!==false){
			$sql=str_replace('{{',BDPREFIX,$sql);
			$sql=str_replace('}}','',$sql);
		}elseif(strpos($sql,'INSERT')!==false && strpos($sql,'SELECT')!==false){
			$sql=str_replace('{{',BDPREFIX,$sql);
			$sql=str_replace('}}','',$sql);
		}elseif(strpos($sql,'DELETE')!==false && strpos($sql,'SELECT')!==false){
			$sql=str_replace('{{',BDPREFIX,$sql);
			$sql=str_replace('}}','',$sql);
		}else{
			$sql=preg_replace(array('/{{/','/}}/'),array(BDPREFIX,''),$sql,1);
		}
		$site=1;
		if(isset($_SESSION['site'])) $site=$_SESSION['site'];
		if(isset(Funcs::$uri[0]) && Funcs::$uri[0]==CMS_DIR){
			$site=$_SESSION['OneSSA']['site'];
		}
		if($exec==0){
			if(strpos($sql,BDPREFIX.'tree')!==false && strpos($sql,BDPREFIX.'tree t ')===false){
				if(strpos($sql,'WHERE')!==false){
					$sql=str_replace('WHERE','WHERE '.BDPREFIX.'tree.site='.$site.' AND',$sql);
				}else{
					$sql=str_replace(BDPREFIX.'tree',BDPREFIX.'tree WHERE '.BDPREFIX.'tree.site='.$site.'',$sql);
				}
			}elseif(strpos($sql,BDPREFIX.'tree t ')!==false){
				if(strpos($sql,'WHERE')!==false){
					$sql=str_replace('WHERE','WHERE t.site='.$site.' AND',$sql);
				}
			}
		}
		DB::$sql=$sql;
		DB::$count++;
		return $sql;
	}
	public static function error($sql,$backtrace, $errorTry=''){
		if(DEBUG==0 && Funcs::$uri[0]!=CMS_DIR){
			header("HTTP/1.0 404 Not Found");
			DB::$error=true;
			View::$layout='main';
			View::render('site/error404');
			$str=array('sql'=>$sql,'backtrace'=>$backtrace,'error'=>$error[2].' '.$errorTry);
			ob_start();
				print '--------------'.date('H:i:s d.m.Y').'-------------'."\n";
				print_r($str);
				$str=ob_get_contents();
			ob_end_clean();
			file_put_contents($_SERVER['DOCUMENT_ROOT'].'/error.log',$str,FILE_APPEND);
		}elseif(DEBUG!=0 && Funcs::$uri[0]!=CMS_DIR){
			header("HTTP/1.0 404 Not Found");
			$error=DB::$pdo->errorInfo();
			print View::getRenderEmpty('site/error',array('sql'=>$sql,'backtrace'=>$backtrace,'error'=>$error[2].' '.$errorTry));
		}else{
			$error=DB::$pdo->errorInfo();
			print View::getRenderOneSSAEmpty('site/error',array('sql'=>$sql,'backtrace'=>$backtrace,'error'=>$error[2].' '.$errorTry));
		}
		die;
	}
	public static function escapePost($notEscape=''){
		foreach($_POST as $key=>$value){
			if($key!=$notEscape){
				if(is_array($value)){
					foreach($value as $i=>$item){
						if(is_array($item)){
							foreach($item as $i2=>$item2){
								$value[$i][$i2]=str_replace('"','&quot;',str_replace("'","&#39;",htmlspecialchars(strip_tags(trim($item2)))));
							}
						}else{
							$value[$i] =str_replace('"','&quot;',str_replace("'","&#39;",htmlspecialchars(strip_tags(trim($item)))));
						}
					}
					$_POST[$key]=$value;
				}else{
					$_POST[$key] =str_replace('"','&quot;',str_replace("'","&#39;",htmlspecialchars(strip_tags(trim($value)))));
				}
			}else{
				if(is_array($value)){
					foreach($value as $i=>$item){
						if(is_array($item)){
							foreach($item as $i2=>$item2){
								$value[$i][$i2]=$item2;
							}
						}else{
							$value[$i]=$item;
						}
					}
					$_POST[$key]=$value;
				}else{
					$_POST[$key]=$value;
				}
			}
		}
	}
	public static function escapeGet($notEscape=''){
		foreach($_POST as $key=>$value){
			if($key!=$notEscape){
				if(is_array($value)){
					foreach($value as $i=>$item){
						if(is_array($item)){
							foreach($item as $i2=>$item2){
								$value[$i][$i2]=str_replace('"','&quot;',str_replace("'","&#39;",htmlspecialchars(strip_tags(trim($item2)))));
							}
						}else{
							$value[$i] =str_replace('"','&quot;',str_replace("'","&#39;",htmlspecialchars(strip_tags(trim($item)))));
						}
					}
					$_POST[$key]=$value;
				}else{
					$_POST[$key] =str_replace('"','&quot;',str_replace("'","&#39;",htmlspecialchars(strip_tags(trim($value)))));
				}
			}else{
				if(is_array($value)){
					foreach($value as $i=>$item){
						if(is_array($item)){
							foreach($item as $i2=>$item2){
								$value[$i][$i2]=$item2;
							}
						}else{
							$value[$i]=$item;
						}
					}
					$_POST[$key]=$value;
				}else{
					$_POST[$key]=$value;
				}
			}
		}
	}
}
?>