<?php
session_start();

@setlocale(LC_ALL,NULL);
@setlocale(LC_ALL,'pt_BR');
@date_default_timezone_set('America/Sao_Paulo');
@ini_set('max_execution_time', 0);

define('PROJECT', 		'project-qt');
define('BACKEND_TITLE', 'Quintal de Trocas - Gerenciador de Conte&uacute;do - ');

error_reporting(E_ALL);

$system_folder 		= 'system';
$application_folder = 'application';

if (strpos($system_folder, '/') === FALSE)
{
	if (function_exists('realpath') AND @realpath(dirname(__FILE__)) !== FALSE)
	{
		$system_folder = realpath(dirname(__FILE__)).'/'.$system_folder;
	}
}
else
{
	$system_folder = str_replace('\\', '/', $system_folder); 
}

define('EXT', 		'.php');
define('SELF', 		pathinfo(__FILE__, PATHINFO_BASENAME));
define('FCPATH', 	str_replace(SELF, '', __FILE__));
define('BASEPATH', 	$system_folder.'/');

// fck editor
$_SESSION['FCPATH'] = FCPATH;

if (is_dir($application_folder))
{
	define('APPPATH', $application_folder.'/');
}
else
{
	if ($application_folder == '')
	{
		$application_folder = 'application';
	}

	define('APPPATH', BASEPATH.$application_folder.'/');
}

require_once BASEPATH.'codeigniter/CodeIgniter'.EXT;