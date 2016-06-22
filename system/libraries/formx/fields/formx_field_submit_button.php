<?php
	
	final class formx_field_submit_button extends formx_field_basic {
		
		public function __construct($field_name = '', $method = '') {
        	
			parent::set_name($field_name)->set_type('submit_button');
        }
	}