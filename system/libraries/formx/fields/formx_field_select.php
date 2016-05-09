<?php
	
	final class formx_field_select extends formx_field_basic {
		
		public function __construct($field_name = '', $method = '') {
        	
			parent::set_name($field_name)->set_type('select')->set_method($method);
        }
        
        final public function set_value($new_field = array(), $selected = '') {
        	
			$new_field 	= is_array($new_field) ? $new_field : array($new_field);
			$index 		= 0;
			
			$selected = (string) $selected;
			$field_value = array();
			
			foreach ($new_field as $new_field_value => $new_field_label) {
				
				$new_field_value = (string) $new_field_value;
				
				$field_value[$index]['field_value'] 	= $new_field_value;
				$field_value[$index]['field_label'] 	= $new_field_label;
				$field_value[$index]['selected'] 		= $selected === $new_field_value ? true : false;

				$index++;
			}
						
			return parent::set_value($field_value);
        }
        
        final function set_selected($selected = 0) {
        	
        	$value = self::get_value();
        	
        	$new_value = array();
        	
       		foreach ($value as $k => $field) {
				
       			$new_value[$field['field_value']] = $field['field_label'];
			}
			
			return self::set_value($new_value, $selected);
        }
        
        final public function set_post_value($selected = '') {
        	
        	$selected = (string) $selected;
			
			$field_value 		= parent::get_value();
			$field_post_value 	= array();
			
			$total = count($field_value);
			
			for($i=0; $i<$total; $i++) {
				
				$field_post_value[$i]['field_value'] 	= $field_value[$i]['field_value'];
				$field_post_value[$i]['field_label'] 	= $field_value[$i]['field_label'];
			    $field_post_value[$i]['selected'] 		= ($selected == $field_value[$i]['field_value'] ? true : false);
			}
			
			unset($field_value);
			
			return parent::set_post_value($field_post_value);
        }
        
		final public function get_posted() {
			
			$field_post_value = parent::get_post_value();
			$field_post_value = $field_post_value == '' ? array() : $field_post_value;
			
			foreach ($field_post_value as $propriety => $post_value) {
				
				if ($post_value['selected']) {
					
					return $post_value['field_value'];
				}
			}
				
			return '';
		}
		
		final function reset() {
        	
        	$field_value 		= parent::get_value();
        	$field_post_value 	= parent::get_post_value();
        	
        	$total = count($total);
				
			for($i=0; $i<$total; $i++) {
				
			    $field_value[$i]['selected'] = false;
			    
			    if (isset($field_post_value[$i])) {

			    	$field_post_value[$i]['selected'] = false;
			    }
			    
			}
			
			parent::set_value($field_value)->set_post_value($field_post_value);
        }
	}