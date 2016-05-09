<?php

class admin_users extends Model {
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
	public function form($label = 'Adicionar', $isInstalled = false)
	{
		$this->load->formx();
		
		$formx = new Formx();
		
		$formx->add_text('name')
			  ->set_label('Nome')
			  ->set_html_class('maxlen-120')
			  ->set_validates(array('required'));
			
		$formx->add_text('login')
			  ->set_label('Login')
			  ->set_html_class('maxlen-120')
			  ->set_validates(array('required'));
			
		$formx->add_text('pass')
			  ->set_label('Senha')
			  ->set_html_class('maxlen-120')
			  ->set_validates(array('required'));
		
		$formx->add_text('email')
			  ->set_label('E-mail')
			  ->set_html_class('maxlen-120')
			  ->set_validates(array('email', 'required'));

		if ($isInstalled) {
			$query = $this->db->get('cms_profiles');
			$profiles = array();
			if ($query->num_rows()) {
				foreach($query->result() as $result) {
					$profiles[$result->id] = $result->name;
				}
			}
	
			$formx->add_multiple('profiles')
				  ->set_label('Profiles')
				  ->set_value($profiles)
				  ->set_validates(array('required'));
		}
		
		$formx->add_textarea('desc')
			  ->set_label('Descri&ccedil;&atilde;o');
				
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

	public function create($hasPermission = false, $isInstalled = false)
	{
		$backBtn = $this->getBackBtn();
		
		if (!$hasPermission) {
			//return $this->setNotification('Voc&ecirc; n&atilde;o tem permiss&atilde;o' . $backBtn, 'error');
		}

		$formx = $this->form('Adicionar', $isInstalled);
		$formx->set_form_action(base_url() . 'admin/' . $this->getUrl() . '/create');
		$formx->add_submit_button('submit')->set_label('Adicionar');
		
		$return = '';
		
		if ($formx->is_post()) {
			
		    if (!$formx->has_error()) {
    			if ($this->db->get_where($this->getTable(), array('email' => $formx->use_field('email')->get_posted()))->num_rows() > 0) {
    			    $formx->use_field('email')->set_error_message('Este e-mail j&aacute; existe!');
    			    $formx->set_form_errors();
    			} 
    			
    			if ($this->db->get_where($this->getTable(), array('login' => $formx->use_field('login')->get_posted()))->num_rows() > 0) {
    				$formx->use_field('login')->set_error_message('Este login j&aacute; existe!');
    				$formx->set_form_errors();
    			}
		    }
			
			if (!$formx->has_error()) {
				// force installation
				$isAdmin = !$isInstalled ? 1 : 0;
				
				$this->db->insert($this->getTable(), 
					array(
						'name' 			=> $formx->use_field('name')->get_posted(),
						'email' 		=> $formx->use_field('email')->get_posted(),
						'desc' 			=> $formx->use_field('desc')->get_posted(),
						'login' 		=> $formx->use_field('login')->get_posted(),
						'pass' 			=> $formx->use_field('pass')->get_posted(),
						'is_admin' 		=> $isAdmin,
						'created_at' 	=> date('Y-m-d H:i:s')
					)
				);
				
				if ($isAdmin == 0) {
					$this->setPermissions($formx->use_field('profiles')->get_posted(), $this->db->insert_id());
				}
				
				# it wasn't installed and this new user is an admin
				if (!$isInstalled && $isAdmin) {
					redirect('admin/log');
					exit;
				}
				
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
		$CI->load->model('admin_user');
		
		$CI->pagination->set_filters(
			array(	
				'Nome' 	=> $this->getTable().'.name',
				'Login' => $this->getTable().'.login',
			)
		);
		
		/* Query */
		$filter = $CI->pagination->get_filter();
		
		if (is_array($filter)) {
			$this->db->like($filter[0], $filter[1]);
		}	
		$this->db->from($this->getTable());
		if ($CI->admin_user->getUser()->is_admin == 0) {
			$this->db->where('is_admin != 1');	
		}
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
			if ($CI->admin_user->getUser()->is_admin == 0) {
				$this->db->where('is_admin != 1');
			}
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
					$CI->table->add('login',	$result->login);
				}
				
				$return = $CI->pagination->render() . $CI->table->render();
			}
		}
		
		return $return;
	}

	public function update($id, $hasPermission = false, $isInstalled = false)
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
			
			$CI =& get_instance();
			$CI->load->model('admin_user');
			if ($query->row()->is_admin == 1 && $CI->admin_user->getUser()->is_admin == 0)
			{
				//return $this->setNotification('Voc&ecirc; n&atilde;o tem permiss&atilde;o' . $backBtn, 'error');
			}
			
			$formx = $this->form('Atualizar');
			$formx->set_form_action(base_url() . 'admin/' . $this->getUrl() . '/update/' . $id);
			$formx->add_submit_button('submit')->set_label('Atualizar');
			
			$result = $query->row();
			
			$formx->use_field('name')->set_value($result->name);
			$formx->use_field('email')->set_value($result->email);
			$formx->use_field('desc')->set_value($result->desc);
			$formx->use_field('login')->set_value($result->login);
			$formx->use_field('pass')->set_value($result->pass);
			$isAdmin = $result->is_admin;
			
			if ($isAdmin == 0) {
				$cmsProfiles = array();
				$q = $this->db->get_where('cms_user_profiles', array('cms_users_id' => $result->id));
				if ($q->num_rows() > 0) {
					foreach ($q->result() as $r) {
						$cmsProfiles[] = $r->cms_profiles_id;	
					}
				}
				$formx->use_field('profiles')->set_selected($cmsProfiles);
			}
				
			$return = '';
			
			if ($formx->is_post()) {
			    if (!$formx->has_error()) {
    				if ($this->db->get_where($this->getTable(), array('id !=' => $id, 'email' => $formx->use_field('email')->get_posted()))->num_rows() > 0) {
    					$formx->use_field('email')->set_error_message('Este e-mail j&aacute; existe!');
    					$formx->set_form_errors();
    				}
    					
    				if ($this->db->get_where($this->getTable(), array('id !=' => $id, 'login' => $formx->use_field('login')->get_posted()))->num_rows() > 0) {
    					$formx->use_field('login')->set_error_message('Este login j&aacute; existe!');
    					$formx->set_form_errors();
    				}
			    }
				
				if (!$formx->has_error()) {
					$this->db->update(
						$this->getTable(),
						array(
							'name' 			=> $formx->use_field('name')->get_posted(),
						    'email' 		=> $formx->use_field('email')->get_posted(),
						    'desc' 			=> $formx->use_field('desc')->get_posted(),
						    'login' 		=> $formx->use_field('login')->get_posted(),
						    'pass' 			=> $formx->use_field('pass')->get_posted(),
							'updated_at' 	=> date('Y-m-d H:i:s')
						),
						array(
							'id' => $id
						)
					);
					
					if ($isAdmin == 0) {
						$this->setPermissions($formx->use_field('profiles')->get_posted(), $id);
					}
					
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
			return $this->setNotification('Voc&ecirc; n&atilde;o tem permiss&atilde;o' . $backBtn, 'error');
		}
		
		$id = !$id ? isset($_REQUEST['item_id']) ? $_REQUEST['item_id'] : '' : $id;
		
		$idArray = is_array($id) ? $id : array($id);
		
		$total_del = 0;
		
		foreach ($idArray as $k => $id) {
			$id = (int) $id;
			if ($id > 0) {
				$query = $this->db->get_where($this->getTable(), array('id' => $id));
				if ($query->num_rows() > 0) {
					$query = $query->row();
					if ($query->is_admin == 1 && $this->countAdmin() == 1) {
						return $this->setNotification('Voc&ecirc; n&atilde;o pode excluir um super user' . $this->getBackBtn(), 'error');
						break;
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
	
	private function isAdmin($userId)
	{
		return $this->db->get_where($this->getTable(), array('is_admin' => 1, 'id' => $userId))->num_rows() == 1;
	}
	
	private function countAdmin()
	{
		return $this->db->get_where($this->getTable(), array('is_admin' => 1))->num_rows(); 
	}
	
	private function setPermissions($profiles, $userId)
	{
		$this->db->delete('cms_user_profiles', array('cms_users_id' => $userId));

		foreach ($profiles as $k => $profileId) {
			$this->db->insert('cms_user_profiles', array('cms_users_id' => $userId, 'cms_profiles_id' => $profileId));
		}
	}
}