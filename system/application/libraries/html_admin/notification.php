<?php

	class notification
	{
	
		private $data = array(
			'can_clonse' 	=> true,
		);
		
		private $messages = array('success', 'warning', 'error', 'information');
		
		public function __construct()
		{}
		
		public function can_close($can_close = true)
		{
			$this->data['can_close'] = $can_close;
			return $this;
		}
		
		public function set_message($kind, $message, $title = '')
		{
			$kind = (in_array($kind, $this->messages)) ? $kind : 'error';
			
			if ($title == '')
			{
				switch (true)
				{
					case $kind == 'success':
						$title = 'Sucesso';
						break;
					case $kind == 'warning':
						$title = 'Aviso';
						break;
					case $kind == 'error':
						$title = 'Erro';
						break;
					case $kind == 'information':
						$title = 'Informa&ccedil;&atilde;o';
						break;
				}
			}
			
			$this->data['kind']		= $kind;
			$this->data['title']	= $title;
			$this->data['message'] 	= $message;
			
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