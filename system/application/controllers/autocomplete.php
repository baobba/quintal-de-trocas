<?php

class autocomplete extends Controller
{
	public function __construct()
	{
		parent::Controller();
	}
	
	public function city()
	{
	    $q = isset($_REQUEST['q']) ? $_REQUEST['q'] : '';
	    $q = trim($q);
	    
	    if ($q === '') {
	        return;
	    }
	    
	    $this->load->entity('CmsToyCity');
	    
	    foreach (CmsToyCity::findWithStateByName($q) as $id => $name) {
	        echo sprintf("%s|%s\n", $name, $id);
	    }
	}
	
	public function state($stateId)
	{
	    $this->load->entity('CmsToyCity');
	    $stateId = explode('callback', $stateId);
	    $stateId = str_replace("&", '', $stateId[0]);

	    $response = array();
	    foreach (CmsToyCity::getComboByStateId($stateId) as $id => $value) {
	        $response[] = array('id' => $id, 'value' => $value);
	    }
	   header('Content-Type: application/json'); 
	   echo sprintf('%s', json_encode($response));
	}
}

