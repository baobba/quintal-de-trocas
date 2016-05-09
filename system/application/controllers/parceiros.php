<?php

class parceiros extends Controller
{
	public function __construct()
	{
		parent::Controller();
	}
	
	public function index()
	{
	    $this->load->entity('CmsNews');
	    $this->load->entity('CmsPartner');
	    
	    $criteria = new Criteria();
	    $criteria->setOrder_by('ordering');
	    $criteria->setWhere(array('active' => 1));
	    
		$this->load->view('frontend/top', $this->data);
		$this->load->view('frontend/parceiros', array(
            'partners' => CmsPartner::findBy($criteria)
        ));
		$this->load->view('frontend/footer', array(
	        'news' => CmsNews::getNewsFooter()
		));
	}
}
