<?php

	class dialog
	{
		private $data = array(
			'config' => array(
				'auto_open' => false,
				'buttons'	=> '{}',
				'draggable'	=> false,
				'height'	=> 'auto',
				'width'		=> 500,
				'maxHeight'	=> false,
				'maxWidth'	=> false,
				'modal'		=> true,
				'resizable'	=> false,
			)
		);
		
		public function __construct()
		{}
		
		public function set_opener($content = '', $title = '')
		{
			$this->data['opener']['content'] 	= $content;
			$this->data['opener']['title'] 		= $title;
		}
		
		public function set_content($content, $title = '')
		{
			$this->data['content']['title'] 	= $title;
			$this->data['content']['content'] 	= $content;
		}
		
		public function merge_config($configs)
		{
			foreach($configs as $this->data['config'] => $config)
			{
				
				if (isset($this->data['config'][$key]))
				{
					$this->data['config'][$key] = $config;
				}
			}
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
