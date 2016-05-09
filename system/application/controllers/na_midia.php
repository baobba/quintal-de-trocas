<?php

class na_midia extends Controller
{
	public function __construct()
	{
		parent::Controller();
	}
	
	public function index()
	{
	    $catId = isset($_POST['catId']) ? $_POST['catId'] : null;
	    $year = isset($_POST['year']) ? $_POST['year'] : null;
	    
	    $this->load->entity('CmsNews');
	    $this->load->entity('CmsPress');
	    $this->load->entity('CmsPressCategory');

	    $this->load->view('frontend/top', $this->data);
	    $this->load->view('frontend/na_midia', array(
	       'categories' => CmsPressCategory::getCombo(),
	       'press' => CmsPress::getPress($catId, $year),
	       'years' => range('2013', date('Y')),
	       '_catId' => $catId,
	       '_year' => $year 
	    ));
	    $this->load->view('frontend/footer', array(
	        'news' => CmsNews::getNewsFooter()
		));
	}
}
