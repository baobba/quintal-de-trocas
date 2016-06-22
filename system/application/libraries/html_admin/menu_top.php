<?php

class menu_top {
    
	private $data = array('menu' => array());
	
	public function add_menu($menu = '', $link = '')
	{
		$current = count($this->data['menu']);
		
		$this->data['menu'][$current]['menu'] = utf8_encode($menu);
		
		if ($link !== '')
		{
			$link = base_url() . 'admin/' . $link;
		}else{
			$link = '#';
		}
		
		$this->data['menu'][$current]['link'] = $link;
		
		return $this;
	}
	
	public function add_submenu($menu, $link)
	{
		$current 			= count($this->data['menu']) - 1;
		$current_submenu 	= $this->next_submenu($current);
		
		if ($link !== '') {
			$link = base_url() . 'admin/' . $link;
			
		} else {
			$link = '#';
		}
		
		$this->data['menu'][$current]['submenu'][$current_submenu]['menu'] = utf8_encode($menu);
		$this->data['menu'][$current]['submenu'][$current_submenu]['link'] = $link;
		
		return $this;
	}
	
	private function next_submenu($current)
	{
		$next = 0;
		
		if (isset($this->data['menu'][$current])) {
			if (isset($this->data['menu'][$current]['submenu'])) {
				$next = count($this->data['menu'][$current]['submenu']); 
			}
		}
		
		return $next;
	}
	
	public function render($after = true)
	{
		$this->add_menu('Home', 'home');
		
		$this->add_menu('Configura&ccedil;&atilde;o')
			 ->add_submenu('Usu&aacute;rios', 'users')
			 ->add_submenu('Grupos de Usu&aacute;rios', 'profiles')
			 ->add_submenu('P&aacute;ginas', 'pages');
		
		$this->add_menu('Clientes', 'client');
		
		$this->add_menu('Not&iacute;cias')
		     ->add_submenu('Not&iacute;cia', 'news')
		     ->add_submenu('Autores', 'news_author')
		     ->add_submenu('Categorias', 'news_category');
		
		$this->add_menu('Parceiros', 'partner');
		
		$this->add_menu('Pontos de Troca', 'exchange_point');
		
		$this->add_menu('Brinquedos')
             ->add_submenu('Listagem', 'toy')
		     ->add_submenu('Categorias', 'toy_category')
		     ->add_submenu('Cidades', 'toy_city')
		     ->add_submenu('Estados', 'toy_state')
		     ->add_submenu('Marcas', 'toy_brand')
		     ->add_submenu('Faixa Et&aacute;ria', 'toy_age');
		
		$this->add_menu('Conte&uacute;dos', 'content');
		
		$this->add_menu('FAQ', 'faq');
				
		$this->add_menu('M&iacute;dia')
		     ->add_submenu('M&iacute;dia', 'press')
		     ->add_submenu('Categorias', 'press_category');

		$CI =& get_instance();
		
		return $CI->load->view(
			'admin/' . strtolower(__CLASS__),
			$this->data,
			$after
		);
	}
}