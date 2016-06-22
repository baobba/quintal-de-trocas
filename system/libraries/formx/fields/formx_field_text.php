<?php
	
	final class formx_field_text extends formx_field_basic {
		
		public function __construct($field_name = '', $method = '') {
        	
			parent::set_name($field_name)->set_type('text')->set_method($method);
        }
	}