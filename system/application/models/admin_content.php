<?php

class admin_content extends Model {
    
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
		
		$formx->add_select('identification')
		      ->set_label('Identificação')
		      ->set_value(CmsContent::getNotUsed()->getArrayCopy())
		      ->set_validates(array('required'));
		
		$formx->add_textarea('content')
			  ->set_label('Conte&uacute;do')
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
            
		    $criteria = new Criteria();
		    $criteria->setWhere(array('identification' => $formx->use_field('identification')->get_posted()));
		    $entityFind = $this->entity;
		    	
		    if ($entityFind::countAll($criteria)) {
		        $formx->use_field('identification')->set_error_message('J&aacute; existe um conte&uacute;do com esta identifica&ccedil;&atilde;o [' . $formx->use_field('identification')->get_posted() . ']!');
		    }
		    
			if (!$formx->has_error()) {
			    $entity = new $this->entity;
			    $entity->setIdentification($formx->use_field('identification')->get_posted());
			    $entity->setContent($formx->use_field('content')->get_posted());
			    
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
			
		$criteria = new Criteria();
		$criteria->setHydrate();
		
		$entity = $this->entity;
		$entities = $entity::findBy($criteria);
			
		$return = $this->setNotification('Nada encontrado' . $backBtn, 'information');
		
		if ($entities->count()) {
			$CI->load->library('html_admin/table');
			$CI->load->library('html_admin/button');
			
			$CI->table->set_update_url($this->getUrl() . '/update');
			$CI->table->set_delete_url($this->getUrl() . '/delete');
			
			foreach ($entities as $entity) {				    
				$CI->table->add('id', $entity->getId());
				$CI->table->add('Identifica&ccedil;&atilde;o', CmsContent::getIdentificationAsString($entity->getIdentification()));
			}
			
			$return = $CI->table->render();
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
			
			$formx->use_field('identification')->set_value(CmsContent::getNotUsed()->getArrayCopy() + array($entity->getIdentification() => CmsContent::getIdentificationAsString($entity->getIdentification())));
			$formx->use_field('identification')->set_selected($entity->getIdentification());
			$formx->use_field('content')->set_value($entity->getContent());
			
			$return = '';
			
			if ($formx->is_post()) {
				
			    if (!$formx->has_error()) {
					
					$criteria = new Criteria();
					$criteria->setWhere(array('id !=' => $id, 'identification' => $formx->use_field('identification')->get_posted()));
					$entityFind = $this->entity;
					
					if ($entityFind::countAll($criteria)) {
	    		        $formx->use_field('identification')->set_error_message('J&aacute; existe um conte&uacute;do com esta identifica&ccedil;&atilde;o [' . $formx->use_field('identification')->get_posted() . ']!');
					}
			    }
			    
				if (!$formx->has_error()) {
				    $entity->setIdentification($formx->use_field('identification')->get_posted());
				    $entity->setContent($formx->use_field('content')->get_posted());
				    
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
		$criteria->setWhere_in('id', array_map('intval', is_array($id) ? $id : array($id)));
		$criteria->setHydrate(true);
		
		$entity = $this->entity;
		$entities = $entity::findBy($criteria);
		
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