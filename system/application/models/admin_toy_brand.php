<?php

class admin_toy_brand extends Model {
    
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
	
	/**
	 * @return Formx
	 */
	public function form($label = 'Adicionar')
	{
		$this->load->formx();
		
		$formx = new Formx();
		
		$formx->add_text('name')
		      ->set_label('Nome')
		      ->set_html_class('maxlen-100')
		      ->set_validates(array('required'));
		
		$ordering = CmsToyBrand::countAll();
		$ordering = range(1, $ordering + 1);
		$ordering = array_combine(array_values($ordering), array_values($ordering));
		
		$formx->add_select('ordering')
		->set_label('Ordena&ccedil;&atilde;o')
		->set_value($ordering, count($ordering) - 1)
		->set_validates(array('required'));
		
		$formx->add_submit_button('submit')->set_label($label);
		$formx->add_button('back')->set_label('Voltar')->set_onclick('window.location.href=\'' . base_url() . 'admin/' . $this->getUrl() . '\'');
		
		return $formx;
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
			    $this->load->helper('formx');
			    
			    $entity = new $this->entity();
			    $entity->setName($formx->use_field('name')->get_posted());
			    $entity->setOrdering((int) $formx->use_field('ordering')->get_posted());
			    
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
		
		$formx = $this->form();
		
		$filters = array();
		$filters[$formx->use_field('name')->get_label()] = $this->getTable() . '.' . $formx->use_field('name')->get_name();
		
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
			         ->setOrder_by('ordering')
			         ->setHydrate(true);
			
			$entities = $entity::findBy($criteria);
			/* Query */
			
			if ($entities->count()) {
				$CI->pagination->set_current_total($entities->count());
				
				$CI->load->library('html_admin/table');
				$CI->load->library('html_admin/button');
				
				$CI->table->set_update_url($this->getUrl() . '/update');
				$CI->table->set_delete_url($this->getUrl() . '/delete');
				
				foreach ($entities as $entity) {
				    
					$CI->table->add('id', $entity->getId());
					$CI->table->add('name', $entity->getName());
					$CI->table->add('orderna&ccedil;&atilde;o', $entity->getOrdering());
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
			
		    $formx = $this->form('Atualizar');
		    $formx->set_form_action(base_url() . 'admin/' . $this->getUrl() . '/update/' . $id);
		    	
			$entity = $entity->offsetGet(0);
			
			$formx->use_field('name')->set_value($entity->getName());
			$formx->use_field('ordering')->set_selected($entity->getOrdering());
			
			$return = '';
			
			if ($formx->is_post()) {
				if (!$formx->has_error()) {
				    $this->load->helper('formx');
				    
				    $entity->setName($formx->use_field('name')->get_posted());
				    $entity->setOrdering((int) $formx->use_field('ordering')->get_posted());
				    
				    $entity->update();
				    					
					$return = $this->setNotification('Atualizado' . $backBtn, 'success', true);
					
				} else {
					$return = $this->setNotification('Erro ao atualizar' . $backBtn, 'error', true);
				}
			}
			
			$return.= $formx->render();
		}
		
		return $return;
	}
	
		
	public function delete($id = 0, $hasPermission = false)
	{
		$backBtn = $this->getBackBtn();
		
		if (!$hasPermission) {
			//return $this->setNotification('Voc&ecirc; n&atilde;o tem permiss&atilde;o' . $backBtn, 'error');
		}
		
		$id = !$id ? isset($_REQUEST['item_id']) ? $_REQUEST['item_id'] : '' : $id;
		
		$criteria = new Criteria();
		$criteria->setWhere_in('id', $ids = array_map('intval', is_array($id) ? $id : array($id)));
		$criteria->setHydrate(true);
		
		$entity = $this->entity;
		$entities = $entity::findBy($criteria);
		
		$criteria = new Criteria();
		$criteria->setWhere_in('cms_toy_brand_id', $ids);
		$criteria->setHydrate(true);
		
		$toys = CmsToy::findBy($criteria);
		
		if ($toys->count()) {
		    $return = 'Não foi possível exluir, pois os seguintes produtos estão atrelados:';
		    foreach ($toys as $toy) {
		        $return.= sprintf('<br /><a href="%s" target="_blank">%s</a>', base_url() . 'admin/toy/update/' . $toy->getId(), $toy->getName());
		    }
		
		    return $this->setNotification($return . ' <br />' . $backBtn, 'error');
		}
		
		$totalDel = 0;
		foreach ($entities as $entity) {
		    $totalDel++;
			$entity->delete();
		}
		
		if ($totalDel) {
			$return = $this->setNotification('Registro(s) apagado(s) ' . $backBtn, 'success');
			
		} else {
			$return = $this->setNotification('Erro ao apagar registro(s) ' . $backBtn, 'error');
		}
		
		return $return;
	}
}