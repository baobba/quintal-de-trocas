<?php 

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

$autoload['libraries']  = array('database', 'input');
$autoload['helper']     = array('url', 'lang', 'const', 'friendly_url_finder', 'slug', 'mailer');
$autoload['plugin']     = array();
$autoload['config']     = array();
$autoload['language']   = array();
$autoload['model']      = array();
