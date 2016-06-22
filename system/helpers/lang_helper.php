<?php

	function __($line = '', $from = 'general')
	{
		if ($line !== '')
		{
			$_SESSION[PROJECT][$from . '_lang'] = get_lang($from);

			$CI =& get_instance();
			$CI->lang->load($from, $_SESSION[PROJECT][$from . '_lang']);
			
			return utf8_encode($CI->lang->line($line));
		}
		return '';
	}
	
	function get_lang($from = 'general')
	{
		$_SESSION[PROJECT][$from . '_lang'] = isset($_SESSION[PROJECT][$from . '_lang']) ? $_SESSION[PROJECT][$from . '_lang'] : 'portuguese';
			
		if (!in_array($_SESSION[PROJECT][$from . '_lang'], array('portuguese', 'english')))
		{
			$_SESSION[PROJECT][$from . '_lang'] = 'portuguese';
		}
		return $_SESSION[PROJECT][$from . '_lang'];
	}
	
	function get_prefix()
	{
		$lang = get_lang();
		if ($lang == 'english')
		{
			return 'en_';
		}else{
			return 'pt_';
		}
	}