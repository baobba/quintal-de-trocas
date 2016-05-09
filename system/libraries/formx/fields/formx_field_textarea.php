<?php
	
    final class formx_field_textarea extends formx_field_basic {
    	
    	private $flag_use_fckeditor 		= false;
    	private $fckeditor_options 	= array(
    		'width' 		=> '650',
    		'height' 		=> '350',
    		'toolbar_set' 	=> 'default',
    		'config'		=> array(),
    	);
    	
		public function __construct($field_name = '', $method = '') {

			parent::set_name($field_name)->set_type('textarea')->set_method($method);
        }
        
        final public function use_fckeditor($fckeditor_options = array()) {
        	
        	$this->fckeditor_options 	= $fckeditor_options + $this->fckeditor_options;
        	$this->flag_use_fckeditor 	= true;
        	
        	return $this;
        }
        
        final public function get_use_fckeditor() {
        	
        	return $this->flag_use_fckeditor;
        }
        
     	final public function get_fckeditor_config() {
        	
        	return $this->fckeditor_options;
        }
         
    }