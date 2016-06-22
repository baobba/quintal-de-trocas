<?php
	
	final class formx_field_password extends formx_field_basic {
		
		public function __construct($field_name = '', $method = '') {
        	
			parent::set_name($field_name)->set_type('password')->set_method($method);
        }
	}