<?
header("Access-Control-Allow-Origin: *");
$filepath=str_replace('/o/?','',$_SERVER['REQUEST_URI']);
$razarray=explode('.',$filepath);
$raz=strtolower(end($razarray));
$filearray=explode('/',$filepath);
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/o/'.end($filearray))) $filepath=$_SERVER['DOCUMENT_ROOT'].'/o/'.end($filearray);
if($raz=='png'){
	header ('Content-Type: image/png'); 
	print file_get_contents($filepath);
}elseif($raz=='jpg' || $raz=='jpeg'){
	header ('Content-Type: image/jpeg');
	print file_get_contents($filepath);
}
?>