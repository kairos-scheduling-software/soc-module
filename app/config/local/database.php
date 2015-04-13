<?php

$db_env = getenv('KAIROS_SOC_DB');
if ($db_env == null) {
	$db_env = 'mysql://root:@localhost/kairos_soc';
}

$db_url = parse_url($db_env);

if (!isset($db_url['pass'])) {
	$db_url['pass'] = '';
}

return array(

	/*
	|--------------------------------------------------------------------------
	| Database Connections
	|--------------------------------------------------------------------------
	|
	| Here are each of the database connections setup for your application.
	| Of course, examples of configuring each database platform that is
	| supported by Laravel is shown below to make development simple.
	|
	|
	| All database work in Laravel is done through the PHP PDO facilities
	| so make sure you have the driver for your particular database of
	| choice installed on your machine before you begin development.
	|
	*/

	'connections' => array(

		$db_url['scheme'] => array(
			'driver'    => $db_url['scheme'],
			'host'      => $db_url['host'],
			'database'  => substr($db_url['path'], 1),
			'username'  => $db_url['user'],
			'password'  => $db_url['pass'],
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

		'pgsql' => array(
			'driver'   => 'pgsql',
			'host'     => 'localhost',
			'database' => 'homestead',
			'username' => 'homestead',
			'password' => 'secret',
			'charset'  => 'utf8',
			'prefix'   => '',
			'schema'   => 'public',
		),

	),
	
	'default' => $db_url['scheme'],

);
