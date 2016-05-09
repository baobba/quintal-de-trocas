<?php

class quem_somos extends Controller
{
	public function __construct()
	{
		parent::Controller();
	}
	
	public function index()
	{
	    $this->load->entity('CmsContent');
	    $this->load->entity('CmsNews');

		$this->load->view('frontend/top', $this->data);
		$this->load->view('frontend/quem_somos', array(
            'content' => CmsContent::get(CmsContent::ID_QUEM_SOMOS)
        ));
		$this->load->view('frontend/footer', array(
	        'news' => CmsNews::getNewsFooter()
		));
	}
}
