<?php

class admin_profiles extends Model {
    
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
		
		$this->db->select('cms_pages.name as page_name, cms_page_actions.*');
		$this->db->join('cms_pages', 'cms_pages.id = cms_page_actions.cms_pages_id');
		$q = $this->db->get('cms_page_actions');

		$checkbox = array();
		foreach($q->result() as $r) {
			$checkbox[$r->id . '.' . $r->cms_pages_id] = $r->page_name . ' - (' .  $r->name . ' <b>' . $r->action . '</b>)'; 
		}
		
		$formx->add_checkbox('cms_page_actions')
			  ->set_label('M&eacute;todos')
			  ->set_value($checkbox)
			  ->set_validates(array('required'));
		
		$formx->add_submit_button('submit')->set_label($label);
		
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
		$backBtn = $this->getBackBtn();
		
		if (!$hasPermission) {
			//return $this->setNotification('Voc&ecirc; n&atilde;o tem permiss&atilde;o' . $backBtn, 'error');
		}
		
		$formx = $this->form();
		$formx->set_form_action(base_url() . 'admin/' . $this->getUrl() . '/create');
		
		$return = '';
		
		if ($formx->is_post()) {
			if (!$formx->has_error()) {

				$this->db->insert($this->getTable(), 
					array(
						'name' => $formx->use_field('name')->get_posted()
					)
				);

				$this->insertProfilePages($this->db->insert_id(), $formx->use_field('cms_page_actions')->get_posted());
				
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
		
		$CI->pagination->set_filters(
			array(	
				'Nome' 	=> $this->getTable().'.name',
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
				
				$CI->table->set_update_url($this->getUrl() . '/update');
				$CI->table->set_delete_url($this->getUrl() . '/delete');
				
				foreach ($query->result() as $result) {
					$CI->table->add('id', 		$result->id);
					$CI->table->add('nome', 	$result->name);
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
		
		$query = $this->db->get_where($this->getTable(), array('id' => $id));
		
		if ($query->num_rows() > 0) {
			
		    $formx = $this->form('Atualizar');
		    $formx->set_form_action(base_url() . 'admin/' . $this->getUrl() . '/update/' . $id);
		    	
			$result = $query->row();
			
			$cmsPageActions = array();
			$q = $this->db->get_where('cms_profile_pages', array('cms_profiles_id' => $result->id));
			if ($q->num_rows() > 0){
				foreach($q->result() as $r) {
					$cmsPageActions[] = $r->cms_page_actions_id . '.' .$r->cms_pages_id; 
				}
			}
			
			$formx->use_field('name')->set_value($result->name);
			$formx->use_field('cms_page_actions')->set_selected($cmsPageActions);
			
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
					
					$this->insertProfilePages($id, $formx->use_field('cms_page_actions')->get_posted());
					
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
		
		$idArray = is_array($id) ? $id : array($id);
		
		$total_del = 0;
		
		foreach ($idArray as $k => $id) {
			$id = (int) $id;
			if ($id > 0) {
				$query = $this->db->get_where($this->getTable(), array('id' => $id));
				if ($query->num_rows() > 0) {
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
	
	public function insertProfilePages($cmsProfileId, $cmsPageActions)
	{
		$this->db->delete('cms_profile_pages', array('cms_profiles_id' => $cmsProfileId));
		foreach ($cmsPageActions as $cmsPageAction) {
			$hash = explode('.', $cmsPageAction);
			$this->db->insert('cms_profile_pages',
					array(
							'cms_profiles_id' 		=> $cmsProfileId,
							'cms_pages_id' 			=> $hash[1],
							'cms_page_actions_id'	=> $hash[0],
					)
			);
		}
	}
}