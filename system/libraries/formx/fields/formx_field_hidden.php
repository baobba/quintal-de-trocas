<?php
	
	final class formx_field_hidden extends formx_field_basic {
		
		public function __construct($field_name = '', $method = '') {
        	
			parent::set_name($field_name)->set_type('hidden')->set_method($method);
        }
	}