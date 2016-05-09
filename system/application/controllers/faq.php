<?php

class faq extends Controller
{
	public function __construct()
	{
		parent::Controller();
	}
	
	public function index()
	{
		$this->load->entity('CmsNews');
	    $this->load->entity('CmsFaq');
	    
	    $criteria = new Criteria();
	    $criteria->setOrder_by('ordering');
	    $criteria->setWhere(array('active' => 1));
	    
		$this->load->view('frontend/top', $this->data);
		$this->load->view('frontend/faq', array(
            'faqs' => CmsFaq::findBy($criteria)
        ));
		$this->load->view('frontend/footer', array(
	        'news' => CmsNews::getNewsFooter()
		));
	}
}
