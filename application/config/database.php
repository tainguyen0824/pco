<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * lance note - host, db name, db user name, pw
 * @package  Database
 *
 * Database connection settings, defined as arrays, or "groups". If no group
 * name is used when loading the database library, the group named "default"
 * will be used.
 *
 * Each group can be connected to independently, and multiple groups can be
 * connected at once.
 *
 * Group Options:
 *  benchmark     - Enable or disable database benchmarking
 *  persistent    - Enable or disable a persistent connection
 *  connection    - Array of connection specific parameters; alternatively,
 *                  you can use a DSN though it is not as fast and certain
 *                  characters could create problems (like an '@' character
 *                  in a password):
 *                  'connection'    => 'mysql://dbuser:secret@localhost/kohana'
 *  character_set - Database character set
 *  table_prefix  - Database table prefix
 *  object        - Enable or disable object results
 *  cache         - Enable or disable query caching
 *	escape        - Enable automatic query builder escaping
 */  
 if ($_SERVER["HTTP_HOST"] == "localhost") {
	$config['default'] = array
	(
		'benchmark'     => TRUE,
		'persistent'    => FALSE,
		'connection' => array
		(
		'type'     => 'mysqli',
		'user'     => 'root', //haider13190jpx
		'pass'     => '', //hr93kb9zmekr929xcm
		'host'     => 'localhost', //kioskdb.c2rkwdzc2in9.us-west-2.rds.amazonaws.com:3306
		'port'     => FALSE,
		'socket'   => FALSE,
		'database' => 'pco' //rdsdatabase
		),
		'character_set' => 'utf8',
		'table_prefix'  => '',
		'object'        => TRUE,
		'cache'         => FALSE,
		'escape'        => TRUE
	);
	$config['TermiteKios'] = array
	(
		'benchmark'     => TRUE,
		'persistent'    => FALSE,
		'connection'    => array
		(
		'type'     => 'mysqli',
		'user'     => 'root', //haider13190jpx
		'pass'     => '', //hr93kb9zmekr929xcm
		'host'     => 'localhost', //kioskdb.c2rkwdzc2in9.us-west-2.rds.amazonaws.com:3306
		'port'     => FALSE,
		'socket'   => FALSE,
		'database' => 'termite' //rdsdatabase
		),
		'character_set' => 'utf8',
		'table_prefix'  => '',
		'object'        => TRUE,
		'cache'         => FALSE,
		'escape'        => TRUE
	);
} 
else {
	$config['default'] = array
	(
		'benchmark'     => TRUE,
		'persistent'    => FALSE,
		'connection' => array
		(
		'type'     => 'mysqli',
		'user'     => 'root', //haider13190jpx
		'pass'     => '', //hr93kb9zmekr929xcm
		'host'     => 'localhost', //kioskdb.c2rkwdzc2in9.us-west-2.rds.amazonaws.com:3306
		'port'     => FALSE,
		'socket'   => FALSE,
		'database' => 'pco' //rdsdatabase
		),
		'character_set' => 'utf8',
		'table_prefix'  => '',
		'object'        => TRUE,
		'cache'         => FALSE,
		'escape'        => TRUE
	);
	$config['TermiteKios'] = array
	(
		'benchmark'     => TRUE,
		'persistent'    => FALSE,
		'connection'    => array
		(
		'type'     => 'mysqli',
		'user'     => 'root', //haider13190jpx
		'pass'     => '', //hr93kb9zmekr929xcm
		'host'     => 'localhost', //kioskdb.c2rkwdzc2in9.us-west-2.rds.amazonaws.com:3306
		'port'     => FALSE,
		'socket'   => FALSE,
		'database' => 'termite' //rdsdatabase
		),
		'character_set' => 'utf8',
		'table_prefix'  => '',
		'object'        => TRUE,
		'cache'         => FALSE,
		'escape'        => TRUE
	);
	// $config['default'] = array
	// (
	// 	'benchmark'     => TRUE,
	// 	'persistent'    => FALSE,
	// 	'connection'    => array
	// 	(
	// 	'type'     => 'mysqli',
	// 	'user'     => 'vn', //haider13190jpx
	// 	'pass'     => 'vn1057xp10x8apew', //hr93kb9zmekr929xcm
	// 	'host'     => 'ec2-52-27-243-189.us-west-2.compute.amazonaws.com:3306', //kioskdb.c2rkwdzc2in9.us-west-2.rds.amazonaws.com:3306
	// 	'port'     => FALSE,
	// 	'socket'   => FALSE,
	// 	'database' => 'pco' //PCO
	// 	),
	// 	'character_set' => 'utf8',
	// 	'table_prefix'  => '',
	// 	'object'        => TRUE,
	// 	'cache'         => FALSE,
	// 	'escape'        => TRUE
	// );
	// $config['TermiteKios'] = array
	// (
	// 	'benchmark'     => TRUE,
	// 	'persistent'    => FALSE,
	// 	'connection'    => array
	// 	(
	// 	'type'     => 'mysqli',
	// 	'user'     => 'vn', //haider13190jpx
	// 	'pass'     => 'vn1057xp10x8apew', //hr93kb9zmekr929xcm
	// 	'host'     => 'ec2-52-25-236-137.us-west-2.compute.amazonaws.com', //kioskdb.c2rkwdzc2in9.us-west-2.rds.amazonaws.com:3306
	// 	'port'     => FALSE,
	// 	'socket'   => FALSE,
	// 	'database' => 'rdsdatabase' //rdsdatabase
	// 	),
	// 	'character_set' => 'utf8',
	// 	'table_prefix'  => '',
	// 	'object'        => TRUE,
	// 	'cache'         => FALSE,
	// 	'escape'        => TRUE
	// );
}