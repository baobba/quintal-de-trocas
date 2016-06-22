<?php
	
	final class formx_validate_hour {
		
		public $has_error = false;
		
		public function __construct($field_object, $args = '') {
			
			$field_post_value 	= $field_object->get_post_value();
			$field_type 		= $field_object->get_type();
			$field_name 		= $field_object->get_name();
			
			$this->has_error = true;
			
			if ($field_type == 'text') {
				if(strlen($field_post_value) == 5){
					//match the format of the hour
					if (preg_match ("/^([0-9]{2}):([0-9]{2})$/", $field_post_value, $parts))
					{
						if($parts[1] < 24 && $parts[2] < 60)						
							$this->has_error = false;
					}
				}
			}
			
			if ($this->has_error === true) {
				
				$field_label = $field_object->get_label();
				$field_label = $field_label != '' ?  $field_label : $field_object->get_name();
				
				$error_msg = $args !== '' ? $args : '';
				$error_msg = $error_msg !== '' ? str_replace(array('[field]'), $field_label, $error_msg) : ('Hora inv&aacute;lida (' . $field_label  . ').'); 
				
				$field_object->set_error_message($error_msg);
			}
			
        }
        
	}