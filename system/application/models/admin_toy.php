<?php

class admin_toy extends Model {
    
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
		
		$formx->add_text('message')
		      ->set_label('Mensagem');

		$formx->add_select('approve')
		      ->set_label('Aprovar?')
		      ->set_value(array('s' => 'Sim', 'n' => 'N&atilde;o'))
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

	public function retrieve($hasPermission = false)
	{
		$backBtn = $this->getBackBtn();
		
		$CI =& get_instance();
		$CI->load->library('html_admin/pagination');
		$CI->pagination->set_url($this->getUrl().'/retrieve');
		
		$entity = $this->entity;

		$return = $this->setNotification('Nada encontrado' . $backBtn, 'information');
		
		$criteria = new Criteria();
		$criteria->setOrder_by('created_at', 'DESC');
		$criteria->setWhere(array('approved' => null));
		$criteria->setHydrate();
		
		$entities = $entity::findBy($criteria);


		$CI->load->library('html_admin/pagination');
		$CI->pagination->set_url($this->getUrl().'/retrieve');
		
		$filters = array();
		$filters['Nome'] = $this->getTable() . '.name';
		$filters['Descri&ccedil;&atilde;o'] = $this->getTable() . '.description';
		
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
				
    			$CI->table->set_check_all(false);
    			$CI->table->set_delete_url($this->getUrl() . '/delete');
				
				foreach ($entities as $entity) {
				    
    				$CI->table->add('id', 		     $entity->getId());
        			$CI->table->add('name', 	     $entity->getName());
    				$CI->table->add('data de cria&ccedil;&atilde;o', date_create_from_format('Y-m-d H:i:s', $entity->getCreated_at())->format('d-m-Y H:i:s'));
    				$CI->table->add('a&ccedil;&otilde;es',
				        button::render(button::ICON_PENCIL, base_url() . 'admin/toy/update/' .  $entity->getId(), 'Visualizar') .
				        button::render(button::ICON_TRASH, base_url() . 'admin/toy/delete/' .  $entity->getId(), 'Excluir', null, "Confirma a exclusão deste registro?")
		          );
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
						
			$return = '';
			
			$return.= sprintf('<p><b>%s</b>: %s</p>', 'Nome', $entity->getName());
			$return.= sprintf('<p><b>%s</b>: %s</p>', 'Descri&ccedil;&atilde;o', $entity->getDescription());
			$return.= sprintf('<p><b>%s</b>: %s</p>', 'Peso', $entity->getWeight());
			$return.= sprintf('<p><b>%s</b>: %s</p>', 'Criado em', $entity->getCreated_at());
			$return.= sprintf('<p><b>%s</b>: %s</p>', 'Categoria', CmsToyCategory::getNameById($entity->getCms_toy_category_id()));
			$return.= sprintf('<p><b>%s</b>: %s</p>', 'Marca', CmsToyBrand::getNameById($entity->getCms_toy_brand_id()));
			$return.= sprintf('<p><b>%s</b>: %s</p>', 'Cidade', CmsToyCity::getNameById($entity->getCms_toy_city_id()));
			$return.= sprintf('<p><b>%s</b>: %s</p>', 'Estado', CmsToyState::getNameByCityId($entity->getCms_toy_city_id()));
			$return.= sprintf('<p><b>%s</b>: %s</p>', 'Cliente', button::render(button::ICON_ZOOM, base_url() . 'admin/client/update/' . $entity->getCms_client_id(), null,  CmsClient::getNameById($entity->getCms_client_id()), null, '_blank'));
			$return.= sprintf('<p><b>%s</b>: %s</p>', 'A&ccedil;&atilde;o', button::render(button::ICON_TRASH, base_url() . 'admin/toy/delete/' .  $entity->getId(), 'Excluir', null, "Confirma a exclusão deste registro?"));
			
			$criteria = new Criteria();
			$criteria->setWhere(array('cms_toy_id' => $entity->getId()));
			$criteria->setHydrate();

			
			foreach (CmsToyImage::findBy($criteria) as $i => $toyImage) {
			    $url = base_url() . URL_UPLOAD_IMAGE . $toyImage->getImage();
			    $return.= sprintf('<p><b>Image#%s</b>: %s</p>', ++$i, sprintf('<a href="%s" target="_blank"><img src="%s" width="150" /></a>', $url, $url));
			}
			
			$success = false;
			if ($formx->is_post()) {
				if (!$formx->has_error()) {
				    
				    $approved = $formx->use_field('approve')->get_posted();
				    $approved = $approved == 's' ? 1 : 0;
				    
				    $entity->setApproved($approved);
				    $entity->setMessage($formx->use_field('message')->get_posted());

				    $entity->update();
				    					
			        $client = CmsClient::getById($entity->getCms_client_id());
			        
				    if ($approved == 1) {
				        _mail($client->email, 'Produto Aprovado', 'produto_aprovado', array(
                            'name' => $client->name,
                            'toy' => $entity->getName()
				        ));
				        
				    } else {
				        _mail($client->email, 'Produto Reprovado', 'produto_reprovado', array(
				            'name' => $client->name,
				            'toy' => $entity->getName(),
				            'message' => $entity->getMessage(),
				        ));
				    }
				    
					$return.= $this->setNotification('Atualizado' . $backBtn, 'success', true);
					
					$success = true;
					
				} else {
					$return.= $this->setNotification('Erro ao atualizar' . $backBtn, 'error', true);
				}
			}
			
			$return.= !$success ? $formx->render() : ''; 
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
		    
		    CmsToy::deleteToy($entity->getId());
		}
		
		if ($totalDel) {
			$return = $this->setNotification('Registro(s) apagado(s) ' . $backBtn, 'success');
			
		} else {
			$return = $this->setNotification('Erro ao apagar registro(s) ' . $backBtn, 'error');
		}
		
		return $return;
	}
}