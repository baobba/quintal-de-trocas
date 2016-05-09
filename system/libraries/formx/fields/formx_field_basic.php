<?php
	
    class formx_field_basic {
    	
    	private $field_type 		= '';
    	private $field_name 		= '';
    	private $field_placeholder 	= '';
    	private $field_help 		= '';
    	private $field_label 		= '';
    	private $field_tabindex 	= '';
    	
    	private $field_value 		= '';
    	private $field_post_value 	= '';
    	
    	private $field_is_post = false;
    	
    	private $field_method = 'post';
    	
   		private $field_error_message 	= array();
		private $field_has_error 		= false;
		
		private $field_html_class = '';
		
		private $field_filters   = array();
		private $field_validates = array();
		
		private $on_click = '';
		
		public function __construct() {}
       
		# Field TYPE
    	public function set_type($field_type = '') {
			
			$this->field_type = $field_type;
			return $this;
		}
		
    	public function get_type() {
			
			return $this->field_type;
		}
		
		# ------------------------------------------------ #
		
		# Field NAME
    	public function set_name($field_name = '') {
			
   			$this->field_name = $field_name;
			return $this;
		}
		
		public function get_name() {
			
			return $this->field_name;
		}
		
		# ------------------------------------------------ #
		
		# Field PLACEHOLDER
    	public function set_placeholder($placeholder = '') {
			
   			$this->field_placeholder = $placeholder;
			return $this;
		}
		
		public function get_placeholder() {
			
			return $this->field_placeholder;
		}
		
		# ------------------------------------------------ #
		
		# Field HELP
    	public function set_help($field_help = '') {
			
   			$this->field_help = $field_help;
			return $this;
		}
		
		public function get_help() {
			
			return $this->field_help;
		}
		
		# ------------------------------------------------ #
		
		# Field LABEL
   		public function set_label($field_label = '') {
			
   			$this->field_label = $field_label;
			return $this;
		}
		
    	public function get_label() {
			
			return $this->field_label;
		}
		
		# ------------------------------------------------ #
		
		# Field TABINDEX
    	public function set_tabindex($field_tabindex = '') {
			
			$this->field_tabindex = $field_tabindex;
			return $this;
		}
		
    	public function get_tabindex() {
			
			return $this->field_tabindex;
		}
		
		# ------------------------------------------------ #
		
		# Field VALUE
   		public function set_value($field_value = '') {
			
			$this->field_value = $field_value;
			return $this;
		}
		
		public function get_value() {

			return $this->field_value;
		}
		
		# ------------------------------------------------ #
		
		# Field POST VALUE
    	public function set_post_value($field_post_value = '') {
			
			$this->field_post_value = $field_post_value;
			return $this;
		}
		
    	public function get_post_value() {
			
			return $this->field_post_value;
		}
		
		public function get_posted() {
			
			return $this->field_post_value;
		}
		
		# ------------------------------------------------ #
		
		# Field IS POST
		public function set_is_post($is_post = true) {
			
			$this->field_is_post = (bool) $is_post;
			return $this;
		}
		
    	public function get_is_post() {
			
			return $this->field_is_post;
		}
		
		# ------------------------------------------------ #
		
		# Field METHOD
		public function set_method($method = '') {
			
			$this->field_method = $method;
			return $this;
		}
		
    	public function get_method() {
			
			return $this->field_method;
		}
		
		# ------------------------------------------------ #
		
   		# Field ERROR MESSAGE
		public function set_error_message($error_message = '')
		{
		    $this->set_has_error(true);

		    foreach ($this->field_error_message as $message) {
		        if ($message == $error_message) {
		            return $this;
		        }
		    }

			$this->field_error_message[] = $error_message;
			
			return $this;
		}
		
    	public function get_error_message() {
			
			return $this->field_error_message;
		}
		
		# ------------------------------------------------ #
		
		# Field HAS ERROR
		public function set_has_error($has_error = true) {
			
			$this->field_has_error = (bool) $has_error;
			return $this;
		}
		
    	public function get_has_error() {
			
			return $this->field_has_error;
		}
		
		# ------------------------------------------------ #
		
		# Field HTML CLASS
    	public function set_html_class($html_class = '') {
			
			$this->field_html_class = implode(' ', is_array($html_class) ? $html_class : array($html_class));
			return $this;
		}
		
		public function get_html_class() {
			
			return $this->field_html_class;
		}
		
		# ------------------------------------------------ #
		
		# Field FILTERS
		public function set_filters($filters = array()) {
			
			$this->field_filters = is_array($filters) ? $filters : array($filters);
			return $this;
		}
		
		public function get_filters() {
			
			return $this->field_filters;
		}
		
		# ------------------------------------------------ #
		
		# Field VALIDATES
		public function set_validates($validates) {

			$this->field_validates = is_array($validates) ? $validates : array($validates);
			return $this;
		}
		
    	public function get_validates() {
			
			return $this->field_validates;
		}
		
		# ------------------------------------------------ #
		
		# Field CLICK
		public function set_onclick($on_click) {
		
		    $this->on_click = $on_click;
		    return $this;
		}
		
		public function get_onclick() {
		    	
		    return $this->on_click;
		}
		
		# ------------------------------------------------ #
		
		public function is_ready() {
			
			$this->retrieve();
			
			if ($this->get_type() !== 'submit_button') {
				
				$this->validate_field();
				$this->filter_field();	
			}
		}
		
		private function retrieve() {

			$method = $this->get_method();
			
			if ($method === 'file') {
				
				$this->set_post_value(isset($_FILES[$this->get_name()]) ? $_FILES[$this->get_name()] : array('name'=>'', 'type'=>'', 'tmp_name'=>'', 'error'=>true, 'size'=>0));
				
			} else {
				
				$method = $method == 'post' ? $_POST : $_GET;
				$this->set_post_value(isset($method[$this->get_name()]) ? $method[$this->get_name()] : '');
				
			}
			
			unset($method);
			
			$this->set_is_post(true);
		}
		
		private function filter_field() {
			
			$filters = $this->get_filters();
			
			if (count($filters) == 0 | $this->get_has_error()) {
				
				return;
			}
			
			foreach ($filters as $filter => $args) {
				
				require_once dirname(__FILE__) . '/formx_filter_' . $filter . '.php';
				
				$filter = 'formx_filter' . $filter;
				$filter = new $filter($this, $args);
				
				new $filter($this, $args);
			}
		}
		
		private function validate_field() {
			
			$validates = $this->get_validates();
			
			if (count($validates) == 0) {
				
				return;
			}
			
			foreach ($validates as $validate => $args) {
				
				$validate = (string) $validate;
				
				if (is_numeric($validate)) {
					
					$validate 	= (string) $args;
					$args 		= '';
				}
				
				require_once dirname(__FILE__) . '/../validates/formx_validate_' . $validate . '.php';
				
				$validate = 'formx_validate_' . $validate;
				$validate = new $validate($this, $args);
								
				if ($validate->has_error) {
					
					$this->set_has_error(true);
					
					return;
				}
				
			}
		}
		
		public function reset() {
			
			$this->set_value();
			$this->set_post_value();
		}
    }