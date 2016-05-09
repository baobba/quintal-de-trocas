<?php

class como_funciona extends Controller
{
	public function __construct()
	{
		parent::Controller();
	}
	
	public function index()
	{
	    $this->load->entity('CmsNews');
	    
		$this->load->view('frontend/top', $this->data);
		$this->load->view('frontend/como_funciona', array(
            
        ));
		$this->load->view('frontend/footer', array(
	        'news' => CmsNews::getNewsFooter()
		));
	}
}
