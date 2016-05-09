<?php

	class table
	{
	
		private $data = array(
			'table' 			=> array(),
			'check_all'			=> true,
			'update_url'		=> '',
			'update_url'		=> '',
			'update_url_txt'	=> '',
		);
		
		private $line = 0;
		
		public function __construct()
		{}
		
		public function set_check_all($check_all = true)
		{
			$this->data['check_all'] = $check_all;
		}
		
		public function set_update_url($update_url, $update_url_txt = 'Editar')
		{
			$this->data['update_url'] 		= $update_url;
			$this->data['update_url_txt'] 	= $update_url_txt;
		}
		
		public function set_delete_url($delete_url, $delete_url_txt = 'Apagar')
		{
			$this->data['delete_url']		= $delete_url;
			$this->data['delete_url_txt'] 	= $delete_url_txt;
		}
		
		public function reset()
		{
			$this->data = array(
				'table' 			=> array(),
				'check_all'			=> true,
				'update_url'		=> '',
				'update_url'		=> '',
				'update_url_txt'	=> '',
			);
		}
		
		public function add($header, $content)
		{
			if (isset($this->data['table'][$this->line]))
			{
				if (isset($this->data['table'][$this->line][$header]))
				{
					$this->line++;
				}
			}
			$this->data['table'][$this->line][$header] = $content;
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