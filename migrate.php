<?php
echo '<pre>';
// change the following paths if necessary
$yii = dirname(__FILE__).'/../framework/yii.php';
$config = dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG', true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

require_once($yii);
Yii::createWebApplication($config);

$runner=new CConsoleCommandRunner();
$runner->commands=array(
    'migrate' => array(
        'class' => 'system.cli.commands.MigrateCommand',
        'interactive' => false,
    ),
);

$params = $_GET;
$params = array(
    'yiic',
    'migrate',
);
isset($_GET['command']) ? $params[] = $_GET['command'] : '';
isset($_GET['name']) ? $params[] = $_GET['name'] : '';

ob_start();
$runner->run($params);
echo htmlentities(ob_get_clean(), null, Yii::app()->charset);