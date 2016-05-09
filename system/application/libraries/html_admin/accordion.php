<?php

	class accordion
	{
		private $data 	= array();
		private $sum 	= 0;
		
		public function __construct()
		{}
		
		public function add_accordion($title, $content)
		{
			$this->data['accordion'][$this->sum]['title'] 	= $title;
			$this->data['accordion'][$this->sum]['content']	= $content;
			
			$this->sum++;

			return $this;
		}
		
		public function render($after = true)
		{
			$CI =& get_instance();
			
			return $CI->load->view(
				'admin/' . strtolower(__CLASS__),
				$this->data,
				$after
			);
		}
	}
