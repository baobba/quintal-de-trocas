<?php

class usuario extends Controller
{
	public function __construct()
	{
		parent::Controller();
		
		$this->load->entity('CmsNews');
		$this->load->entity('CmsNewsletter');
		$this->load->entity('CmsClient');
		$this->load->entity('CmsToy');
		$this->load->entity('CmsToyImage');
		$this->load->entity('CmsToyAge');
		$this->load->entity('CmsToyCity');
		$this->load->entity('CmsToyState');
		$this->load->entity('CmsToyBrand');
		$this->load->entity('CmsToyCategory');
		$this->load->model('useful');
		$this->load->preArray();
	}
	
	public function login()
	{
	    if ($this->useful->isLogged()) {
	        redirect(URL_USUARIO_MEUS_DADOS);
	        exit;
	    }

	    $email = isset($_POST['email']) ? $_POST['email'] : null;
	    $email = trim($email);
        $password = isset($_POST['password']) ? $_POST['password'] : null;
        $password = trim($password);
	    
        $message = '';
        
        if ($email !== '' && $password !== '') {
            try {
                if ($this->useful->login($email, $password)) {
                    redirect(URL_USUARIO_MEUS_DADOS);
                    exit;
                }
            } catch (Exception $e) {
                $message = sprintf('<p>%s</p>', $e->getMessage());
            }
        }
        
		$this->load->view('frontend/top', $this->data);
		$this->load->view('frontend/login', array(
            'email' => $email === '' ? 'Digite seu e-mail' : '',
		    'message' => $message,
        ));
		$this->load->view('frontend/footer', array(
	        'news' => CmsNews::getNewsFooter()
		));
	}
	
	public function recuperar_senha()
	{
	    if ($this->useful->isLogged()) {
	        redirect(URL_USUARIO_MEUS_DADOS);
	        exit;
	    }
	
	    $this->load->view('frontend/top', $this->data);
	    $this->load->view('frontend/recuperar_senha', array(
            'formx' => $this->useful->getRecoveryPasswordForm()
	    ));
	    $this->load->view('frontend/footer', array(
            'news' => CmsNews::getNewsFooter()
	    ));
	}
	
	public function recuperar($code = '')
	{
	    if ($this->useful->isLogged()) {
	        redirect(URL_USUARIO_MEUS_DADOS);
	        exit;
	    }
	    
	    $code = trim($code);
	    
	    if ($code == '') {
	        redirect(URL_USUARIO_RECUPERAR_SENHA);
	        exit;
	    }
	    
	    $criteria = new Criteria();
	    $criteria->setHydrate();
	    $criteria->setWhere(array('recovery_code' => $code));
	     
	    $client = CmsClient::findBy($criteria);
	    
	    if ($client->count() !== 1) {
	        redirect(URL_USUARIO_RECUPERAR_SENHA);
	        exit;
	    }
	    
	
	    $this->load->view('frontend/top', $this->data);
	    $this->load->view('frontend/recuperar', array(
	        'code' => $code,
            'formx' => $this->useful->getRecoveryForm($code)
	    ));
	    $this->load->view('frontend/footer', array(
            'news' => CmsNews::getNewsFooter()
	    ));
	}
	
	public function criar_conta()
	{
	    if ($this->useful->isLogged()) {
            redirect(URL_USUARIO_MEUS_DADOS);
            exit;    
	    }
	    
	    $preArray = new Prearray();
	    
	    $this->load->view('frontend/top', $this->data);
	    $this->load->view('frontend/criar_conta', array(
            'formx' => $this->useful->getNewAccountForm($preArray->estado()),
            'states' => $preArray->estado(),
            '_state' => isset($_POST['state']) ? $_POST['state'] : null,
	    ));
	    $this->load->view('frontend/footer', array(
	        'news' => CmsNews::getNewsFooter()
		));
	    
	    if ($this->useful->isLogged()) {
	        redirect(URL_USUARIO_MEUS_DADOS);
	        exit;
	    }
	}
	
	public function facebook()
	{
	    if ($this->useful->isLogged()) {
	        redirect(URL_USUARIO_MEUS_DADOS);
	        exit;
	    }
	    if(strpos($_SERVER["REQUEST_URI"],"code=") == true){
                $url = explode("code=",$_SERVER["REQUEST_URI"]);
                $code = $url[1];
            }
	    if (!isset($_GET['code']) && !isset($code)) {
	        redirect(URL_USUARIO_LOGIN);
	        exit;
	    }
	    
        $appId = '631147513594252';
        $appSecret = 'fea761443ea7665e50b66c84f26d6444'; 

        $redirectUri = urlencode(base_url() . 'usuario/facebook/');
 
        if(!isset($code)){
            $code = $_GET['code'];
        }
        $token_url = "https://graph.facebook.com/oauth/access_token?client_id=%s&redirect_uri=%s&client_secret=%s&code=%s";
        $token_url = sprintf($token_url, $appId, $redirectUri, $appSecret, $code);
        $ch = curl_init($token_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        $params = null;
        
        parse_str($response, $params);
        
        if (isset($params['access_token']) && $params['access_token']){
            $graph_url = "https://graph.facebook.com/me?access_token=" . $params['access_token'];
            $ch = curl_init($graph_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);
            $response = utf8_decode($response);
            $response = json_decode($response);
            if ($response instanceof stdClass && $response->email && $response->id && $response->name && $response->email) {
                $image = file_get_contents(sprintf('http://graph.facebook.com/%s/picture?type=large', $response->id));
                
                @file_put_contents(FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . $avatar = uniqid() . '.jpg', $image);
                
                $criteria = new Criteria();
                $criteria->setWhere(array('email' => $response->email));
                $user = CmsClient::findBy($criteria);
                
                if (count($user) == 1) {
                    $this->useful->forceLogin($user[0]);
                    
                } else {
                    $birthday = isset($response->birthday) ? $response->birthday : '';
                    $birthday = explode('/', $birthday);
                    $birthday = count($birthday) == 3 ? sprintf('%s-%s-%s 00:00:00', $birthday[2], $birthday[1], $birthday[0]) : date('Y-m-d H:i:s');
                    
                    $user = new CmsClient();
                    $user->setAvatar($avatar);
                    $user->setBirth_date($birthday);
                    $user->setName($response->name);
                    $user->setEmail($response->email);
                    $user->setGender($response->gender == 'male' ? 'm' : 'f');
                    $user->setPassword($pass = uniqid());
                    $user->setSalt(uniqid());
                    $user->setPassword(md5($pass . $user->getSalt()));
                    $user->save();
                    
                    $this->useful->login($response->email, $pass);
                }
            }
        }
        
        redirect(URL_USUARIO_MEUS_DADOS);
    }
	
	public function logout()
	{
	    $this->useful->logout();
	    
	    redirect(URL_USUARIO_LOGIN);
	    exit;
	}
	
	public function meus_dados($tabSelected = null)
	{
	    if ($this->useful->isLogged() === false) {
	        redirect(URL_USUARIO_LOGIN);
	        exit;
	    }

	    $this->load->entity('CmsExchangeMessage');
	    $this->load->entity('CmsExchange');
	    
	    $preArray = new Prearray();
	    
	    $toyAges = CmsToyAge::getCombo();
	    $toyCategories = CmsToyCategory::getCombo();
	    $toyBrands = CmsToyBrand::getCombo();
	    $toyStates = CmsToyState::getCombo();
	    
	    $formxUserData = $this->useful->getUpdateAccountForm($preArray->estado());
	    $formxNewToy = $this->useful->getNewToyForm($toyAges, $toyCategories, $toyBrands, $toyStates);
	    $user = $this->useful->getLoggedUser();
	    
	    switch (true) {
	        case $formxNewToy->is_posted():
	            $tabSelected = 2;

	            break;
            default:
                break;
	    }
	    
	    if ($tabSelected == 4) {
	        $this->useful->reply($user['id']);
	    }
	    
	    $this->data['toyCity'] = isset($_REQUEST['toy_city']) ? $_REQUEST['toy_city'] : '';
	    
	    $this->load->view('frontend/top', $this->data);
	    $this->load->view('frontend/meus_dados', array(
	        'formxUserData' => $formxUserData,
            'formxUserDataView' => $this->load->view('frontend/form/usuario_editar.php', array(
                'formxUserData' => $formxUserData,
                'states' => $preArray->estado(),
                '_state' => isset($_POST['state']) ? $_POST['state'] : $user['state'],
            ), true),
	            
	        'formxNewToy' => $formxNewToy,
            'formxNewToyView' => $this->load->view('frontend/form/novo_brinquedo.php', array(
                'formxNewToy' => $formxNewToy,
                'toyAges' => $toyAges,
                'toyCategories' => $toyCategories,
    	        'toyBrands' => $toyBrands,
                'toyStates' => $toyStates
            ), true),

            'exchanges' => CmsExchange::getExchangesByUser($user['id']), 
	        'messages' => CmsExchangeMessage::getMessages($user['id']),
	        'toys' => CmsToy::getUserToys($user['id']),
                'toysExchanged' => CmsToy::getUserToysExchanged($user['id']),
	        'user' => $user,
	        'tabSelected' => $tabSelected,
	    ));
	    $this->load->view('frontend/footer', array(
	        'news' => CmsNews::getNewsFooter()
		));
	}
	
	public function editar_brinquedo($toyId)
	{
	    if ($this->useful->isLogged() === false) {
	        redirect(URL_USUARIO_LOGIN);
	        exit;
	    }
	    
	    $user = $this->useful->getLoggedUser();
	    
	    $userToy = CmsToy::getUserToyByToyId($toyId);

	    $preArray = new Prearray();
	    $formxUserData = $this->useful->getUpdateAccountForm($preArray->estado());
	    
	    if (!$userToy instanceof stdClass) {
	        redirect(URL_USUARIO_MEUS_DADOS_CRIAR_BRINQUEDO);
	        exit;
	    }
	    
	    $toyAges = CmsToyAge::getCombo();
	    $toyCities = CmsToyCity::getCombo();
	    $toyCategories = CmsToyCategory::getCombo();
	    //$toyBrands = CmsToyBrand::getCombo();
	    $toyStates = CmsToyState::getCombo();
	    
	    //$formxUpdateToy = $this->useful->getUpdateToyForm($userToy, $toyAges, $toyStates, $toyCities, $toyCategories, $toyBrands);

	    $formxUpdateToy = $this->useful->getUpdateToyForm($userToy, $toyAges, $toyStates, $toyCities, $toyCategories);
	    
	    $this->data['toyCity'] = $userToy->cms_toy_city_id;
	    
	    $cmsToyImage = CmsToyImage::getMainImageByToyId($userToy->id);
	    $cmsToyImageExtra1 = CmsToyImage::getImageByToyId($userToy->id, 'extra1');
	    $cmsToyImageExtra2 = CmsToyImage::getImageByToyId($userToy->id, 'extra2');
	    
	    $this->load->view('frontend/top', $this->data);
	    $this->load->view('frontend/editar_brinquedo', array(
	    	'formxUserData' => $formxUserData,
            'formxUpdateToy' => $formxUpdateToy,
            'formxUpdateToyView' => $this->load->view('frontend/form/editar_brinquedo', array(
                'images' => array($cmsToyImage, $cmsToyImageExtra1, $cmsToyImageExtra2),
                'formxUpdateToy' => $formxUpdateToy,
                'formxUserData' => $formxUserData,
    	        'toyId' => $toyId,
                'toyAges' => $toyAges,
                'toyCities' => $toyCities,
                'toyStates' => $toyStates,
                'toyCategories' => $toyCategories,
                //'toyBrands' => $toyBrands
                ), true),
            'user' => $user,
	    ));
	    $this->load->view('frontend/footer', array(
	            'news' => CmsNews::getNewsFooter()
	    ));
	}
	
	public function imagens($toyId)
	{
	    if ($this->useful->isLogged() === false) {
	        redirect(URL_USUARIO_LOGIN);
	        exit;
	    }
	     
	    $user = $this->useful->getLoggedUser();
	     
	    $userToy = CmsToy::getUserToyByToyId($toyId);
	    
	    if (!$userToy instanceof stdClass) {
	        redirect(URL_USUARIO_MEUS_DADOS_CRIAR_BRINQUEDO);
	        exit;
	    }
	    
	    $formxUpdateToy = $this->useful->getUpdateToyImageForm($userToy);
	     
	    $this->load->view('frontend/top', $this->data);
	    $this->load->view('frontend/editar_imagens', array(
            'formxUpdateToyImages' => $formxUpdateToy,
            'formxUpdateToyImagesView' => $this->load->view('frontend/form/editar_imagens', array(
                'formxUpdateToyImages' => $formxUpdateToy,
                'toyId' => $toyId), true),
            'user' => $user,
	    ));
	    $this->load->view('frontend/footer', array(
	            'news' => CmsNews::getNewsFooter()
	    ));
	}
	
	public function deletar_imagem($toyId, $cmsToyImageId)
	{
	    if ($this->useful->isLogged() === false) {
	        redirect(URL_USUARIO_LOGIN);
	        exit;
	    }
	    
	    $user = $this->useful->getLoggedUser();
	    
	    $userToy = CmsToy::getUserToyByToyId($toyId);
	    
	    if (!$userToy instanceof stdClass) {
	        redirect(URL_USUARIO_EDITAR_BRINQUEDO . $toyId );
	        exit;
	    }
	    
	    if (($image = CmsToyImage::getByIdAndToyId($cmsToyImageId, $toyId)) == null) {
	        redirect(URL_USUARIO_EDITAR_BRINQUEDO . $toyId );
	        exit;
	    }
	    
// 	    if ($image->getName() == CmsToyImage::NAME_MAIN) {
// 	        redirect(URL_USUARIO_EDITAR_BRINQUEDO . $toyId );
// 	        exit;
// 	    }
	    
	    unlink(FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . $image->getImage());
	    
	    $image->delete();
	    
	    redirect(URL_USUARIO_EDITAR_BRINQUEDO . $toyId);
	    exit;
	}
	
	public function deletar_brinquedo($toyId)
	{
	    if ($this->useful->isLogged() === false) {
	        redirect(URL_USUARIO_LOGIN);
	        exit;
	    }
	     
	    $user = $this->useful->getLoggedUser();
	     
	    $userToy = CmsToy::getUserToyByToyId($toyId);
	     
	    if (!$userToy instanceof stdClass) {
	        redirect(URL_USUARIO_MEUS_DADOS_CRIAR_BRINQUEDO);
	        exit;
	    }
	     
	    CmsToy::setAsDeleted($toyId);
	    
	    redirect(URL_USUARIO_MEUS_DADOS_MEUS_BRINQUEDOS);
	    exit;
	}
	
	public function aceitar_troca($exchangeId)
	{
	    $this->load->entity('CmsExchange');
	    
	    if ($this->useful->isLogged() === false) {
	        redirect(URL_USUARIO_LOGIN);
	        exit;
	    }
	    
	    $user = $this->useful->getLoggedUser();
	    
	    if (CmsExchange::canAcceptExchange($exchangeId, $user['id'])) {
	        CmsExchange::acceptExchange($exchangeId);
	    }
	    
	    redirect(URL_USUARIO_MEUS_DADOS_MINHAS_TROCAS);
	}
	
	public function recusar_troca($exchangeId)
	{
	    $this->load->entity('CmsExchange');
	     
	    if ($this->useful->isLogged() === false) {
	        redirect(URL_USUARIO_LOGIN);
	        exit;
	    }
	     
	    $user = $this->useful->getLoggedUser();
	     
	    if (CmsExchange::canAcceptExchange($exchangeId, $user['id'])) {
	        CmsExchange::declineExchange($exchangeId);
	        
	        $criteria = new Criteria();
	        $criteria->setHydrate();
	        $criteria->setWhere(array('id' => $exchangeId));
	        
	        $exchange = CmsExchange::findBy($criteria);
	        $exchange = $exchange->offsetGet(0);
	        
	        $fromToy = CmsToy::getById($exchange->getFrom_toy());
	        
	        if ($fromToy->getCms_client_id() == $user['id']) {
	           $toy = CmsToy::getById($exchange->getTo_toy());
	           
	        } else {
	           $toy = $fromToy;
	        }
	        
	        $criteria = new Criteria();
	        $criteria->setHydrate();
	        $criteria->setWhere(array('id' => $toy->getCms_client_id()));
	        
	        $client = CmsClient::findBy($criteria);
	        $client = $client->offsetGet(0);
	        
	        _mail($client->getEmail(), 'Recusa de Troca', 'troca_recusada', array(
    	        'name' => $client->getName(),
    	        'toyName' => trim($toy->getName()),
	        ));
	    }
	     
	    redirect(URL_USUARIO_MEUS_DADOS_MINHAS_TROCAS);
	}
	
	public function finalizar_troca($exchangeId)
	{
	    $this->load->entity('CmsExchange');
	    
	    if ($this->useful->isLogged() === false) {
	        redirect(URL_USUARIO_LOGIN);
	        exit;
	    }
	    
	    $user = $this->useful->getLoggedUser();
	    
	    $this->useful->finish($exchangeId, $user['id']);
	    
	    redirect(URL_USUARIO_MEUS_DADOS_MINHAS_TROCAS);
	}
}
