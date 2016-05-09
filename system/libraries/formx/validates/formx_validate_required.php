<?php
	
	final class formx_validate_required {
		
		public $has_error = false;
		
		public function __construct($field_object, $args = '') {
			
			$field_post_value 	= $field_object->get_post_value();
			$field_type 		= $field_object->get_type();
			$field_name 		= $field_object->get_name();
			
			switch (true) {
				
				case in_array($field_type, array('hidden', 'password', 'text', 'textarea')):
					
					$this->has_error = empty($field_post_value) && !is_numeric($field_post_value);
					break;
	
				case in_array($field_type, array('checkbox', 'multiple', 'radio', 'select')):
					
					$this->has_error = true;
					
					foreach($field_post_value as $post_value) {

						if ($post_value['selected'] === true && $post_value['field_value'] !== '') {
							
							$this->has_error = false;
							break;
						}
					}
					
					break;
	
				case $field_type == 'file':
					
					$this->has_error = true;
					
					if (isset($_FILES[$field_name])) {
						
						if (is_file($_FILES[$field_name]['tmp_name'])) {
							
							$this->has_error = false;
						}
					}
					
					break;
				
				default:
					break;
			}
			
			if ($this->has_error === true) {
				
				$field_label = $field_object->get_label();
				$field_label = $field_label != '' ?  $field_label : $field_object->get_name();
				
				$error_msg = $args !== '' ? $args : '';
				$error_msg = $error_msg !== '' ? str_replace(array('[field]'), $field_label, $error_msg) : ('Campo (' . $field_label  . ') necess&aacute;rio'); 
				
				$field_object->set_error_message($error_msg);
			}
			
        }
        
	}