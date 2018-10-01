<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*-------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
*/
$active_group = 'conv_con';
$query_builder = TRUE;

if(ENVIRONMENT === 'testing')
{
/***** LOCAL MyPHP INI******/
$db['conv_con'] = array('hostname' => 'localhost',
                        'username' => 'convivere_admin',
                        'password' => 'C0nv1v3r3',
                        'database' => 'data_convivere',
                        'dbdriver' => 'mysqli',
                        'dbprefix' => PREFIX_DB_CVR,
                        'pconnect' => FALSE,
                        'db_debug' => TRUE,
                        'cache_on' => FALSE,
                        'cachedir' => '',
                        'char_set' => 'utf8',
                        'dbcollat' => 'utf8_spanish_ci',
                        'encrypt' => FALSE,
                        'compress' => FALSE,
                        'stricton' => FALSE,
                        'failover' => array(),
                        'save_queries' => TRUE
                        );
/***** LOCAL MyPHP  FIN******/
}
else
{
/*****  MSSQLSERVER  FIN*******/
 //$db['conv_con']['hostname'] = 'db-server-convivere.database.windows.net';
 //$db['conv_con']['hostname'] = DESKTOP-KEPR1NF\EMS_SQL_SERVER'';
$db['conv_con'] = array('hostname' => HOSTNAME,
                        'username' => 'convivere_admin',
                        'password' => 'C0nv1v3r3',
                        'database' => 'data_convivere',
                        'dbdriver' => 'sqlsrv',
                        'dbprefix' => PREFIX_DB_CVR,
                        'pconnect' => FALSE,
                        'db_debug' => (ENVIRONMENT !== 'production'),
                        'cache_on' => FALSE,
                        'cachedir' => '',
                        'char_set' => 'utf8',
                        'dbcollat' => 'utf8_general_ci',
                        'swap_pre' => '',
                        'autoinit' => TRUE,
                        'encrypt'  => FALSE,
                        'compress' => FALSE,
                        'stricton' => FALSE,
                        'failover' => array()
);
/*****  MSSQLSERVER  FIN******/
}
