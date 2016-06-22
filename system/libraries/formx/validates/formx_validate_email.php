<?php
	
	final class formx_validate_email {
		
		public $has_error = false;
		
		public function __construct($field_object, $args = '') {
			
			$field_post_value 	= $field_object->get_post_value();
			$field_type 		= $field_object->get_type();
			$field_name 		= $field_object->get_name();
			
			$this->has_error = true;
			
			if ($field_type == 'text') {
				
				if (preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/', $field_post_value) && filter_var($field_post_value, FILTER_VALIDATE_EMAIL)) {

					$this->has_error = false;
				}
			}
			
			if ($this->has_error === true) {
				
				$field_label = $field_object->get_label();
				$field_label = $field_label != '' ?  $field_label : $field_object->get_name();
				
				$error_msg = $args !== '' ? $args : '';
				$error_msg = $error_msg !== '' ? str_replace(array('[field]'), $field_label, $error_msg) : ('E-mail inv&aacute;lido (' . $field_label  . ').'); 
				
				$field_object->set_error_message($error_msg);
			}
			
        }
        
	}