<?
class IndexController{
	function __construct(){
		if(Funcs::$uri[0]==''){
			View::render('site/index');
		}elseif(Funcs::$uri[0]=='rep143400'){
			Reports::report2();
		}
	}
}
?>