<?php

class admin_user extends Model
{
	public function __construct()
	{
		parent::Model();	
	}
	
	public function login()
	{
		$pass 	= $this->input->post('pass');
		$login 	= $this->input->post('login');
		
		if ($pass && $login) {
			
			$query = $this->db->get_where('users', array('pass' => $pass, 'login' => $login));
			
			if ($query->num_rows() > 0) {
				$this->sessionStart($query->row());
				$this->setPermissions($query->row());
				return true;
			}
		}
		return false;
	}
	
	public function setPermissions($user)
	{
		if ($user->is_admin == 1) {
			return;
		}
		
		$this->db->select('
			cms_pages.file as page_file,
			cms_page_actions.action as page_action,
		');
		
		$this->db->from('cms_users');
		$this->db->where(array('cms_users.id' => $user->id));
		
		$this->db->join('cms_user_profiles', 	'cms_user_profiles.cms_users_id = cms_users.id');
		$this->db->join('cms_profiles', 		'cms_profiles.id = cms_user_profiles.cms_profiles_id');
		$this->db->join('cms_profile_pages', 	'cms_profile_pages.cms_profiles_id = cms_profiles.id');
		$this->db->join('cms_page_actions', 	'cms_page_actions.id = cms_profile_pages.cms_page_actions_id');
		$this->db->join('cms_pages', 			'cms_pages.id = cms_page_actions.cms_pages_id');
		
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			$permissions = array();
			foreach($query->result() as $row) {
				$permissions[$row->page_file] = isset($permissions[$row->page_file]) ? $permissions[$row->page_file] : array();
				$permissions[$row->page_file][$row->page_action] = true;
			}
			$_SESSION[PROJECT]['backend']['permissions'] = $permissions;
		}
	}
	
	public function isLogged($redirect = true)
	{
		if (!$this->hasStarted()) {
			if ($redirect) {
				redirect('admin/log');
				exit();
			}
			return false;
		}
		return true;
	}

	public function isInstalled($redirect = false)
	{
		$query = $this->db->get_where('users', array('is_admin' => 1));
		
		if ($query->num_rows() == 1) {
			return true;
		}
		
		if ($redirect !== false) {
			if ($redirect === true) {
				$redirect = 'admin/users/create';
			}
			redirect('admin/' . $redirect);
			exit;
		} 
		
		return false;
	}
	
	public function hasPermission($file = '', $action = '')
	{
		if ($this->isLogged(false)) {
			if ($_SESSION[PROJECT]['user']->is_admin == 1) {
				return true;
			}
			
			if (isset($_SESSION[PROJECT]['backend']['permissions'][$file])) {
				// class::method
				$action = explode('::', $action);
				$action = count($action) == 2 ? $action[1] : $action[0];
				return @isset($_SESSION[PROJECT]['backend']['permissions'][$file][$action]);
			}
		}
		
		return false;
	}
		
	public function logout()
	{
    	$this->sessionEnd();
    	redirect('admin/log');
    	exit;
	}

	public function sessionStart($user)
	{
		$_SESSION[PROJECT]['user'] = $user;
		return true;
	}
	
	public function sessionEnd()
	{
		$_SESSION[PROJECT]['user'] = null;
		unset($_SESSION[PROJECT]);
	}
		
	public function hasStarted()
	{
		return @isset($_SESSION[PROJECT]['user']);
	}
	
	public function getUser()
	{
		return @$_SESSION[PROJECT]['user'];
	}
}
