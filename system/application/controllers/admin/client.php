<?php

class client extends Controller
{
	protected $data = array(
		'model'		=> '',
		'subtitle' 	=> '',
		'content'	=> '',
		'subtitle'	=> 'Clientes',
		'load_js'	=> array(),
		'load_css'	=> array(),
	);
			
	public function __construct()
	{
		parent::Controller();
				
		$this->load->model('admin_' . __CLASS__, 'admin_model');
		
		$entity = 'Cms' . implode('', array_map('ucfirst', explode('_', __CLASS__)));
		$this->load->entity($entity);
		$this->load->entity('CmsPressCategory');
		$this->load->model('admin_user', 'user');

		$this->user->isLogged();
		
		$this->admin_model->url    = strtolower(__CLASS__);
		$this->admin_model->entity = $entity;
		
		$this->load->library('html_admin/tabs', 'tabs');
		$this->load_js('jquery.screenshotpreview');
	}

	public function index()
	{
		$this->retrieve();
	}

	public function retrieve()
	{		
		$this->tabs->add_tab(
			'Listar',
			$this->admin_model->retrieve($this->user->hasPermission(__CLASS__, __METHOD__)),
			true
		);
		
		$this->data['content'] = $this->tabs->render();
		
		$this->load->view('admin/base', $this->data);
	}
	
	public function update($id = 0)
	{
		$this->tabs->add_tab(
			'Visualizar',
			$this->admin_model->update($id, $this->user->hasPermission(__CLASS__, __METHOD__)),
			true
		);
		
		$this->data['content'] = $this->tabs->render();
		
		$this->load->view('admin/base', $this->data);
	}	
}
