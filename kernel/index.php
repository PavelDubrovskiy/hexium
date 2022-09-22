<?php
$startMemory = memory_get_usage();
$start = microtime(true);
//session_start();
$dir = explode('/', $_SERVER['PHP_SELF']);
$current_dir = $dir[1];
if ($current_dir != CMS_DIR) {
    $current_dir = '';
} else {
    $current_dir = '/' . $current_dir;
}

require_once SOURCE . '/' . CMS_DIR . '/class/DB' . BDCONNECTION . '.php';
require_once SOURCE . '/' . CMS_DIR . '/class/Funcs.php';
require_once SOURCE . '/' . CMS_DIR . '/class/View.php';
require_once SOURCE . '/' . CMS_DIR . '/class/PHPEmail.php';
require_once SOURCE . '/' . CMS_DIR . '/class/Cache.php';

new DB;
new Funcs;

$dir = scandir(SOURCE . '/models/');

foreach ($dir as $class) {
    if (strpos($class, '.php') && substr($class, 0, 1) != '_') {
        require_once SOURCE .'/models/' . $class;
    }
}

$controllers = array();
if ($current_dir) {
    $dir = opendir(SOURCE . PLUGINS_DIR);
    while ($pluginDir = readdir($dir)) {
        $pdir = opendir(SOURCE . PLUGINS_DIR . $pluginDir . '/');
        if ($pluginDir != '.' && $pluginDir != '..' && substr($pluginDir, 0, 1) != '_') {
            while ($plugin = readdir($pdir)) {
                $pdir = opendir(SOURCE . PLUGINS_DIR . $pluginDir . '/controllers/');
                while ($plugin = readdir($pdir)) {
                    if (strpos($plugin, 'Controller.php')) {
                        $controller = strtolower(substr($plugin, 0, strlen($plugin) - 14));
                        $controllers[$controller] = array(name => $controller, path => PLUGINS_DIR . $pluginDir . '/controllers/');
                    }
                }
                $pdir = opendir(SOURCE . PLUGINS_DIR . $pluginDir . '/models/');
                while ($plugin = readdir($pdir)) {
                    if (strpos($plugin, '.php')) {
                        require_once SOURCE . PLUGINS_DIR . $pluginDir . '/models/' . $plugin;
                    }
                }
            }
        }
    }
}
$controllers = array();
$dir = opendir(SOURCE . '/controllers/');
while ($class = readdir($dir))
	if (strpos($class, 'Controller.php'))
		$controllers[] = strtolower(substr($class, 0, strlen($class) - 14));
$suburi = Funcs::$uri;

if (isset($suburi[0]) && in_array($suburi[0], $controllers)) {
	$class = $suburi[0];
	$class = strtoupper(substr($class, 0, 1)) . substr($class, 1, strlen($class));
	require_once SOURCE . '/controllers/' . $class . 'Controller.php';
	eval('$mod = new ' . $class . 'Controller;');
	if(isset($suburi[1]) && $suburi[1] != '' && !is_numeric($suburi[1])) {
		if (method_exists($class . 'Controller', $suburi[1] . '')) {
			eval('$mod->' . $suburi[1] . '();');
		}
	}
} else { // unknown module
	require_once SOURCE . '/controllers/IndexController.php';
	$mod = new IndexController;
}

if (DEBUG == 2) {
    echo 'Время выполнения скрипта ' . (microtime(true) - $start) . '<br />';
    echo 'Количество SQL-запросов ' . DB::$count . '<br />';
    echo 'Количество потребляемой памяти ' . number_format((memory_get_usage() - $startMemory), 0, '', ' ') . ' bytes<br />';
    echo 'COOKIE: <br /><pre>';
    print_r($_COOKIE);
    echo '</pre><br />SESSION: <br /><pre>';
    print_r($_SESSION);
    echo '</pre><br /><br /><a href="?clear=cookies">Сбросить куки и сессии</a><br /><br />';
}