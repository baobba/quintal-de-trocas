<?php

class admin_client extends Model {
    
	public $table = '';
	public $entity;
	public $url;
	
	public function __construct()
	{
		parent::Model();
		$this->table = str_replace('admin_', '', __CLASS__);	
	}
	
	public function getUrl()
	{
		return $this->url;
	}

	public function setNotification($message, $kind, $can_close = false)
	{
		$CI =& get_instance();
    	$CI->load->library('html_admin/notification', 'notification');
		
		return $CI->notification->set_message($kind, $message)->can_close($can_close)->render();
	}
	
	public function getTable()
	{
		return $this->table;
	}
	
	public function getBackBtn($txt = '', $url = '')
	{
		$txt = $txt == '' ? 'Voltar' : $txt;
		$url = $url == '' ?  base_url() . 'admin/' . $this->getUrl() : $url;
		
		return '
			<br />
			<input 
				class="button"
				type="button"
				value="' . $txt . '"
				onclick="window.location.href=\'' . $url . '\';"
			/>
		';
	}

	public function create($hasPermission = false)
	{
		$backBtn = $this->getBackBtn();
		
		if (!$hasPermission) {
			//return $this->setNotification('Voc&ecirc; n&atilde;o tem permiss&atilde;o' . $backBtn, 'error');
		}
		
		$formx = $this->form();
		$formx->set_form_action(base_url() . 'admin/' . $this->getUrl() . '/create');
		
		$return = '';
		
		if ($formx->is_post()) {
			if (!$formx->has_error()) {
			    $slug = slug($formx->use_field('name')->get_posted());
			    
			    $this->load->helper('formx');
			    
			    $cover_image = null;
			    if ($formx->use_field('cover_image')->upload_file(array('uploadPath' => './uploads/image', 'allowed' => 'jpeg|jpg|bmp|gif|png'))) {
			        $cover_image = $formx->use_field('cover_image')->get_posted();
			    }
			    
			    $entity = new $this->entity();
			    $entity->setName($formx->use_field('name')->get_posted());
			    $entity->setCms_press_category_id($formx->use_field('cms_press_category_id')->get_posted());
			    $entity->setCover_image($cover_image);
			    $entity->setPublicated_at(FormxHelper::convertFulltimeToSqlDate($formx->use_field('publicated_at')->get_posted()));
			    $entity->setOrdering((int) $formx->use_field('ordering')->get_posted());
			    $entity->setActive((int) $formx->use_field('active')->get_posted());
			    
			    $entity->save();

				$return = $this->setNotification('Registro inserido' . $backBtn, 'success', true);
				
			} else {
				$return = $this->setNotification('Erro ao inserir registro' . $backBtn, 'error', true);
			}
		}
		
		return $return . $formx->render();
	}
	
	public function retrieve($hasPermission = false)
	{
		$backBtn = $this->getBackBtn();
		
		if (!$hasPermission) {
			//return $this->setNotification('Voc&ecirc; n&atilde;o tem permiss&atilde;o' . $backBtn, 'error');
		}
		
		$CI =& get_instance();
		$CI->load->library('html_admin/pagination');
		$CI->pagination->set_url($this->getUrl().'/retrieve');
		
		$filters = array();
		$filters['Nome'] = $this->getTable() . '.name';
		
		$CI->pagination->set_filters($filters);
		
		/* Query */
		$criteria = new Criteria();
		
		$filter = $CI->pagination->get_filter();
		if (is_array($filter)) {
		    $criteria->setLike($filter[0], $filter[1]);
		}
		
		$entity = $this->entity;
		$total  = $entity::countAll($criteria);
		/* Query */
		
		$return = $this->setNotification('Nada encontrado' . $backBtn, 'information');
		
		if ($total) {
			
			$CI->pagination->set_total_pages($total);
			
			/* Query */
			$criteria->setLimit($CI->pagination->get_itens_per_page())
			         ->setOffset($CI->pagination->get_limit())
			         ->setHydrate(true);
			
			$entities = $entity::findBy($criteria);
			/* Query */
			
			if ($entities->count()) {
				$CI->pagination->set_current_total($entities->count());
				
				$CI->load->library('html_admin/table');
				$CI->load->library('html_admin/button');
				
				$CI->table->set_update_url($this->getUrl() . '/update');
				$CI->table->set_check_all(false);

				foreach ($entities as $entity) {
				    
					$CI->table->add('id', 	$entity->getId());
					$CI->table->add('name', $entity->getName());
					
					$visualizar = '';
					if ($entity->getAvatar()) {					    
    				    $visualizar = '(<a href="" class="screenshot" rel="' . base_url() . 'uploads/image/' . $entity->getAvatar() . '" onclick="return false;">visualizar</a>)';				    
					}
					$CI->table->add('avatar', 	$visualizar);
				}
				
				$return = $CI->pagination->render() . $CI->table->render();
			}
		}
		
		return $return;
	}
	
	public function update($id, $hasPermission = false)
	{
		$backBtn = $this->getBackBtn();
		
		if (!$hasPermission) {
			//return $this->setNotification('Voc&ecirc; n&atilde;o tem permiss&atilde;o' . $backBtn, 'error');
		}
		
		$id 	= (int) $id;
		$return = $this->setNotification('Nada encontrado [ID = ' . $id . ']' . $backBtn, 'error');
		
		if ($id <= 0) {
			return $return;
		}
		
		$criteria = new Criteria();
		$criteria->setWhere(array('id' => $id));
		$criteria->setHydrate(true);
		
		$entity = $this->entity;
		$entity = $entity::findBy($criteria);
		
		if ($entity->count()) {
		    $entity = $entity->offsetGet(0);
		    		    
			$return = sprintf('<p><b>%s</b>: %s</p>', 'Nome', $entity->getName());
			$return.= sprintf('<p><b>%s</b>: %s</p>', 'E-mail', $entity->getEmail());
			$return.= sprintf('<p><b>%s</b>: %s</p>', 'Newsletter', $entity->getNewsletter() == 1 ? 'Sim' : 'N&atilde;o');
			$return.= sprintf('<p><b>%s</b>: %s</p>', 'Avatar', sprintf('<img src="%s" height="50" />', base_url() . URL_UPLOAD_IMAGE . $entity->getAvatar()));
			$return.= sprintf('<p><b>%s</b>: %s</p>', 'Nascimento', date_create_from_format('Y-m-d', $entity->getBirth_date())->format('d/m/Y'));
			$return.= sprintf('<p><b>%s</b>: %s</p>', 'Sexo', strtoupper($entity->getGender()));
			$return.= sprintf('<p><b>%s</b>: %s</p>', 'Telefone', $entity->getPhone());
			$return.= sprintf('<p><b>%s</b>: %s</p>', 'Endere&ccedil;o', $entity->getAddress());
			$return.= sprintf('<p><b>%s</b>: %s</p>', 'N&ordm;', $entity->getAddress_no());
			$return.= sprintf('<p><b>%s</b>: %s</p>', 'Complemento', $entity->getComplement());
			$return.= sprintf('<p><b>%s</b>: %s</p>', 'Bairro', $entity->getNeighborhood());
			$return.= sprintf('<p><b>%s</b>: %s</p>', 'Cidade', $entity->getCity());
			$return.= sprintf('<p><b>%s</b>: %s</p>', 'CEP', $entity->getZip_code());
			$return.= sprintf('<p><b>%s</b>: %s</p>', 'Estado', strtoupper($entity->getState()));
	
			$return.= $this->getBackBtn();
		}
		
		return $return;
	}
}
