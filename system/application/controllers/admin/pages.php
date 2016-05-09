<?php

class pages extends Controller
{
	protected $data = array(
		'model'		=> '',
		'subtitle' 	=> '',
		'content'	=> '',
		'subtitle'	=> 'P&aacute;ginas',
		'load_js'	=> array(),
		'load_css'	=> array(),
	);
			
	public function __construct()
	{
		parent::Controller();
		
		$this->load->model('admin_' . __CLASS__, 'admin_model');
		$this->load->entity('CmsPageActions');
		$this->load->model('admin_user', 'user');
		
		$this->user->isLogged();
		
		$this->admin_model->url = strtolower(__CLASS__);
		
		$this->load->library('html_admin/tabs', 'tabs');
	}

	public function index()
	{
		$this->retrieve();
	}

	public function create()
	{	
		$hasPermission = $this->user->hasPermission(__CLASS__, __METHOD__);
		 
		$this->tabs->add_tab(
			'Adicionar',
			$this->admin_model->create($hasPermission),
			true
		);
		
		$this->data['content'] = $this->tabs->render();
		
		$this->load->view('admin/base', $this->data);
	}
	
	public function retrieve()
	{
		$hasPermission = $this->user->hasPermission(__CLASS__, __METHOD__);
		
		$this->tabs->add_tab(
			'Listar',
			$this->admin_model->retrieve($hasPermission),
			true
		);
		
		$this->tabs->add_tab(
			'Adicionar',
			$this->admin_model->create($this->user->hasPermission(__CLASS__, 'pages::create'))
		);
		
		$this->data['content'] = $this->tabs->render();
		
		$this->load->view('admin/base', $this->data);
	}
	
	public function refresh_pages()
	{
		$hasPermission = $this->user->hasPermission(__CLASS__, __METHOD__);
		
		$this->tabs->add_tab(
			'Atualizar P&aacute;ginas',
			$this->admin_model->refreshPages($hasPermission, true),
			true
		);
		
		$this->data['content'] = $this->tabs->render();
		
		$this->load->view('admin/base', $this->data);
	}
	
	public function refresh_methods($pageId = 0)
	{
		$hasPermission = $this->user->hasPermission(__CLASS__, __METHOD__);
		
		$this->tabs->add_tab(
			'Atualizar P&aacute;ginas',
			$this->admin_model->refreshPageMethods($pageId, $hasPermission),
			true
		);
		
		$this->data['content'] = $this->tabs->render();
		
		$this->load->view('admin/base', $this->data);
	}
	
	public function delete_method($pageId = 0, $actionId = 0)
	{
		$hasPermission = $this->user->hasPermission(__CLASS__, __METHOD__);
		
		$this->tabs->add_tab(
			'Apagar P&aacute;ginas',
			$this->admin_model->deleteMethod($pageId, $actionId, $hasPermission),
			true
		);
		
		$this->data['content'] = $this->tabs->render();
		
		$this->load->view('admin/base', $this->data);
	}
	
	public function create_method($pageId = 0)
	{
		$hasPermission = $this->user->hasPermission(__CLASS__, __METHOD__);
		
		$this->tabs->add_tab(
			'Criar M&eacute;todo Customizado',
			$this->admin_model->createPageMethods($pageId, $hasPermission),
			true
		);
		
		$this->data['content'] = $this->tabs->render();
		
		$this->load->view('admin/base', $this->data);
	}
	
	public function update_method($pageId = 0, $actionId = 0)
	{
		$hasPermission = $this->user->hasPermission(__CLASS__, __METHOD__);
		
		$this->tabs->add_tab(
			'Atualizar M&eacute;todo',
			$this->admin_model->updatePageMethods($pageId, $actionId, $hasPermission),
			true
		);
		
		$this->data['content'] = $this->tabs->render();
		
		$this->load->view('admin/base', $this->data);
	}
	
	public function retrieve_methods($pageId = 0)
	{
		$hasPermission = $this->user->hasPermission(__CLASS__, __METHOD__);
		
		$this->tabs->add_tab(
			'Listar M&eacute;todos',
			$this->admin_model->retrieveMethods($pageId, $hasPermission),
			true
		);
		
		$this->data['content'] = $this->tabs->render();
		
		$this->load->view('admin/base', $this->data);
	}
	
	public function update($id = 0)
	{
		$hasPermission = $this->user->hasPermission(__CLASS__, __METHOD__);
		
		$this->tabs->add_tab(
			'Atualizar',
			$this->admin_model->update($id, $hasPermission),
			true
		);
		
		$this->data['content'] = $this->tabs->render();
		
		$this->load->view('admin/base', $this->data);
	}
	
	public function delete($id = 0)
	{
		$hasPermission = $this->user->hasPermission(__CLASS__, __METHOD__);
		
		$this->tabs->add_tab(
			'Apagar',
			$this->admin_model->delete($id, $hasPermission)
		);
		
		$this->data['content'] = $this->tabs->render();
		
		$this->load->view('admin/base', $this->data);
	}
}
