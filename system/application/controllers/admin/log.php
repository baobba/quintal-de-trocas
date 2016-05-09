<?php

class log extends Controller
{
	public function __construct()
	{
		parent::Controller();
		$this->load->model('admin_user', 'user');
	}
	
	public function index()
	{
		if ($this->user->isLogged(false) == false && $this->user->isInstalled() == false) {
			redirect('admin/users/create');
			exit;
		}
		$this->in();
	}
	
	public function in()
	{
		$this->user->login();
		if ($this->user->isLogged(false)) {
			redirect('admin/home');
			exit;
		}
		$this->load->view('admin/login');
	}
	
	public function out()
	{
		$this->user->logout();
	}
}
