<?php

class admin_pages extends Model {
   
	public $table = '';
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
		
		return $formx;
	}
	
	function getBackBtn($txt = '', $url = '')
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
		return $this->setNotification('Voc&ecirc; n&atilde;o pode criar p&aacute;ginas manualmente!' .
											$this->getBackBtn('Atualizar P&aacute;ginas',
											base_url() . 'admin/pages/refresh_pages'
										),
										'information'
									);
	}
	
	public function createPageMethods($pageId, $hasPermission = false)
	{
		$backBtn = $this->getBackBtn('', base_url() . 'admin/pages/retrieve_methods/' . $pageId);
		if (!$hasPermission) {
			//return $this->setNotification('Voc&ecirc; n&atilde;o tem permiss&atilde;o' . $backBtn, 'error');
		}
		
		$pageId = (int) $pageId;
		if ($pageId <=0) {
			return $this->setNotification('P&aacute;gina [' . $pageId . '] n&atilde;o encontrada' . $backBtn, 'error');
		}
		
		$this->refreshPageMethods($pageId);
		
		$this->form();
					
		$formx = new Formx();
		
		$formx->add_text('action')
			  ->set_label('M&eacute;todo')
			  ->set_html_class('maxlen-100')
			  ->set_validates('required');
		
		$formx->add_text('name')
		      ->set_label('Descri&ccedil;&atilde;o')
		      ->set_html_class('maxlen-100')
		      ->set_validates('required');
			
		$formx->set_form_action(base_url() . 'admin/' . $this->getUrl() . '/create_method/' . $pageId);
		$formx->add_submit_button('submit')->set_label('Criar');
		
		$return = '';
		
		if ($formx->is_post())
		{
			if (!$formx->has_error())
			{
				if ($this->db->get_where('cms_page_actions', array('cms_pages_id' => $pageId, 'action' => $formx->use_field('action')->get_posted()))->num_rows() >= 1) {
					$return = $this->setNotification('J&aacute; existe um m&eacute;todo com este no nome (<b>' . $formx->use_field('action')->get_posted() . '</b>) no banco', 'error');

				} else {
				    $cmsPageActions = new CmsPageActions();
				    $cmsPageActions->setCms_pages_id($pageId);
				    $cmsPageActions->setAction($formx->use_field('action')->get_posted());
				    $cmsPageActions->setName($formx->use_field('name')->get_posted());
				    $cmsPageActions->setIs_custom(1);
					$cmsPageActions->save();
										
					redirect(base_url() . 'admin/' . $this->getUrl() . '/retrieve_methods/' . $pageId);
				}
			}
		}
		
		return $return . $formx->render();
	}
	
	public function updatePageMethods($pageId, $actionId, $hasPermission = false)
	{
		$backBtn = $this->getBackBtn();
		if (!$hasPermission) {
			//return $this->setNotification('Voc&ecirc; n&atilde;o tem permiss&atilde;o' . $backBtn, 'error');
		}
		
		$pageId = (int) $pageId;
		if ($pageId <=0) {
			return $this->setNotification('P&aacute;gina [' . $pageId . '] n&atilde;o encontrada' . $backBtn, 'error');
		}
		
		$pageId 	= (int) $pageId;
		$actionId 	= (int) $actionId;
		if ($pageId <= 0 | $actionId <= 0) {
			return $this->setNotification('P&aacute;gina [' . $pageId . '] n&atilde;o encontrada' . $backBtn, 'error');
		}
		
		$this->refreshPageMethods($pageId);
		
		$this->db->select('cms_page_actions.*');
		$this->db->join('cms_pages', 'cms_page_actions.cms_pages_id = cms_pages.id');
		$query = $this->db->get_where('cms_page_actions', array('cms_page_actions.id' => $actionId, 'cms_page_actions.cms_pages_id' => $pageId));
		
		if ($query->num_rows() != 1) {
			return $this->setNotification('P&aacute;gina [' . $pageId . '] n&atilde;o encontrada' . $backBtn, 'error');
		}
		
		$this->form();

		$formx = new Formx();
		
		if ($query->row()->is_custom == 1) {
			$formx->add_text('action')
				  ->set_label('M&eacute;todo')
				  ->set_value($query->row()->action)
				  ->set_html_class('maxlen-100')
				  ->set_validates('required');
		}
		
		$formx->add_text('name')
			  ->set_label('Descri&ccedil;&atilde;o')
			  ->set_value($query->row()->name)
			  ->set_html_class('maxlen-100')
			  ->set_validates('required');
			
		
		$formx->set_form_action(base_url() . 'admin/' . $this->getUrl() . '/update_method/' . $pageId . '/' . $actionId);
		$formx->add_submit_button('submit')->set_label('Atualizar');
				
		$return = '';
		
		if ($formx->is_post()) {
			if (!$formx->has_error()) {
			    
				$action = array();
				if ($query->row()->is_custom == 1) {
					$action = array('action' => $formx->use_field('action')->get_posted());
					
					$q = $this->db->get_where('cms_page_actions', $action + array('id !=' => $actionId));
					
					if ($q->num_rows() > 0) {
						$return = $this->setNotification('J&aacute; existe um m&eacute;todo com este no nome (<b>' . $formx->use_field('action')->get_posted() . '</b>) no banco', 'error');
					}
				}
				
				if ($return == '') {
				    
					$this->db->update('cms_page_actions', array(
							'name' 			=> $formx->use_field('name')->get_posted(),
						) + $action,
						array(
							'id' 			=> $actionId,
							'cms_pages_id' 	=> $pageId,
						)
					);
					redirect(base_url() . 'admin/' . $this->getUrl() . '/retrieve_methods/' . $pageId);
				}
			}
		}
		
		return $return . $formx->render();
	}
	
	public function refreshPages($hasPermission = false)
	{
		if (!$hasPermission) {
			return $this->setNotification('Voc&ecirc; n&atilde;o tem permiss&atilde;o' . $this->getBackBtn(), 'error');
		}
		
		$path = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'controllers';
		$path.= DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR;
		
		$pages = glob($path . '*.php');
		
		foreach ($pages as $page) {
			$page = explode(DIRECTORY_SEPARATOR, $page);
			$page = end($page);
			$page = str_replace('.php', '', $page);
			
			if (!in_array($page, array('home', 'log'))) {
				$queryPage = $this->db->get_where($this->getTable(), array('file' => $page));
				if ($queryPage->num_rows() == 0) {
					$pageId = $this->insertNewPage($page);
				} else {
					$pageId = $queryPage->row()->id;
				}
				
				$this->refreshPageMethods($pageId);
			}
		}
		
		redirect('admin/' . $this->getUrl());
	}
	
	public function deleteMethod($pageId, $actionId, $hasPermission = false)
	{
		$backBtn = $this->getBackBtn();
		
		if (!$hasPermission) {
			//return $this->setNotification('Voc&ecirc; n&atilde;o tem permiss&atilde;o' . $backBtn, 'error');
		}
		
		$pageId 	= (int) $pageId;
		$actionId 	= (int) $actionId;
		if ($pageId <= 0 | $actionId <= 0) {
			return $this->setNotification('P&aacute;gina [' . $pageId . '] n&atilde;o encontrada' . $backBtn, 'error');
		}
		
		$this->refreshPageMethods($pageId);
		
		$this->db->select('cms_page_actions.*');
		$this->db->join('cms_pages', 'cms_page_actions.cms_pages_id = cms_pages.id');
		$query = $this->db->get_where('cms_page_actions', array('cms_page_actions.id' => $actionId, 'cms_page_actions.cms_pages_id' => $pageId));
		
		if ($query->num_rows() != 1) {
			return $this->setNotification('P&aacute;gina [' . $pageId . '] n&atilde;o encontrada' . $backBtn, 'error');
		}
		
		if ($query->row()->is_custom == 0) {
			return $this->setNotification('Voc&ecirc; n&atilde;o pode apagar um m&eacute;todo padr&atilde;o' . $backBtn, 'error');
		}

		$this->db->delete('cms_page_actions', array('cms_page_actions.id' => $actionId));
		redirect(base_url() . 'admin/pages/retrieve_methods/' . $pageId);
	} 
 	
	public function refreshPageMethods($pageId, $redirect = false)
	{
		$pageId = (int) $pageId;
		if ($pageId <= 0){
			($redirect ? redirect('admin/pages') : ''); 
			return false;
		}
		
		$queryPage = $this->db->get_where($this->getTable(), array('id' => $pageId));
		if ($queryPage->num_rows() == 0) {
			return false;
		}
		
		$file 	  = $queryPage->row()->file;
		$filePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'controllers';
		$filePath.= DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR;
		$filePath.= $file . '.php';
		
		if (!is_file($filePath)) {
			($redirect ? redirect('admin/pages') : '');
			return false;
		}
		
		if (!class_exists($file)) {
			require_once $filePath;
		}
		
		if (!class_exists($file)) {
			($redirect ? redirect('admin/pages') : '');
			return false;
		}
		
		$methods 		= get_class_methods($file);
		$methods 		= array_combine(array_values($methods), array_values($methods));
		# cleanup methods
		$removeMethods 	= array('index', '__construct', 'Controller', '_ci_initialize', '_ci_scaffolding', 'CI_Base', 'get_instance');
		$removeMethods 	= array_combine(array_values($removeMethods), array_values($removeMethods));
		$methods 		= array_diff_key($methods, $removeMethods);
		
		# new page
		$dbMethodsQuery = $this->db->get_where('cms_page_actions', array('cms_pages_id' => $pageId));
		if ($dbMethodsQuery->num_rows() == 0) {
			foreach ($methods as $method) {
				$this->db->insert('cms_page_actions', array('cms_pages_id' => $pageId, 'action' => $method, 'name' => $method, 'is_custom' => 0));
			}
			($redirect ? redirect('admin/pages/retrieve_methods/' . $pageId) : '');
			return true;
		}
		
		# page exists on db
		$dbMethods = array();
		foreach ($dbMethodsQuery->result() as $method) {
			# the method on this file was removed, so update db
			if (!isset($methods[$method->action]) && $method->is_custom == 0) {
				$this->db->delete('cms_page_actions', array('id' => $method->id));
			} else {
				unset($methods[$method->action]);
			}
		}

		# insert new methods
		foreach ($methods as $method) {
			$this->db->insert('cms_page_actions', array('cms_pages_id' => $pageId, 'action' => $method, 'name' => $method));
		}
		
		($redirect ? redirect('admin/pages/retrieve_methods/' . $pageId) : '');
		return true;
	}
	
	private function insertNewPage($page)
	{
		$this->db->insert($this->getTable(), array('name' => $page, 'file' => $page));
		return $this->db->insert_id();
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
		
		$CI->pagination->set_filters(
			array(	
				'P&aacute;gina' => $this->getTable().'.name',
				'Arquivo' 		=> $this->getTable().'.file',
			)
		);
		
		/* Query */
		$filter = $CI->pagination->get_filter();
		
		if (is_array($filter)) {
			$this->db->like($filter[0], $filter[1]);
		}	
		$this->db->from($this->getTable());
		$query = $this->db->get();
		/* Query */
		
		$return = $this->setNotification('Nada encontrado' . $backBtn, 'information');
		
		if ($query->num_rows()) {
			
			$CI->pagination->set_total_pages($query->num_rows());
			
			if (is_array($filter)) {
				$this->db->like($filter[0], $filter[1]);
			}
			
			/* Query */
			$this->db->from($this->getTable());
			$this->db->limit($CI->pagination->get_itens_per_page(), $CI->pagination->get_limit());
			$query = $this->db->get();
			/* Query */
			
			$CI->pagination->set_current_total($query->num_rows());
			
			if ($query->num_rows() > 0) {
				$CI->load->library('html_admin/table');
				
				#$CI->table->set_update_url($this->getUrl() . '/update');
				#$CI->table->set_delete_url($this->getUrl() . '/delete');
				$CI->table->set_check_all(false);
				
				$baseUrl = base_url();
				
				foreach ($query->result() as $result) {
					$CI->table->add('id', 					$result->id);
					$CI->table->add('P&aacute;gina', 		$result->name);
					#$CI->table->add('Arquivo',				$result->file);
					$CI->table->add('a&ccedil;&otilde;es', 	'<a title="Alterar" class="button tooltip" href="'. $baseUrl . 'admin/pages/update/'. $result->id . '" style="padding-right:0">
																<span class="ui-icon ui-icon-pencil"></span>
															</a>
															<!--<a title="Procurar M&eacute;todos" class="button tooltip" href="'. $baseUrl . 'admin/pages/refresh_methods/'. $result->id . '" style="padding-right:0">
																<span class="ui-icon ui-icon-arrowrefresh-1-e"></span>
															</a>-->
															<a title="Listar M&eacute;todos" class="button tooltip" href="'. $baseUrl . 'admin/pages/retrieve_methods/'. $result->id . '" style="padding-right:0">
																<span class="ui-icon ui-icon-wrench"></span>
															</a>
															<a title="Criar M&eacute;todo Customizado" class="button tooltip" href="'. $baseUrl . 'admin/pages/create_method/'. $result->id . '" style="padding-right:0">
																<span class="ui-icon ui-icon-document"></span>
															</a>
															
															'
									);
				}
				
				$return = $CI->pagination->render() . $CI->table->render();
			}
		}
		
		return $return;
	}

	public function retrieveMethods($pageId = 0, $hasPermission = false)
	{
		$backBtn = $this->getBackBtn();
		
		if (!$hasPermission) {
			//return $this->setNotification('Voc&ecirc; n&atilde;o tem permiss&atilde;o' . $backBtn, 'error');
		}
		
		$pageId = (int) $pageId;
		if ($pageId <=0) {
			return $this->setNotification('P&aacute;gina [' . $pageId . '] n&atilde;o encontrada' . $backBtn, 'error');
		}
		
		$this->refreshPageMethods($pageId);
		
		$this->db->select('cms_pages.name as page_name, cms_page_actions.*');
		$this->db->join('cms_pages', 'cms_page_actions.cms_pages_id = cms_pages.id');
		$this->db->order_by('cms_page_actions.name');
		$query = $this->db->get_where('cms_page_actions', array('cms_pages_id' => $pageId));
		
		if ($query->num_rows() == 0) {
			return $this->setNotification('Nenhuma m&eacute;todo encontrado para esta p&aacute;gina' . $backBtn, 'information');
		}
		
		$CI =& get_instance();
		$CI->load->library('html_admin/table');
		$CI->table->set_check_all(false);
				
		$baseUrl = base_url();
				
		foreach ($query->result() as $result) {
			
			$delete = $result->is_custom == 1 ?  '<a title="Apagar" class="button tooltip" href="'. $baseUrl . 'admin/pages/delete_method/'. $pageId . '/' . $result->id . '" style="padding-right:0" onclick="return confirm(\'Confirma?\');">
														<span class="ui-icon ui-icon-trash"></span>
													</a>' : '';
			
			$CI->table->add('id', 						$result->id);
			$CI->table->add('P&aacute;gina', 			$result->page_name);
			$CI->table->add('Descri&ccedil;&atilde;o',  $result->name);
			$CI->table->add('M&eacute;todo', 			'<b>' . $result->action . '</b>');
			
			$CI->table->add('a&ccedil;&otilde;es', 		'<a title="Alterar" class="button tooltip" href="'. $baseUrl . 'admin/pages/update_method/'. $pageId . '/' . $result->id . '" style="padding-right:0">
															<span class="ui-icon ui-icon-pencil"></span>
														</a>
														' . $delete);
		}

		$toolBar = $this->getBackBtn('Adicionar M&eacute;todo Customizado', base_url() . 'admin/pages/create_method/' . $pageId) . $this->getBackBtn();
		$toolBar = str_replace('<br />', '', $toolBar);
		
		return $toolBar . '<br /><br />' . $CI->table->render() . $toolBar;
	}
	
	public function update($id, $hasPermission = false)
	{
		$backBtn 	= $this->getBackBtn();
		
		if (!$hasPermission) {
			//return $this->setNotification('Voc&ecirc; n&atilde;o tem permiss&atilde;o' . $backBtn, 'error');
		}
		
		$id		= (int) $id;
		$return = $this->setNotification('Nada encontrado [ID = ' . $id . ']' . $backBtn, 'error');
		
		if ($id <= 0) {
			return $return;
		}
		
		$query = $this->db->get_where($this->getTable(), array('id' => $id));
		
		if ($query->num_rows() > 0) {
			
		    $formx = $this->form();
		    $formx->set_form_action(base_url() . 'admin/' . $this->getUrl() . '/update/' . $id);
		    $formx->add_submit_button('submit')->set_label('Atualizar');
		    
			$formx->use_field('name')->set_value($query->row()->name);
			
			$return = '';
			
			if ($formx->is_post()) {
				if (!$formx->has_error()) {
					
					$this->db->update(
						$this->getTable(),
						array(
							'name' => $formx->use_field('name')->get_posted(),
						),
						array(
							'id' => $id
						)
					);
					
					$return = $this->setNotification('Atualizado' . $backBtn, 'success', true);
					
				} else {
					$return = $this->setNotification('Erro ao atualizar' . $backBtn, 'error', true);
				}
			}
			
			$return.= $formx->render();
		}
		
		return $return;
	}
		
	public function delete($id, $hasPermission = false)
	{
		$backBtn = $this->getBackBtn();
		
		if (!$hasPermission) {
			//return $this->setNotification('Voc&ecirc; n&atilde;o tem permiss&atilde;o' . $backBtn, 'error');
		}
		
		$id = !$id ? isset($_REQUEST['item_id']) ? $_REQUEST['item_id'] : '' : $id;
		
		$idArray = is_array($id) ? $id : array($id);
		
		$total_del = 0;
		
		foreach ($idArray as $k => $id) {
			$id = (int) $id;
			if ($id > 0) {
				$query = $this->db->get_where($this->getTable(), array('id' => $id));
				if ($query->num_rows() > 0) {
					
					$file 	  = $query->row()->file;
					$filePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'controllers';
					$filePath.= DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR;
					
					if (file_exists($filePath . $file . '.php')) {
						return $this->setNotification('N&atilde;o &eacute; poss&iacute;vel apagar um p&aacute;gina existente' . $this->getBackBtn(), 'error');
					}
					
					$total_del++;
					$this->db->delete($this->getTable(), array('id' => $id));
				}
			}
		}
		
		if ($total_del > 0) {
			$return = $this->setNotification('Registro(s) apagado(s) ' . $backBtn, 'success');
		} else {
			$return = $this->setNotification('Erro ao apagar registro(s) ' . $backBtn, 'error');
		}
		
		return $return;
	}
}