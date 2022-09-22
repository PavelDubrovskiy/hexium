<?
//header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);die;
//header("HTTP/1.0 404 Not Found");die;
//print 'erroor';die;
//if($_SERVER['REMOTE_ADDR']=='5.143.252.230') die;
//if($_SERVER['REMOTE_ADDR']=='213.59.239.186') die;
//if($_SERVER['REMOTE_ADDR']=='217.107.80.162') die;
//if($_SERVER['REMOTE_ADDR']=='213.59.239.150') die;
error_reporting(1);
ini_set('memory_limit', '1024M');
//ini_set('display_errors', 1);
date_default_timezone_set('Europe/Moscow');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
define('SOURCE',$_SERVER['DOCUMENT_ROOT'].'/..');
require_once SOURCE.'/settings.php';
require_once SOURCE.'/'.CMS_DIR.'/index.php';
?>