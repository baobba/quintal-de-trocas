<?php

class pontos_de_trocas extends Controller
{
	public function __construct()
	{
		parent::Controller();
	}
	
	public function index()
	{
		$this->load->entity('CmsNews');
		$this->load->entity('CmsExchangePoint');
		$this->load->entity('CmsToyState');
		$this->load->entity('CmsToyCity');

		$state = isset($_POST['state']) ? $_POST['state'] : null;
		$city = isset($_POST['city']) ? $_POST['city'] : null;
		
		$_state = explode('/', $state);
		$_state = end($_state);
		
		$exchangePoints = array();
		if ($_state | $city) {
		    
		    $exchangePoints = CmsExchangePoint::getExchangePoints($_state, $city)->result();
		}
		
		$stateId = null;
		if ($state) {
		    $criteria = new Criteria();
		    $criteria->setLike('acronym', $_state);
		    $criteria->setHydrate();
		    
		    $found = CmsToyState::findBy($criteria);
            $found = $found->count() ? $found->offsetGet(0) : null;
            
            if ($found) {
                $stateId = $found->getId();
            }
		}
		
		$this->load->view('frontend/top', $this->data);
		$this->load->view('frontend/pontos_de_troca', array(
            'states' => CmsToyState::getCombo(),
	        'cities' => $stateId ? CmsToyCity::getComboByStateId($stateId) : array(),
		    'exchangePoints' => $exchangePoints,
		    '_state' => $state,
		    '_city' => $city
		                    
        ));
		$this->load->view('frontend/footer', array(
	        'news' => CmsNews::getNewsFooter()
		));
	}
}
