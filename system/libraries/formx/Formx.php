<?php

	class Formx {

		/**
		 * All vars are declared private so you need to use
		 * setters and getters outside $this context
		 */

		private $form_fields 	= array();
		private $form_errors 	= array();
		private $current_field 	= '';
		private $form_legend 	= '';
		private $form_name 		= 'formx';
		private $form_method 	= 'post';
		private $form_action 	= '';
		private $form_class 	= '';
		private $form_id 		= '';
		private $is_sucess 		= false;
		
		private $has_error = false;
		
		const METHOD_POST = 'post';
		const METHOD_GET = 'get';
		
		/**
		 * Needed for file upload
		 */
		private $form_enctype = 'multipart/form-data';
		
		public function __construct($form_method = self::METHOD_POST, $form_action = '', $form_id = '', $form_class = '', $form_name = 'formx', $form_enctype = 'multipart/form-data') {

			$form_method 	= strtolower($form_method);
			$form_name 		= $form_name == '' ? 'formx' : $form_name;
			
			# Allows only post or get for security purpose
			$this->form_method 	= in_array($form_method, array(self::METHOD_POST, self::METHOD_GET)) ? $form_method : self::METHOD_POST;
			$this->form_name 	= $form_name;
			$this->form_action 	= $form_action;
			$this->form_class 	= $form_class;
			$this->form_id 		= $form_id;
			$this->form_enctype = $form_enctype;
			
			require_once dirname(__FILE__) . '/fields/formx_field_basic.php';
			
			# Sets a hidden. Purpose:
			# 1 - Prevents CSRF Attack -> http://en.wikipedia.org/wiki/Cross-site_request_forgery
			# 2 - Verify if this form was submitted on is_post method
			$this->add_hidden($form_name)->set_validates('required')->set_name($form_name)->set_value(md5($form_name));
		}
		
		public function get_form_action() {
			
			return $this->form_action;
		}
		
		public function set_form_action($form_action) {
			
			$this->form_action = $form_action;
		}
		
		public function set_form_legend($form_legend) {
			
			$this->form_legend = $form_legend;
		}
		
		public function get_form_legend() {
			
			return $this->form_legend;
		}
		
		public function get_form_name() {
			
			return $this->form_name;
		}
		
		public function get_form_method() {
			
			return $this->form_method;
		}
		
		public function get_form_enctype() {
			
			return $this->form_enctype;
		}
		
		public function get_form_id() {
			
			return $this->form_id;
		}
		
		public function get_form_class() {
			
			return $this->form_class;
		}
		
		public function get_form_errors() {
			
			return $this->form_errors;
		}
		
		public function get_form_fields() {
			
			return $this->form_fields;
		}
		
		public function set_form_errors($form_errors = '') {
			
			$form_errors = is_array($form_errors) ? $form_errors : array($form_errors);
			$this->form_errors = $this->form_errors + $form_errors;
			$this->has_error = true;
		}
		
		/**
		 * @return formx_field_checkbox
		 */
		public function add_checkbox($field_name = '') {
			
			return $this->add_field('checkbox', $field_name);
		}
		
		/**
		 * @return formx_field_file
		 */
		public function add_file($field_name = '') {
			
			return $this->add_field('file', $field_name);
		}
		
		/**
		 * @return formx_field_hidden
		 */
		public function add_hidden($field_name = '') {
			
			return $this->add_field('hidden', $field_name);
		}
		
		/**
		 * @return formx_field_multiple
		 */
		public function add_multiple($field_name = '') {
			
			return $this->add_field('multiple', $field_name);
		}
		
		/**
		 * @return formx_field_password
		 */
		public function add_password($field_name = '') {
			
			return $this->add_field('password', $field_name);
		}
		
		/**
		 * @return formx_field_radio
		 */
		public function add_radio($field_name = '') {
			
			return $this->add_field('radio', $field_name);
		}
		
		/**
		 * @return formx_field_select
		 */
		public function add_select($field_name = '') {
			
			return $this->add_field('select', $field_name);
		}
		
		/**
		 * @return formx_field_text
		 */
		public function add_text($field_name = '') {
			
			return $this->add_field('text', $field_name);
		}
		
		/**
		 * @return formx_field_textarea
		 */
		public function add_textarea($field_name = '') {
			
			return $this->add_field('textarea', $field_name);
		}
		
		/**
		 * @return formx_field_reset_button
		 */
		public function add_reset_button($field_name = '') {
			
			return $this->add_field('reset_button', $field_name);
		}
		
		/**
		 * @return formx_field_submit_button
		 */
		public function add_submit_button($field_name = '') {
			
			return $this->add_field('submit_button', $field_name);
		}
		
		/**
		 * @return formx_field_button
		 */
		public function add_button($field_name = '') {
			
			return $this->add_field('button', $field_name);
		}

		/**
		 * Verify if this form was submitted
		 */
		public function is_post()
		{
			if ($this->is_posted()) {
			    foreach ($this->form_fields as $field_object) {
			        $field_object->is_ready();
			    }

				return true;
			}
			
			return false;
		}
		
		public function is_posted()
		{
		    $method = $this->get_form_method() == 'post' ? $_POST : $_GET;
		    
		    if (isset($method[$this->get_form_name()])) {
		        if ($method[$this->get_form_name()] == md5($this->get_form_name())) {
		            return true;
		        }
		    }
		    
		    return false;
		}
		
		public function has_error() {
			
			if (count($this->form_fields) == 0) {
				return true;
			}
			
			$has_error = $this->has_error;
			
			foreach ($this->form_fields as $k => $field_object) {
				if ($field_object->get_has_error() == true) {
				    
					# Merge field error with $this->form_errors
					# So you can use form_errors wherever you want
					$this->form_errors = array_merge(
						$this->form_errors,
						$field_object->get_error_message()
					);
					
					$has_error = true;
				}
			}
			
			$this->has_error = $has_error;
			return $has_error;
			
		}
		
		private function add_field($field_type = '', $field_name = '') {
			
			$this->current_field = $field_name;
			
			require_once dirname(__FILE__) . '/fields/formx_field_' . $field_type . '.php';
			
			$field_class = 'formx_field_' . $field_type;
			$field_class = new $field_class();
			
			$this->form_fields[$field_name] = new $field_class($field_name, $this->form_method);
			
			return $this->form_fields[$field_name];
		}
		
		public function get_posted($field_name = '') {
			
			if (isset($this->form_fields[$field_name])) {
				
				return $this->form_fields[$field_name]->get_posted();
			}
			
			return '';
		}
		
		public function update_value($field_name = '', $new_value = '') {
			
			if (isset($this->form_fields[$field_name])) {
				
				return $this->form_fields[$field_name]->set_value($new_value);
			}
			
			return false;
		}
		
		/**
		 * @return formx_field_basic
		 */
		public function use_field($field_name = '') {
			
			if (isset($this->form_fields[$field_name])) {
				
				return $this->form_fields[$field_name];
			}
			
			return false;
		}
		
		/**
		 * @return formx_field_builder
		 */
		public function remove_field($field_name = '') {
			
			$this->form_fields[$field_name] = array();
			unset($this->form_fields[$field_name]);
			
			return $this;
		}
		
		public function set_is_sucess()
		{
		    $this->is_sucess = true;
		}
		
		public function is_sucess()
		{
		    return $this->is_sucess;
		}
		
		public function reset()
		{
		    foreach($this->form_fields as $field) {
		        $field->set_post_value('');
		    }
		}
		
		public function render() {
			
		    $CI =& get_instance();
		    return $CI->load->view('admin/formx', array(
                        'form_class'    => $this->get_form_class(),
			            'form_action' 	=> $this->get_form_action(),
			            'form_method' 	=> $this->get_form_method(),
			            'has_error' 	=> $this->has_error,
			            'form_id' 		=> $this->get_form_id(),
			            'form_legend' 	=> $this->get_form_legend(),
			            'fields' 		=> $this->get_form_fields(),
			), true);
		}
	}