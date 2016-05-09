<?php
	
	final class formx_field_button extends formx_field_basic {
		
		private $field_onclick = '';
		
		public function __construct($field_name = '', $method = '') {
        	
			parent::set_name($field_name)->set_type('button');
        }
        
        public function set_onclick($onclick = '') {
        	
        	$this->field_onclick = $onclick;
        	
        	return $this;
        }
        
        public function get_onclick($onclick = '') {
        	 
        	return $this->field_onclick;
        }
	}