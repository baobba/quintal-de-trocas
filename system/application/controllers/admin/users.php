<?php

class users extends Controller
{
	protected $data = array(
		'model'		=> '',
		'subtitle' 	=> '',
		'content'	=> '',
		'subtitle'	=> 'Usu&aacute;rios',
		'load_js'	=> array(),
		'load_css'	=> array(),
	);
			
	public function __construct()
	{
		parent::Controller();
		
		$this->load->model('admin_' . __CLASS__, 'admin_model');
		$this->load->entity('CmsUsers');
		$this->load->model('admin_user', 'user');
		
		if ($this->user->isLogged(false) == false && $this->user->isInstalled()) {
			$this->user->isLogged();
		}
		
		$this->admin_model->url = strtolower(__CLASS__);
		
		$this->load->library('html_admin/tabs', 'tabs');
	}

	public function index()
	{
		$this->retrieve();
	}

	public function create()
	{	
		$hasPermission = true;
			
		$isInstalled = $this->user->isInstalled();
		if ($isInstalled) {
			$hasPermission = $this->user->hasPermission(__CLASS__, __METHOD__);
		}
		 
		$this->tabs->add_tab(
			'Adicionar',
			$this->admin_model->create($hasPermission, $isInstalled),
			true
		);
		
		$this->data['content'] = $this->tabs->render();
		
		$this->load->view('admin/base', $this->data);
	}
	
	public function retrieve()
	{
		$isInstalled = $this->user->isInstalled();
		if (!$isInstalled) {
			redirect(base_url() . 'admin/users/create');
		}
		
		$hasPermission = $this->user->hasPermission(__CLASS__, __METHOD__);
		
		$this->tabs->add_tab(
			'Listar',
			$this->admin_model->retrieve($hasPermission),
			true
		);
		
		$this->tabs->add_tab(
			'Adicionar',
			$this->admin_model->create($this->user->hasPermission(__CLASS__, 'users::create'), $isInstalled)
		);
		
		$this->data['content'] = $this->tabs->render();
		
		$this->load->view('admin/base', $this->data);
	}
	
	public function update($id = 0)
	{
		$isInstalled = $this->user->isInstalled();
		if (!$isInstalled) {
			redirect(base_url() . 'admin/users/create');
		}
		
		$hasPermission = $this->user->hasPermission(__CLASS__, __METHOD__);
		
		$this->tabs->add_tab(
			'Atualizar',
			$this->admin_model->update($id, $hasPermission, $isInstalled),
			true
		);
		
		$this->data['content'] = $this->tabs->render();
		
		$this->load->view('admin/base', $this->data);
	}
	
	public function delete($id = 0)
	{
		$this->user->isInstalled('users/create');
		
		$hasPermission = $this->user->hasPermission(__CLASS__, __METHOD__);
		
		$this->tabs->add_tab(
			'Apagar',
			$this->admin_model->delete($id, $hasPermission)
		);
		
		$this->data['content'] = $this->tabs->render();
		
		$this->load->view('admin/base', $this->data);
	}
}
