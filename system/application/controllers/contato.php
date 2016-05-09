<?php

class contato extends Controller
{
	public function __construct()
	{
		parent::Controller();
	}
	
	public function index()
	{
	    $this->load->entity('CmsNews');
	    $this->load->model('useful');
	    $this->load->preArray();
	    
	    $preArray = new Prearray();
	    
	    $state = '';
	     
        if ($this->useful->isLogged()) {
            $user = $this->useful->getLoggedUser();
            $state = $user['state'];
	    }
	    
		$this->load->view('frontend/top', $this->data);
		$this->load->view('frontend/contato', array(
            'states' => array_combine(array_values($preArray->estado()), array_values($preArray->estado())),
		    '_state' => isset($_POST['state']) ? $_POST['state'] : $state,
		    'formx' => $this->useful->getContactForm(),
        ));
		$this->load->view('frontend/footer', array(
	        'news' => CmsNews::getNewsFooter()
		));
	}
}

