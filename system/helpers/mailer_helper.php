<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
if ( ! function_exists('_mail'))
{
    include_once __DIR__ . '/../libraries/phpmailer/class.phpmailer.php';
    include_once __DIR__ . '/../libraries/phpmailer/class.pop3.php';
    include_once __DIR__ . '/../libraries/phpmailer/class.smtp.php';
    
	function _mail($to, $subject, $template, $params) {
		$CI =& get_instance();

        $message = $CI->load->view('mail_template/' . $template, $params, true);
        $message = utf8_decode($message);
        
        send_message('smtp.gmail.com', 'system@quintaldetrocas.com.br', '3+AdEBbf83925f.654eb91', 'contato@quintaldetrocas.com.br', 'Quintal de Trocas', $to, null, $subject, $message);
        
		return true;
	}	
}
