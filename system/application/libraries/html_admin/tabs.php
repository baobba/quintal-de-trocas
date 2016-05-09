<?php

	class tabs
	{
	
		private $data 	= array();
		private $sum 	= 0;
		
		public function __construct()
		{}
		
		public function add_tab($title, $content, $selected = false)
		{
			$this->data['tabs'][$this->sum]['title'] 	= $title;
			$this->data['tabs'][$this->sum]['content']	= $content;
			$this->data['tabs'][$this->sum]['selected']	= $selected;
			
			$this->sum++;

			return $this;
		}
		
		public function render($after = true)
		{
			$CI =& get_instance();
			
			return $CI->load->view(
				'admin/' . strtolower(__CLASS__) ,
				$this->data,
				$after
			);
		}
			
	}
