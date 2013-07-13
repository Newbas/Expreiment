<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return CMap::mergeArray( array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Perfolios.com',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'defaultController'=>'home',

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'loginUrl'=>array('/'),
		),
		'portfolio'=>array(
			'class'=>'ext.Portfolio.Portfolio'
		),
// 		'db'=>array(
// 			'connectionString' => 'sqlite:protected/data/blog.db',
// 			'tablePrefix' => 'tbl_',
// 		),
		// uncomment the following to use a MySQL database
		
			
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'home/error',
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>' => '<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
				'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
			),
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
// 				array(
// 					'class'=>'CWebLogRoute',
// 					'categories'=>'system.db.CDbCommand',
// 					'showInFireBug'=>true,
// 				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),
	
	'modules'=>array(
		'gii' => array(
				'class' => 'system.gii.GiiModule',
				'password' => 'test',
				// If removed, Gii defaults to localhost only. Edit carefully to taste.
				'ipFilters' => array('127.0.0.1', '*', '10.0.89.255', '::1'),
		),
	),
	
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
),  require(dirname(__FILE__).'/base.php') );