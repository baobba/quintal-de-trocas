<?php
	
	class UiPagination {
		
		private $max_pages 		= 0;
		private $current_page 	= 0;
		private $delta 			= 5;
		private $base_url 		= '';
		
		public $limit 	= 0;
		public $offset 	= 0;
		
		private $filters = array();
		private $query 	= '';
		private $filter = '';
		
		public function __construct() {}
		
		public function get_url($page)
		{
		    return str_replace('[PAGE]', $page, $this->base_url);
		}
		
		public function set_pagination($max_pages = 0, $current_page = 0, $itens_per_page = 10, $delta = 0) {
			
			$itens_per_page = (int) $itens_per_page;
			$itens_per_page = $itens_per_page <= 0 ? 10 : $itens_per_page;
			
			$current_page = (int) $current_page;
			$current_page = $current_page <= 0 ? 0 : $current_page;
			
			$max_pages 		= (int) ceil($max_pages / $itens_per_page);
			$current_page 	= $current_page > $max_pages ? $max_pages : $current_page;
			
			$offset = ($current_page - 1) * $itens_per_page;
			$offset	= $offset <= 0 ? 0 : $offset;
			
			$this->delta 		= $delta;
			$this->limit 		= $itens_per_page;
			$this->offset 		= $offset;
			$this->current_page = $current_page;
			$this->max_pages 	= $max_pages;
			
		}
		
		public function set_base_url($base_url = '') {
			
			$this->base_url = $base_url;
		}
		
		public function generate() {
			
			$current_page 	= (int) $this->current_page;
			$max_pages 		= (int) $this->max_pages;
			$delta 			= (int) $this->delta;
			$delta 			= $delta <= 1 ? 1 : $delta;
			
			$pagination = array();
			$pagination['current'] 	= $current_page;
			$pagination['first'] 	= '';
			$pagination['prev'] 	= '';
			$pagination['pages']	= array();
			$pagination['next'] 	= '';
			$pagination['last'] 	= '';
			
			if ($max_pages > 0) {
				
				if (($delta + 2) < $max_pages) {
					
					# first+delta
					if (($delta + 1) >= $current_page) {
						
						$pagination['pages'] = range(2, ++$delta);
						
					# delta+last
					} elseif (($max_pages - $current_page) <= $delta) {

						$pagination['pages'] = range($max_pages - $delta, $max_pages - 1);

					# boxes
					} else {
						
						$boxes = floor(($max_pages - $delta - 1) / $delta);

						$min = $current_page;
						$max = $current_page + $delta - 1;
						
						for ($i = 1; $i <= $boxes; $i++) {
							
							$max = ($i * $delta) + $delta + 1;
							$min = $max - $delta + 1;
							
							if ($current_page <= $max && $current_page >= $min) {
								
								break;
							}
						}
						
						$pagination['pages'] = range($min, $max);
					}
					
					$pagination['first'] = 1;
					$pagination['last'] = $max_pages;
					
				} else {
					$pagination['pages'] = range(1, $max_pages);
				}
				
			}
			
			$pagination['next'] = $current_page < $max_pages ? $current_page + 1 : '';
			$pagination['prev'] = $max_pages > 1 && $current_page -1 > 0 ? $current_page - 1 : '';

			return $pagination;
		}
	}