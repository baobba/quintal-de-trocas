<?php
	
	final class formx_validate_date {
		
		public $has_error = false;
		
		public function __construct($field_object, $args = '') {
			
			$field_post_value 	= $field_object->get_post_value();
			$field_type 		= $field_object->get_type();
			$field_name 		= $field_object->get_name();
			
			$this->has_error = true;
			
			if ($field_type == 'text') {
				if(strlen($field_post_value) == 10){
					$field_post_value = str_replace('/', '-', $field_post_value);
					
					//match the format of the date
					if (preg_match ("/^([0-9]{2})-([0-9]{2})-([0-9]{4})$/", $field_post_value, $parts))
					{
						//check weather the date is valid of not
						if(checkdate($parts[2],$parts[1],$parts[3]))
							$this->has_error = false;
					}
				}
			}
			
			if ($this->has_error === true) {
				
				$field_label = $field_object->get_label();
				$field_label = $field_label != '' ?  $field_label : $field_object->get_name();
				
				$error_msg = $args !== '' ? $args : '';
				$error_msg = $error_msg !== '' ? str_replace(array('[field]'), $field_label, $error_msg) : ('Data inv&aacute;lida (' . $field_label  . ').'); 
				
				$field_object->set_error_message($error_msg);
			}
			
        }
        
	}