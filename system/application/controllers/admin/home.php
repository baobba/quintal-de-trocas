<?php

class home extends Controller
{
	protected $data = array(
		'subtitle' 	=> 'Home',
		'content'	=> '',
		'load_js'	=> array(),
		'load_css'	=> array(),
	);
	
	public function __construct()
	{
		parent::Controller();
		
		$this->load->model('admin_user', 'user');
		$this->user->isLogged();
	}
	
	public function index()
	{
		$this->data['content'] = '
			<p>
				<b>' . $this->user->getUser()->name .',</b>
				<br />
				Seja bem vindo ao Gerenciador de Conte&uacute;do do seu website.
			</p>';
		
		$this->load->view('admin/base', $this->data);
	}
}
