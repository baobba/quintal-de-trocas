<?php
	
	final class formx_validate_number {
		
		public $has_error = false;
		
		public function __construct($field_object, $args = '') {
			
			$field_post_value 	= $field_object->get_post_value();
			$field_type 		= $field_object->get_type();
			$field_name 		= $field_object->get_name();
			
			
			$this->has_error = true;
			
			if(is_numeric($field_post_value)){
				$this->has_error = false;
			}
			
			if ($this->has_error === true) {
				
				$field_label = $field_object->get_label();
				$field_label = $field_label != '' ?  $field_label : $field_object->get_name();
				
				$error_msg = $args !== '' ? $args : '';
				$error_msg = $error_msg !== '' ? str_replace(array('[field]'), $field_label, $error_msg) : ('Campo (' . $field_label  . ') inv&aacute;lido'); 
				
				$field_object->set_error_message($error_msg);
			}
			
        }
        
	}