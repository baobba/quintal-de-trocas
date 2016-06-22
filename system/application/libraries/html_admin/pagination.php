<?php

	class pagination
	{
	
		private $data = array(
			'query' 			=> '',
			'itens_per_page'	=> 15,
			'total_ages' 		=> 1,
			'total_real'		=> 1,
			'total_current'  	=> 1,
			'current_page'		=> 1,
			'filters' 			=> array(),
			'current_filter' 	=> '',
			'url'				=> '',
		);
		
		public function __construct()
		{
			$this->set_query();
		}
		
		public function set_filters($filters = array())
		{
			$filters_tmp = array();
			
			if (count($filters) > 0) {
				$filter_selected = isset($_REQUEST['filter_by']) ? $_REQUEST['filter_by'] : false; 
				
				foreach ($filters as $filter => $db_field) {
					$selected = false;
					
					if ($filter_selected == $db_field) {
						$this->set_current_filter($db_field);	
						$selected = true;
					}
					
					$filters_tmp[$filter]['selected'] 	= $selected;
					$filters_tmp[$filter]['dbField'] 	= $db_field;
				}
				
			}
			$this->data['filters'] = $filters_tmp;
		}
		
		public function set_url($url)
		{
			$this->data['url'] = $url;
		}
		
		public function get_filter()
		{
			$current_filter = $this->get_current_filter();
			$query = $this->get_query();
			
			if ($current_filter !== '' && $query !=='')
			{
				return array($current_filter, $query);
			}
			return false;
			
		}
		
		public function set_current_filter($db_field = '')
		{
			$this->data['current_filter'] = $db_field;
		}
		
		public function get_current_filter()
		{
			return $this->data['current_filter'];	
		}
		
		public function set_query($query = '')
		{
			$this->data['query'] = isset($_REQUEST['query']) ? trim($_REQUEST['query']) !== '' ? $_REQUEST['query'] : $query : $query;
		}
		
		public function get_query()
		{
			return trim($this->data['query']) !== '' ? $this->data['query'] : false; 
		}
		
		public function set_total_real($totalReal)
		{
			$this->data['total_real'] = 'Total de' . ': ' . $totalReal . ($totalReal > 1 ?  ' registros' : ' registro');  
		}
		
		public function set_current_total($current_total)
		{
			$this->data['total_current'] = ' - Exibindo: ' . $current_total . ($current_total > 1 ? ' registros' : ' registro') . ' nesta p&aacute;gina';  	
		}
		
		public function set_total_pages($total_pages = 0)
		{
			
			$this->set_total_real($total_pages);
			
			$total_pages = intval($total_pages);
			$total_pages = ceil($total_pages / $this->get_itens_per_page());
			
			$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
			$page = ($page > 1 && $page <= $total_pages) ? $page : 1;
			
			$this->data['current_page'] = $page;
			$this->data['total_pages'] 	= $total_pages; 
		}
		
		public function get_limit()
		{
			return ($this->data['current_page'] - 1) * $this->get_itens_per_page();
		}
		
		public function get_itens_per_page()
		{
			return $this->data['itens_per_page'];
		}
		
		public function set_itens_per_page($itens_per_page = 1)
		{
			$itens_per_page = intval($itens_per_page);
			$this->data['itens_per_page'] = $itens_per_page > 0 ? $itens_per_page : 1; 
		}
		
		/**
		 * @desc
		 */
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