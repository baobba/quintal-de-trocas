<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Filter
	{
		
		function Filter()
		{}

		/* --------------------------------------------------------------------------------------- */
		
		function datebr_to_datesql($date = '')
		{
			
			$date = trim($date);
			
			if (strlen($date) < 10 | $date == '00/00/0000 00:00:00' | $date == '00/00/0000')
			{
				return '';
			}
			
			$date = explode(' ', $date);
			
			if (count($date) == 2)
			{
				
				$date1 = explode('/', $date[0]);
				$date[1] = isset($date[1]) ? $date[1] : '00:00:01';
				$hour = explode(':', $date[1]);
				
				return $date1[2] . '-' . $date1[1] . '-' . $date1[0] . ' ' . $hour[0] . ':' . $hour[1] . ':' . $hour[2];
				
			}else{
				
				$date = explode('/', $date[0]);
				
				return $date[2] . '-' . $date[1] . '-' . $date[0];
				
			}
			
		}
		
		function datesql_to_datebr($date = '')
		{
			
			$date = trim($date);
			
			if (strlen($date) < 10 | $date == '0000-00-00 00:00:00' | $date == '0000-00-00')
			{
				return '';
			}
			
			$date = explode(' ', $date);
			
			if (count($date) == 2)
			{
				
				$date1 = explode('-', $date[0]);
				$date[1] = isset($date[1]) ? $date[1] : '00:00:01';
				$hour = explode(':', $date[1]);
				
				return $date1[2] . '/' . $date1[1] . '/' . $date1[0] . ' ' . $hour[0] . ':' . $hour[1] . ':' . $hour[2];
				
			}else{
				
				$date = explode('-', $date[0]);
				
				return $date[2] . '/' . $date[1] . '/' . $date[0];
				
			}
			
		}
		
		/* --------------------------------------------------------------------------------------- */
		
		function fone_to_sql($fone = '')
		{
			$fone = trim($fone);
			
			if (strlen($fone) == 14)
			{
				return str_replace(array(' ', '(', ')', '-'), '', $fone);
			}
			
			return '';
		}
		
		function sql_to_fone($fone = '')
		{
			$fone = trim($fone);
			
			if (strlen($fone) == 10)
			{
				return '(' . $fone{0} . $fone{1} . ') ' . $fone{2} . $fone{3} . $fone{4} . $fone{5} . '-' . $fone{6} . $fone{7} . $fone{8} . $fone{9};
			}
			
			return '';
		}

		/* --------------------------------------------------------------------------------------- */
	
		function remover_acento($str = '')
		{

			$acentos = array(
				'A' => '/&Agrave;|&Aacute;|&Acirc;|&Atilde;|&Auml;|&Aring;/',
				'a' => '/&agrave;|&aacute;|&acirc;|&atilde;|&auml;|&aring;/',
				'C' => '/&Ccedil;/',
				'c' => '/&ccedil;/',
				'E' => '/&Egrave;|&Eacute;|&Ecirc;|&Euml;/',
				'e' => '/&egrave;|&eacute;|&ecirc;|&euml;/',
				'I' => '/&Igrave;|&Iacute;|&Icirc;|&Iuml;/',
				'i' => '/&igrave;|&iacute;|&icirc;|&iuml;/',
				'N' => '/&Ntilde;/',
				'n' => '/&ntilde;/',
				'O' => '/&Ograve;|&Oacute;|&Ocirc;|&Otilde;|&Ouml;/',
				'o' => '/&ograve;|&oacute;|&ocirc;|&otilde;|&ouml;/',
				'U' => '/&Ugrave;|&Uacute;|&Ucirc;|&Uuml;/',
				'u' => '/&ugrave;|&uacute;|&ucirc;|&uuml;/',
				'Y' => '/&Yacute;/',
				'y' => '/&yacute;|&yuml;/',
				'a.' => '/&ordf;/',
				'o.' => '/&ordm;/'
			);

			$str = str_replace(array('&', ';'), '', $str);
			
   			return preg_replace($acentos, array_keys($acentos), htmlentities($str));
		}
		
		/* --------------------------------------------------------------------------------------- */
		
		function cpf_to_sql($cpf = '')
		{

			$cpf = str_replace(array('.', ' ', '-', '/'), '', $cpf);
			
			if (strlen($cpf) == 11)
			{
				return $cpf;
			}
			
			return '';
						
		}
	
		function sql_to_cpf($cpf = '')
		{
			
			if (strlen($cpf) == 11)
			{
				return $cpf{0} . $cpf{1} . $cpf{2} . '.' . $cpf{3} . $cpf{4} . $cpf{5} . '.' . $cpf{6} . $cpf{7} . $cpf{8} . '/' . $cpf{9} . $cpf{10};
			}
			
			return '';
						
		}
		
		/* --------------------------------------------------------------------------------------- */
		
		function cep_to_sql($cep = '')
		{

			$cep = str_replace(array('.', ' ', '-', '/'), '', $cep);
			
			if (strlen($cep) == 8)
			{
				return $cep;
			}
			
			return '';
						
		}
	
		function sql_to_cep($cep = '')
		{
			
			if (strlen($cep) == 8)
			{
				return $cep{0} . $cep{1} . $cep{2} . $cep{3} . $cep{4} . '-' . $cep{5} . $cep{6} . $cep{7};
			}
			
			return '';
						
		}
		
		/* --------------------------------------------------------------------------------------- */
		
		function valor_to_sql($valor = 0)
		{
			
			$valor = str_replace(array('.', ',', 'r$', 'r&#36;', '&#36;', ' '), '', strtolower($valor));
			$valor = intval($valor);
			$valor = str_pad($valor, 3, 0, STR_PAD_LEFT);
			$valor = str_split($valor);
			$valor = array_reverse($valor);
			
			$totalValor = count($valor) - 1;
			
			$novoValor = '';
			
			for($i=$totalValor; $i>=0; $i--)
			{
				$novoValor.= $i == 2 ? $valor[$i] . '.' : $valor[$i];
			}
			
			return $novoValor;
			
		}
		
		function sql_to_valor($valor = 0, $pre = '')
		{
		
			$valor = $this->valor_to_sql($valor);
			
			return $pre . number_format($valor, 2, ',', '.');
			
		}
		
		/* --------------------------------------------------------------------------------------- */
		
		function cnpj_to_sql($cnpj = '')
		{
			
			$cnpj = str_replace(array('.', ' ', '-', '/'), '', $cnpj);
			
			if (strlen($cnpj) >= 14)
			{
				return $cnpj;
			}
			
			return '';
						
		}
		
		function sql_to_cnpj($cnpj = '')
		{
		
			if (strlen($cnpj) == 15)
			{
				
				return 
					$cnpj{0} . $cnpj{1} . $cnpj{2} . 
					'.' . 
					$cnpj{3} . $cnpj{4} . $cnpj{5} . 
					'.' . 
					$cnpj{6} . $cnpj{7} . $cnpj{8} . 
					'/' . 
					$cnpj{9} . $cnpj{10} . $cnpj{11} . $cnpj{12} .
					'-'. 
					$cnpj{13} . $cnpj{14};
					
			}elseif (strlen($cnpj) == 14)
			{
				
				return 
					$cnpj{0} . $cnpj{1} . 
					'.' . 
					$cnpj{2} . $cnpj{3} . $cnpj{4} . 
					'.' . 
					$cnpj{5} . $cnpj{6} . $cnpj{7} . 
					'/' . 
					$cnpj{8} . $cnpj{9} . $cnpj{10} . $cnpj{11} .
					'-'. 
					$cnpj{12} . $cnpj{13};
					
			}
			
			return '';
			
		}
				
	}
