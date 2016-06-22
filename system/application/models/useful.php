<?php

class useful extends Model
{
	public function __construct()
	{
		parent::Model();	
	}
	
	public function isLogged()
	{
	    return isset($_SESSION['logged']) && $_SESSION['logged'] == true;
	}
	
	public function getLoggedUser()
	{
	    return unserialize($_SESSION['logged_user']);
	}
	
	public function forceLogin(stdClass $client)
	{
	    $client = (array) $client;
	    
	    unset($client['password']);
	    unset($client['salt']);
	     
	    $_SESSION['logged'] = true;
	    $_SESSION['logged_user'] = serialize($client);
	}
	
	public function login($email, $pass)
	{
	    $criteria = new Criteria();
	    $criteria->setWhere(array('email' => $email));
	    
	    $client = CmsClient::findBy($criteria);
	    
	    if (count($client) == 1) {
	        $client = array_shift($client);
	        
	        if (md5($pass . $client->salt) !== $client->password) {
	            throw new InvalidArgumentException('Senha inv&aacute;lida');
	        }
	        
	        $this->forceLogin($client);

	    } else {
            throw new InvalidArgumentException(sprintf('Usu&aacute;rio %s n&atilde;o encontrado.', $email)); 
	    }
	    
	    return true;
	}
	
	public function logout()
	{
	    $_SESSION['logged'] = null;
	    $_SESSION['logged_user'] = null;
	    
	    unset($_SESSION['logged']);
	    unset($_SESSION['logged_user']);
	}
	
	public function getRecoveryPasswordForm()
	{
	    $CI =& get_instance();
	    $CI->load->formx();
	     
	    $formx = new Formx(formx::METHOD_POST, '', '', '', 'recovery_password');
	    
	    $formx->add_text('email')->set_validates(array('email'))->set_label('Email');
	    $formx->add_text('email_check')->set_validates(array('email'))->set_label('Email (Confirma&ccedil;&atilde;o)');
	    
	    if ($formx->is_post()) {
	        if ($formx->has_error() == false) {
	            $email = trim($formx->use_field('email')->get_posted());
	            $emailCheck = $formx->use_field('email_check')->get_posted();
	            
	            if ($email !== $emailCheck) {
	                $formx->use_field('email_check')->set_error_message('O e-mail de confirma&ccedil;&atilde;o n&atilde;o é igual.');
	            }

	            $criteria = new Criteria();
	            $criteria->setHydrate();
	            $criteria->setWhere(array('email' => $email));
	            
	            $client = CmsClient::findBy($criteria);
	            
	            if ($client->count() !== 1) {
	                $formx->use_field('email')->set_error_message('E-mail n&atilde;o encontrado.');
	            }
	            
                if ($formx->has_error() == false) {
                    $client = $client->offsetGet(0);
                    $client->setRecovery_code($rc = uniqid());
                    $client->update();
                    
                    _mail($client->getEmail(), 'Recuperar Senha', 'recuperar_senha', array(
                        'name' => $client->getName(),
                        'link' => base_url() . URL_USUARIO_RECUPERAR_SENHA_CODIGO . $rc)
                    );
                    
	                $formx->reset();
	                $formx->set_is_sucess();
                }
	        }
	    }
	    
	    return $formx;
	}
	
	public function getRecoveryForm($code)
	{
	    $CI =& get_instance();
	    $CI->load->formx();
	
	    $formx = new Formx(formx::METHOD_POST, '', '', '', 'recovery');
	     
	    $formx->add_text('password')->set_validates(array('required'))->set_label('Nova Senha');
	    $formx->add_text('password_check')->set_validates(array('required'))->set_label('Nova Senha (Confirma&ccedil;&atilde;o)');
	     
	    if ($formx->is_post()) {
	        if ($formx->has_error() == false) {
	            $password = trim($formx->use_field('password')->get_posted());
	            $passwordCheck = $formx->use_field('password_check')->get_posted();
	            
	            if ($password !== $passwordCheck) {
	                $formx->use_field('password_check')->set_error_message('A senha de confirma&ccedil;&atilde;o n&atilde;o é igual.');
	            }
	
	            $criteria = new Criteria();
	            $criteria->setHydrate();
	            $criteria->setWhere(array('recovery_code' => $code));
	            
	            $client = CmsClient::findBy($criteria);
	             
	            if ($client->count() !== 1) {
	                $formx->use_field('password_check')->set_error_message('C&oacute;digo n&atilde;o encontrado.');
	            }
	             
	            if ($formx->has_error() == false) {
	                $client = $client->offsetGet(0);
	                $client->setRecovery_code('');
	                $client->setSalt(uniqid());
                    $client->setPassword(md5($password . $client->getSalt()));
	                $client->update();
	                
	                $this->login($client->getEmail(), $password);
	                
	                redirect(URL_USUARIO_MEUS_DADOS);
	                
	                $formx->reset();
	                $formx->set_is_sucess();
	            }
	        }
	    }
	     
	    return $formx;
	}
	  

/*	    
	public function getUpdateToyForm($toy, $toyAges = array(), $toyCities = array(), $toyStates = array(), $toyCategories = array(), $toyBrands = array())
	{
	    $CI =& get_instance();
	    $CI->load->formx();
	    
	    $formx = new Formx(formx::METHOD_POST, '', '', '', 'new_toy');
	    
	    $toyAgeInterest = trim($toy->age_interest);
	    $toyAgeInterest = strlen($toyAgeInterest) ? json_decode($toyAgeInterest, 1) : array();
	    $toyAgeInterest = array_keys($toyAgeInterest);
	    
	    $toyCategoryInterest = trim($toy->category_interest);
	    $toyCategoryInterest = strlen($toyCategoryInterest) ? json_decode($toyCategoryInterest, 1) : array();
	    $toyCategoryInterest = array_keys($toyCategoryInterest);
	    
	    $toyBrandInterest = trim($toy->brand_interest);
	    $toyBrandInterest = strlen($toyBrandInterest) ? json_decode($toyBrandInterest, 1) : array();
	    $toyBrandInterest = array_keys($toyBrandInterest);
	    
	    $formx->add_text('name')->set_post_value($toy->name)->set_label('T&iacute;tulo do brinquedo')->set_validates(array('required'));
	    $formx->add_text('description')->set_post_value($toy->description)->set_label('Descri&ccedil;&atilde;o do brinquedo')->set_validates(array('required'));
	    $formx->add_text('weight')->set_post_value($toy->weight)->set_label('Peso do brinquedo');
	    $formx->add_radio('toy_age')->set_label('Faixa et&aacute;ria')->set_validates(array('required'))->set_value($toyAges)->set_post_value($toy->cms_toy_age_id);
	    
	    $formx->add_multiple('toy_age_interest')->set_value($toyAges)->set_post_value($toyAgeInterest);
	    $formx->add_multiple('toy_category_interest')->set_value($toyCategories)->set_post_value($toyCategoryInterest);
	    $formx->add_multiple('toy_brand_interest')->set_value($toyBrands)->set_post_value($toyBrandInterest);
	    
	    $formx->add_radio('toy_category')->set_label('Tipo de Brinquedo')->set_validates(array('required'))->set_value($toyCategories)->set_post_value($toy->cms_toy_category_id);
	    $formx->add_text('toy_state')->set_label('Estado')->set_validates(array('required'))->set_value($toyStates)->set_post_value(CmsToyCity::getStateIdById($toy->cms_toy_city_id));
	    $formx->add_text('toy_city')->set_post_value($toy->cms_toy_city_id);
	    $formx->add_radio('toy_brand')->set_label('Marca do brinquedo')->set_validates(array('required'))->set_value($toyBrands)->set_post_value($toy->cms_toy_brand_id);
	    $formx->add_file('image');

	    if ($formx->is_post()) {
	        $cmsToyImage = CmsToyImage::getMainImageByToyId($toy->id);
	        
            if ($formx->use_field('image')->upload_image(array('uploadPath' => './uploads/image', 'width' => 481, 'height' => 316)) && $cmsToyImage !== null) {
                @unlink(FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . $cmsToyImage->getImage());
                
                $cmsToyImage->setImage($formx->use_field('image')->get_posted());
                $cmsToyImage->setName('main');
            }
            
	        if ($formx->has_error() === false) {
	            $user = $this->getLoggedUser();
	            
	            $cmsToy = CmsToy::getById($toy->id);
	            $cmsToy->setName($formx->use_field('name')->get_posted());
	            $cmsToy->setDescription($formx->use_field('description')->get_posted());
	            $cmsToy->setWeight($formx->use_field('weight')->get_posted());
	            $cmsToy->setCreated_at(date('Y-m-d H:i:s'));
	            $cmsToy->setCms_toy_brand_id($formx->use_field('toy_brand')->get_posted());
	            $cmsToy->setCms_toy_category_id($formx->use_field('toy_category')->get_posted());
	            $cmsToy->setCms_toy_city_id($formx->use_field('toy_city')->get_posted());
	            $cmsToy->setCms_toy_age_id($formx->use_field('toy_age')->get_posted());
	            $cmsToy->setCms_client_id($user['id']);
	            
	            $ageInterest = array();
	            foreach ($formx->use_field('toy_age_interest')->get_posted() as $i) {
	                $ageInterest[$i] = $toyAges[$i];
	            }
	            
	            $cmsToy->setAge_interest(json_encode($ageInterest));
	            
	            $categoryInterest = array();
	            foreach ($formx->use_field('toy_category_interest')->get_posted() as $i) {
	                $categoryInterest[$i] = $toyCategories[$i];
	            }
	            
	            $cmsToy->setCategory_interest(json_encode($categoryInterest));
	            
	            $brandInterest = array();
	            foreach ($formx->use_field('toy_brand_interest')->get_posted() as $i) {
	                $brandInterest[$i] = $toyBrands[$i];
	            }
	            
	            $cmsToy->setBrand_interest(json_encode($brandInterest));
	            
	            $cmsToy->update();
	            
	            if ($cmsToyImage) {
	               $cmsToyImage->update();
	            }
	            
	            $formx->reset();
	            $formx->set_is_sucess();
	        }
	    }
	    
	    return $formx;
	}
*/


	public function getUpdateToyForm($toy, $toyAges = array(), $toyCities = array(), $toyStates = array(), $toyCategories = array())
	{
	    $CI =& get_instance();
	    $CI->load->formx();
	    
	    $formx = new Formx(formx::METHOD_POST, '', '', '', 'new_toy');
	    
    
	    $formx->add_text('name')->set_post_value($toy->name)->set_label('T&iacute;tulo do brinquedo')->set_validates(array('required'));
	    $formx->add_text('description')->set_post_value($toy->description)->set_label('Descri&ccedil;&atilde;o do brinquedo')->set_validates(array('required'));
	    $formx->add_radio('toy_age')->set_label('Faixa et&aacute;ria')->set_validates(array('required'))->set_value($toyAges)->set_post_value($toy->cms_toy_age_id);
	    
	    $formx->add_radio('toy_category')->set_label('Tipo de Brinquedo')->set_validates(array('required'))->set_value($toyCategories)->set_post_value($toy->cms_toy_category_id);
	    $formx->add_text('toy_state')->set_label('Estado')->set_validates(array('required'))->set_value($toyStates)->set_post_value(CmsToyCity::getStateIdById($toy->cms_toy_city_id));
	    $formx->add_text('toy_city')->set_post_value($toy->cms_toy_city_id);
	    $formx->add_file('image');

	    if ($formx->is_post()) {
	        $cmsToyImage = CmsToyImage::getMainImageByToyId($toy->id);
	        
            if ($formx->use_field('image')->upload_image(array('uploadPath' => './uploads/image', 'width' => 481, 'height' => 316)) && $cmsToyImage !== null) {
                @unlink(FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . $cmsToyImage->getImage());
                
                $cmsToyImage->setImage($formx->use_field('image')->get_posted());
                $cmsToyImage->setName('main');
            }
            
	        if ($formx->has_error() === false) {
	            $user = $this->getLoggedUser();
	            
	            $cmsToy = CmsToy::getById($toy->id);
	            $cmsToy->setName($formx->use_field('name')->get_posted());
	            $cmsToy->setDescription($formx->use_field('description')->get_posted());
	            $cmsToy->setCreated_at(date('Y-m-d H:i:s'));
	            $cmsToy->setCms_toy_category_id($formx->use_field('toy_category')->get_posted());
	            $cmsToy->setCms_toy_city_id($formx->use_field('toy_city')->get_posted());
	            $cmsToy->setCms_toy_age_id($formx->use_field('toy_age')->get_posted());
	            $cmsToy->setCms_client_id($user['id']);
	            
	            $cmsToy->update();
	            
	            if ($cmsToyImage) {
	               $cmsToyImage->update();
	            }
	            
	            $formx->reset();
	            $formx->set_is_sucess();
	        }
	    }
	    
	    return $formx;
	}

	
	public function getUpdateToyImageForm($toy)
	{
	    $CI =& get_instance();
	    $CI->load->formx();
	     
	    $formx = new Formx(formx::METHOD_POST, '', '', '', 'new_toy');
	    
	    $formx->add_file('image1');
	    $formx->add_file('image2');
	    $formx->add_file('image3');
	
	    if ($formx->is_post()) {
	        $cmsToyImage = CmsToyImage::getMainImageByToyId($toy->id);
	        $cmsToyImageExtra1 = CmsToyImage::getImageByToyId($toy->id, 'extra1');
	        $cmsToyImageExtra2 = CmsToyImage::getImageByToyId($toy->id, 'extra2');
	        
	        if ($formx->use_field('image1')->upload_image(array('uploadPath' => './uploads/image', 'width' => 481, 'height' => 316))) {
	            
	            if ($cmsToyImage) {
	               @unlink(FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . $cmsToyImage->getImage());
	            }
	            
	            if ($cmsToyImage) {
    	            $cmsToyImage->setImage($formx->use_field('image1')->get_posted());
    	            $cmsToyImage->update();
	                
	            } else {
	                $cmsToyImageExtra1 = new CmsToyImage();
	                $cmsToyImageExtra1->setCms_toy_id($toy->id);
	                $cmsToyImageExtra1->setImage($formx->use_field('image1')->get_posted());
	                $cmsToyImageExtra1->setName('main');
	                $cmsToyImageExtra1->save();
	            }
	        }
	        
	        if ($formx->use_field('image2')->upload_image(array('uploadPath' => './uploads/image', 'width' => 481, 'height' => 316))) {
	            if ($cmsToyImageExtra1) {
	                @unlink(FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . $cmsToyImageExtra1->getImage());
	                $cmsToyImageExtra1->setImage($formx->use_field('image2')->get_posted());
                    $cmsToyImageExtra1->update();

	            } else {
	                $cmsToyImageExtra1 = new CmsToyImage();
	                $cmsToyImageExtra1->setCms_toy_id($toy->id);
	                $cmsToyImageExtra1->setImage($formx->use_field('image2')->get_posted());
	                $cmsToyImageExtra1->setName('extra1');
	                $cmsToyImageExtra1->save();
	            }
	        }
	        
	        if ($formx->use_field('image3')->upload_image(array('uploadPath' => './uploads/image', 'width' => 481, 'height' => 316))) {
	            if ($cmsToyImageExtra2) {
	                @unlink(FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . $cmsToyImageExtra2->getImage());
	                $cmsToyImageExtra2->setImage($formx->use_field('image3')->get_posted());
	                $cmsToyImageExtra2->update();
	            } else {
	                $cmsToyImageExtra2 = new CmsToyImage();
	                $cmsToyImageExtra2->setCms_toy_id($toy->id);
	                $cmsToyImageExtra2->setImage($formx->use_field('image3')->get_posted());
	                $cmsToyImageExtra2->setName('extra2');
	                $cmsToyImageExtra2->save();
	            }
	        }
	
	        if ($formx->has_error() === false) {
	            $formx->reset();
	            $formx->set_is_sucess();
	        }
	    }
	     
	    return $formx;
	}
	
/*	
	public function getNewToyForm($toyAges = array(), $toyCategories = array(), $toyBrands = array(), $toyStates = array())
	{
	    $CI =& get_instance();
	    $CI->load->formx();
	     
	    $formx = new Formx(formx::METHOD_POST, '', '', '', 'new_toy');
	     
	    $formx->add_text('name')->set_label('T&iacute;tulo do brinquedo')->set_validates(array('required'));
	    $formx->add_text('description')->set_label('Descri&ccedil;&atilde;o do brinquedo')->set_validates(array('required'));
	    $formx->add_text('weight')->set_label('Peso do brinquedo');
	    $formx->add_select('toy_age')->set_label('Faixa et&aacute;ria')->set_validates(array('required'))->set_value($toyAges);
	    $formx->add_select('toy_category')->set_label('Tipo de Brinquedo')->set_validates(array('required'))->set_value($toyCategories);
	    $formx->add_text('toy_state')->set_label('Estado')->set_validates(array('required'))->set_value($toyStates);
	    $formx->add_text('toy_city')->set_label('Cidade')->set_validates(array('required'));
	    $formx->add_radio('toy_brand')->set_label('Marca do brinquedo')->set_validates(array('required'))->set_value($toyBrands);
	    
	    $formx->add_multiple('toy_age_interest')->set_value($toyAges);
	    $formx->add_multiple('toy_category_interest')->set_value($toyCategories);
	    $formx->add_multiple('toy_brand_interest')->set_value($toyBrands);
	    
	    $formx->add_file('image_main');
	    $formx->add_file('image_extra1');
	    $formx->add_file('image_extra2');
	
	    if ($formx->is_post()) {
	        $cmsToyImages = array();
	        
	        foreach (array(
	                array('name' => CmsToyImage::NAME_MAIN, 'required' => true),
	                array('name' => CmsToyImage::NAME_EXTRA1, 'required' => false),
	                array('name' => CmsToyImage::NAME_EXTRA2, 'required' => false)) as $image) {

    	        $cmsToyImage = new CmsToyImage();

    	        $name = $image['name'];
    	        $field = sprintf('image_%s', $name);
    	        
    	        if ($formx->use_field($field)->upload_image(array('uploadPath' => './uploads/image', 'width' => 481, 'height' => 316))) {
    	            $cmsToyImage->setImage($formx->use_field($field)->get_posted());
    	            $cmsToyImage->setName($name);
    	            
    	            $cmsToyImages[] = $cmsToyImage;
    	        
    	        } elseif ($image['required']) {
    	            $formx->use_field($field)->set_error_message('Voc&ecirc; precisa informar uma imagem.');
    	        }
	        }
	
	        if ($formx->has_error() === false) {
	            $user = $this->getLoggedUser();
	             
	            $cmsToy = new CmsToy();
	            $cmsToy->setName($formx->use_field('name')->get_posted());
	            $cmsToy->setDescription($formx->use_field('description')->get_posted());
	            $cmsToy->setWeight($formx->use_field('weight')->get_posted());
	            $cmsToy->setCreated_at(date('Y-m-d H:i:s'));
	            $cmsToy->setCms_toy_brand_id($formx->use_field('toy_brand')->get_posted());
	            $cmsToy->setCms_toy_category_id($formx->use_field('toy_category')->get_posted());
	            $cmsToy->setCms_toy_city_id($formx->use_field('toy_city')->get_posted());
	            $cmsToy->setCms_toy_age_id($formx->use_field('toy_age')->get_posted());
	            $cmsToy->setCms_client_id($user['id']);
	            $cmsToy->setDeleted(0);
	            $cmsToy->setApproved(1);
	             
	            $ageInterest = array();
	            foreach ($formx->use_field('toy_age_interest')->get_posted() as $i) {
	                $ageInterest[$i] = $toyAges[$i];
	            }
	             
	            $cmsToy->setAge_interest(json_encode($ageInterest));
	             
	            $categoryInterest = array();
	            foreach ($formx->use_field('toy_category_interest')->get_posted() as $i) {
	                $categoryInterest[$i] = $toyCategories[$i];
	            }
	             
	            $cmsToy->setCategory_interest(json_encode($categoryInterest));
	             
	            $brandInterest = array();
	            foreach ($formx->use_field('toy_brand_interest')->get_posted() as $i) {
	                $brandInterest[$i] = $toyBrands[$i];
	            }
	             
	            $cmsToy->setBrand_interest(json_encode($brandInterest));
	            
	            $cmsToy->save();
	             
	            foreach ($cmsToyImages as $cmsToyImage) {
    	            $cmsToyImage->setCms_toy_id($cmsToy->getId());
    	            $cmsToyImage->save();
	            }
	             
	            _mail($user['email'], 'Seu produto foi recebido', 'produto_recebido', array(
	               'name' => $user['name'],
	               'toy' => $cmsToy->getName(),
	            ));
	            
	            $formx->reset();
	            $formx->set_is_sucess();
	        }
	    }
	     
	    return $formx;
	}
*/	


	public function getNewToyForm($toyAges = array(), $toyCategories = array(), $toyStates = array())
	{
	    $CI =& get_instance();
	    $CI->load->formx();
	     
	    $formx = new Formx(formx::METHOD_POST, '', '', '', 'new_toy');
	     
	    $formx->add_text('name')->set_label('T&iacute;tulo do brinquedo')->set_validates(array('required'));
	    $formx->add_text('description')->set_label('Descri&ccedil;&atilde;o do brinquedo')->set_validates(array('required'));
	    $formx->add_select('toy_age')->set_label('Faixa et&aacute;ria')->set_validates(array('required'))->set_value($toyAges);
	    $formx->add_select('toy_category')->set_label('Tipo de Brinquedo')->set_validates(array('required'))->set_value($toyCategories);
	    $formx->add_text('toy_state')->set_label('Estado')->set_validates(array('required'))->set_value($toyStates);
	    $formx->add_text('toy_city')->set_label('Cidade')->set_validates(array('required'));
	    
	    $formx->add_file('image_main');
	    $formx->add_file('image_extra1');
	    $formx->add_file('image_extra2');
	
	    if ($formx->is_post()) {
	        $cmsToyImages = array();
	        
	        foreach (array(
	                array('name' => CmsToyImage::NAME_MAIN, 'required' => true),
	                array('name' => CmsToyImage::NAME_EXTRA1, 'required' => false),
	                array('name' => CmsToyImage::NAME_EXTRA2, 'required' => false)) as $image) {

    	        $cmsToyImage = new CmsToyImage();

    	        $name = $image['name'];
    	        $field = sprintf('image_%s', $name);
    	        
    	        if ($formx->use_field($field)->upload_image(array('uploadPath' => './uploads/image', 'width' => 481, 'height' => 316))) {
    	            $cmsToyImage->setImage($formx->use_field($field)->get_posted());
    	            $cmsToyImage->setName($name);
    	            
    	            $cmsToyImages[] = $cmsToyImage;
    	        
    	        } elseif ($image['required']) {
    	            $formx->use_field($field)->set_error_message('Voc&ecirc; precisa informar uma imagem.');
    	        }
	        }
	
	        if ($formx->has_error() === false) {
	            $user = $this->getLoggedUser();
	             
	            $cmsToy = new CmsToy();
	            $cmsToy->setName($formx->use_field('name')->get_posted());
	            $cmsToy->setDescription($formx->use_field('description')->get_posted());
	            $cmsToy->setCreated_at(date('Y-m-d H:i:s'));
	            $cmsToy->setCms_toy_category_id($formx->use_field('toy_category')->get_posted());
	            $cmsToy->setCms_toy_city_id($formx->use_field('toy_city')->get_posted());
	            $cmsToy->setCms_toy_age_id($formx->use_field('toy_age')->get_posted());
	            $cmsToy->setCms_client_id($user['id']);
	            $cmsToy->setDeleted(0);
	            $cmsToy->setApproved(1);
	             
	            	            
	            $cmsToy->save();
	             
	            foreach ($cmsToyImages as $cmsToyImage) {
    	            $cmsToyImage->setCms_toy_id($cmsToy->getId());
    	            $cmsToyImage->save();
	            }
	             
	            _mail($user['email'], 'Seu produto foi recebido', 'produto_recebido', array(
	               'name' => $user['name'],
	               'toy' => $cmsToy->getName(),
	            ));
	            
	            $formx->reset();
	            $formx->set_is_sucess();
	        }
	    }
	     
	    return $formx;
	}

	
	public function getNewsletterForm()
	{
	    $CI =& get_instance();
	    $CI->load->formx();
	    
	    $formx = new Formx(formx::METHOD_POST, '', '', '', 'newsletter');	    
	    $formx->add_text('email')->set_validates(array('email'))->set_label('Email');
	    $formx->add_text('name')->set_validates(array('required'))->set_label('Nome');
	    
	    if ($formx->is_post()) {
	        CmsNewsletter::insert($formx->use_field('name')->get_posted(), $formx->use_field('email')->get_posted());
	        
	        $formx->reset();
	        $formx->set_is_sucess();
	    }
	    
	    return $formx;
	}
	
	public function getNewAccountForm($states)
	{
	    $CI =& get_instance();
	    $CI->load->formx();
	     
	    $formx = new Formx(formx::METHOD_POST, '', '', '', 'new_account');
	     
	    $formx->add_text('email')->set_post_value('Digite seu email *')->set_validates(array('email'))->set_label('Email');
	    $formx->add_text('email_check')->set_post_value('Confirme seu email *')->set_validates(array('email'))->set_label('Email (Confirma&ccedil;&atilde;o)');
	    $formx->add_text('password')->set_validates(array('required'))->set_label('Senha');
	    $formx->add_text('password_check')->set_validates(array('required'))->set_label('Senha (Confirma&ccedil;&atilde;o)');
	    $formx->add_text('name')->set_post_value('Nome Completo *')->set_validates(array('required'))->set_label('Nome');
	    $formx->add_text('birth_date')->set_post_value('Data de Nascimento *')->set_validates(array('required'))->set_label('Data de Nascimento');
	    $formx->add_select('gender')->set_validates(array('required'))->set_value(array('m' => 'Masculino', 'f' => 'Feminino'))->set_label('Sexo');
	    #$formx->add_text('cpf')->set_post_value('CPF *')->set_validates(array('required'))->set_label('CPF');
	    $formx->add_text('phone')->set_post_value('DDD + Telefone')->set_label('Telefone');
	    $formx->add_text('zip_code')->set_post_value('CEP *')->set_validates(array('required'))->set_label('CEP');
	    $formx->add_text('address')->set_post_value('Endere&ccedil;o *')->set_validates(array('required'))->set_label('Endere&ccdeil;o');
	    $formx->add_text('address_no')->set_post_value('N&ordm; *')->set_validates(array('required'))->set_label('N&uacute;mero');
	    $formx->add_text('complement')->set_post_value('Complemento *')->set_label('Complemento');
	    $formx->add_text('neighborhood')->set_post_value('Bairro *')->set_validates(array('required'))->set_label('Bairro');
	    $formx->add_text('state')->set_post_value('Estado *')->set_validates(array('required'))->set_label('Estado');
	    $formx->add_text('city')->set_post_value('Cidade *')->set_validates(array('required'))->set_label('Cidade onde se encontra o Brinquedo');
	    $formx->add_checkbox('accept')->set_value(array('1' => 'Sim'))->set_validates(array('required'))->set_label('Aceite');
	    $formx->add_checkbox('newsletter')->set_value(array('1' => 'Sim'));
	    
	    if ($formx->is_post()) {
            $email = trim($formx->use_field('email')->get_posted());
            $emailCheck = $formx->use_field('email_check')->get_posted();
            
            if ($email !== $emailCheck) {
                $formx->use_field('email_check')->set_error_message('O e-mail de confirma&ccedil;&atilde;o n&atilde;o é igual.');
            } 
            
            $criteria = new Criteria();
            $criteria->setWhere(array('email' => $email));
             
            if (CmsClient::countAll($criteria) > 0) {
                $formx->use_field('email')->set_error_message('E-mail duplicado.');
            }
            
            $password = trim($formx->use_field('password')->get_posted());
            $passwordCheck = $formx->use_field('password_check')->get_posted();
            
            if (strlen($password) < 6) {
                $formx->use_field('password')->set_error_message('Sua senha deve conter no m&iacute;nimo 6 caracteres');
            }
            
            if ($password !== $passwordCheck) {
                $formx->use_field('password_check')->set_error_message('A senha de confirma&ccedil;&atilde;o n&atilde;o é igual.');
            }

            $name = trim($formx->use_field('name')->get_posted());
            
            if (strlen($name) <= 5) {
                $formx->use_field('name')->set_error_message('Nome inv&aacute;lido.');
            }
            
            $birthDate = trim($formx->use_field('birth_date')->get_posted());
            
            try {
                $birthDate = date_create_from_format('d/m/Y', $birthDate);
                $birthDate = $birthDate instanceof DateTime ? $birthDate->format('Y-m-d') : '';
                
            } catch (Exception $e) {
                
            }
            
            $isValid = date_parse($birthDate);
            
            if ($isValid['error_count'] > 0) {
                $formx->use_field('birth_date')->set_error_message('A Data de Nascimento informada &eacute; inv&aacute;lida. Formato aceito dd/mm/YYYY.');   
            }
            
            $gender = trim($formx->use_field('gender')->get_posted());
            
            if (!in_array($gender, array('m', 'f'))) {
                $formx->use_field('gender')->set_error_message('O sexo informado n&atilde;o &eacute; v&aacute;lido.');
            }
            
//             $cpf = trim($formx->use_field('cpf')->get_posted());
//             $cpf = str_replace(array('-', '.'), '', $cpf);
            
//             if ($this->isCPFValid($cpf) === false) {
//                 $formx->use_field('cpf')->set_error_message('CPF inv&aacute;lido.');
//             }
            
//             $criteria = new Criteria();
//             $criteria->setWhere(array('cpf' => $cpf));
             
//             if (CmsClient::countAll($criteria) > 0) {
//                 $formx->use_field('cpf')->set_error_message('CPF duplicado.');
//             }
            
            $phone = trim($formx->use_field('phone')->get_posted());

            $zipCode = trim($formx->use_field('zip_code')->get_posted());
            if ($zipCode === '') {
                $formx->use_field('zip_code')->set_error_message('CEP inv&aacute;lido.');
            }
            
            $address = trim($formx->use_field('address')->get_posted());
	        if ($address === '') {
                $formx->use_field('address')->set_error_message('Endere&ccedil;o inv&aacute;lido.');
            } 
            
	        $addressNo = trim($formx->use_field('address_no')->get_posted());
	        if ($addressNo === '') {
                $formx->use_field('address_no')->set_error_message('N&ordm; inv&aacute;lido.');
            } 
            
            $complement = trim($formx->use_field('complement')->get_posted());
            $state = trim($formx->use_field('state')->get_posted());
            
            if (!array_key_exists($state, $states)) {
                $formx->use_field('state')->set_error_message('Estado inv&aacute;lido.');
            }
            
            $city = trim($formx->use_field('city')->get_posted());
            
            $accept = trim(array_shift($formx->use_field('accept')->get_posted()));
            
            if ($accept === '') {
                $formx->use_field('accept')->set_error_message('Voc&ecirc; precisa aceitar os termos de uso.');
            }
            
            $neighborhood = trim($formx->use_field('neighborhood')->get_posted());
            
            if ($neighborhood === '') {
                $formx->use_field('neighborhood')->set_error_message('Bairro inv&aacute;lido.');
            }
            
            $newsletter = trim(array_shift($formx->use_field('newsletter')->get_posted())) == 1;
            
	        if (!$formx->has_error()) {
	            $client = new CmsClient();
	            $client->setName($name);
	            $client->setAvatar('avatar.jpg');
	            $client->setBirth_date($birthDate);
	            $client->setGender($gender);
	            #$client->setCpf($cpf);
	            $client->setPhone($phone);
	            $client->setZip_code($zipCode);
	            $client->setAddress($address);
	            $client->setAddress_no($addressNo);
	            $client->setComplement($complement);
	            $client->setNeighborhood($neighborhood);
	            $client->setCity($city);
	            $client->setState($state);
	            $client->setEmail($email);
	            $client->setSalt(uniqid());
	            $client->setPassword(md5($password . $client->getSalt()));
	            $client->setNewsletter($newsletter ? 1 : 0);
	            
	            if ($newsletter) {
	                CmsNewsletter::insert($name, $email);
	                
	            } else {
	                CmsNewsletter::removeEmail($email);
	            }
	            
	            $client->save();
	            
	            _mail($client->getEmail(), 'Bem vindo', 'bem_vindo', array(
	               'name' => $client->getName()
	            ));
	            
	            $this->login($email, $password);

	            $formx->reset();
	            $formx->set_is_sucess();
	        }
	    }
	    
	    return $formx;
	}
	
	public function getUpdateAccountForm($states)
	{
	    $CI =& get_instance();
	    $CI->load->formx();
	
	    $formx = new Formx(formx::METHOD_POST, '', '', '', 'new_account');
	
	    $user = $this->getLoggedUser();
	    
	    $user['birth_date'] = date_create_from_format('Y-m-d', $user['birth_date'])->format('d/m/Y');

	    $formx->add_text('name')->set_post_value($user['name'])->set_value($user['name'])->set_validates(array('required'))->set_label('Nome');
	    $formx->add_text('birth_date')->set_post_value($user['birth_date'])->set_value($user['birth_date'])->set_validates(array('required'))->set_label('Data de Nascimento');
	    $formx->add_select('gender')->set_validates(array('required'))->set_value(array('m' => 'Masculino', 'f' => 'Feminino'))->set_selected($user['gender'])->set_post_value($user['gender'])->set_label('Sexo');
	    #$formx->add_text('cpf')->set_post_value($user['cpf'])->set_value($user['cpf'])->set_validates(array('required'))->set_label('CPF');
	    $formx->add_text('phone')->set_post_value($user['phone'])->set_value($user['phone'])->set_label('Telefone');
	    $formx->add_text('zip_code')->set_post_value($user['zip_code'])->set_value($user['zip_code'])->set_validates(array('required'))->set_label('CEP');
	    $formx->add_text('address')->set_post_value($user['address'])->set_value($user['address'])->set_validates(array('required'))->set_label('Endereço');
	    $formx->add_text('address_no')->set_post_value($user['address_no'])->set_value($user['address_no'])->set_validates(array('required'))->set_label('Número');
	    $formx->add_text('complement')->set_post_value($user['complement'])->set_value($user['complement'])->set_label('Complemento');
	    $formx->add_text('neighborhood')->set_post_value($user['neighborhood'])->set_value($user['neighborhood'])->set_validates(array('required'))->set_label('Bairro');
	    $formx->add_text('state')->set_post_value($user['state'])->set_value($user['state'])->set_validates(array('required'))->set_label('Estado');
	    $formx->add_text('city')->set_post_value($user['city'])->set_value($user['city'])->set_validates(array('required'))->set_label('Cidade onde se encontra o Brinquedo');
	    $formx->add_file('avatar');
	     
	    if ($formx->is_post()) {
	        $name = trim($formx->use_field('name')->get_posted());
	
	        if (strlen($name) <= 5) {
	            $formx->use_field('name')->set_error_message('Nome inv&aacute;lido.');
	        }
	
	        $isValid = false;
	        
	        try {
	            $birthDateForm = trim($formx->use_field('birth_date')->get_posted());
	            
	            $birthDate = date_create_from_format('d/m/Y', $birthDateForm);
	            $birthDate = $birthDate instanceof DateTime ? $birthDate : null;
	            
	            if ($birthDate instanceof DateTime) {
	                if (($isValid = ($birthDate->format('d/m/Y') === $birthDateForm)) === true) {
	                    $isValid = date_parse($birthDate->format('Y-m-d'));
	                    $isValid = $isValid['error_count'] == 0;
	                }
	            }
	        
	        } catch (Exception $e) {
	        
	        }
	        
	        if ($isValid == false) {
	            $formx->use_field('birth_date')->set_error_message('A Data de Nascimento informada &eacute; inv&aacute;lida. Formato aceito dd/mm/YYYY.');
	
	        } else {
	            $birthDate = $birthDate->format('Y-m-d');
	        }
	
	        $gender = trim($formx->use_field('gender')->get_posted());
	
	        if (!in_array($gender, array('m', 'f'))) {
	            $formx->use_field('gender')->set_error_message('O sexo informado n&atilde;o &eacute; v&aacute;lido.');
	        }
	
// 	        $cpf = trim($formx->use_field('cpf')->get_posted());
// 	        $cpf = str_replace(array('-', '.'), '', $cpf);
	
// 	        if ($this->isCPFValid($cpf) === false) {
// 	            $formx->use_field('cpf')->set_error_message('CPF inv&aacute;lido.');
// 	        }
	        
// 	        $criteria = new Criteria();
// 	        $criteria->setWhere(array('cpf' => $cpf, 'id !=' => $user['id']));
	        
// 	        if (CmsClient::countAll($criteria) > 0) {
// 	            $formx->use_field('cpf')->set_error_message('CPF duplicado.');
// 	        }
	
	        $phone = trim($formx->use_field('phone')->get_posted());
	
	        $zipCode = trim($formx->use_field('zip_code')->get_posted());
	        if ($zipCode === '') {
	            $formx->use_field('zip_code')->set_error_message('CEP inv&aacute;lido.');
	        }
	
	        $address = trim($formx->use_field('address')->get_posted());
	        if ($address === '') {
	            $formx->use_field('address')->set_error_message('Endere&ccedil;o inv&aacute;lido.');
	        }
	
	        $addressNo = trim($formx->use_field('address_no')->get_posted());
	        if ($addressNo === '') {
	            $formx->use_field('address_no')->set_error_message('N&ordm; inv&aacute;lido.');
	        }
	
	        $complement = trim($formx->use_field('complement')->get_posted());
	        $state = trim($formx->use_field('state')->get_posted());
	
	        if (!array_key_exists($state, $states)) {
	            $formx->use_field('state')->set_error_message('Estado inv&aacute;lido.');
	        }
	
	        $city = trim($formx->use_field('city')->get_posted());
	
	        $neighborhood = trim($formx->use_field('neighborhood')->get_posted());
	
	        if ($neighborhood === '') {
	            $formx->use_field('neighborhood')->set_error_message('Bairro inv&aacute;lido.');
	        }
	
	        if (!$formx->has_error()) {
	            $criteria = new Criteria();
	            $criteria->setWhere(array('id' => $user['id']));
	            $criteria->setHydrate();
	            
	            $client = CmsClient::findBy($criteria);
	            
	            if ($client->count() !== 1) {
	                redirect(URL_USUARIO_LOGIN);
	                exit;
	            }
	            
	            $client = $client->offsetGet(0);
	            
	            if ($formx->use_field('avatar')->upload_image(array('uploadPath' => './uploads/image', 'width' => 170, 'height' => 170, 'unique' => true))) {
	                if ($client->getAvatar() !== 'avatar.jpg') {
    	                @unlink(FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . $client->getAvatar());
	                }

	                $client->setAvatar($formx->use_field('avatar')->get_posted());
	            }
	            
	            $client->setName($name);
	            $client->setBirth_date($birthDate);
	            $client->setGender($gender);
	            #$client->setCpf($cpf);
	            $client->setPhone($phone);
	            $client->setZip_code($zipCode);
	            $client->setAddress($address);
	            $client->setAddress_no($addressNo);
	            $client->setComplement($complement);
	            $client->setNeighborhood($neighborhood);
	            $client->setCity($city);
	            $client->setState($state);
	            $client->update();
	            
	            $criteria->setHydrate(false);
	            
	            $client = CmsClient::findBy($criteria);
	            
	            if (count($client) !== 1) {
	                redirect(URL_USUARIO_LOGIN);
	                exit;
	            }
	            
	            $client = array_shift($client);
	            $client = (array) $client;
	            
	            unset($client['password']);
	            unset($client['salt']);
	            
	            $_SESSION['logged_user'] = serialize($client);
	            
	            #$formx->reset();
	            $formx->set_is_sucess();
	        }
	    }
	     
	    return $formx;
	}
	
	public function isCPFValid($cpf)
	{
	    $cpf = preg_replace('[^0-9]', '', $cpf);
	    
	    if(strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999'){
	        return false;

	    } else {
	        for ($t = 9; $t < 11; $t++) {
	            for ($d = 0, $c = 0; $c < $t; $c++) {
	                $d += $cpf{$c} * (($t + 1) - $c);
	            }
	            
	            $d = ((10 * $d) % 11) % 10;
	            
	            if ($cpf{$c} != $d) {
	                return false;
	            }
	        }
	    }
	    
        return true;
	}
	
	public function getContactForm()
	{
	    $CI =& get_instance();
	    $CI->load->formx();
	    
	    $formx = new Formx(formx::METHOD_POST, '', '', '', 'contato');
	    
	    $user['name'] = $user['email'] = $user['phone'] = $user['city'] = $user['state'] = '';
	    
	    if ($this->isLogged()) {
            $user = $this->getLoggedUser();
	    }
	    
	    $formx->add_text('name')->set_post_value($user['name'])->set_label('Nome')->set_validates(array('required'));
	    $formx->add_text('email')->set_post_value($user['email'])->set_validates(array('email'))->set_label('Email');
	    $formx->add_text('phone')->set_post_value($user['phone'])->set_label('Telefone');
	    $formx->add_text('city')->set_post_value($user['city'])->set_label('Cidade');
	    $formx->add_text('state')->set_post_value($user['state'])->set_label('Estado');
	    $formx->add_text('subject')->set_label('Assunto')->set_validates(array('required'));
	    $formx->add_text('message')->set_label('Mensagem')->set_validates(array('required'));
	    
	    if ($formx->is_post()) {
	        if(!$formx->has_error()) {
	           $msg = '<p>
    				<b>Nome: </b> ' . $formx->use_field('name')->get_posted() . '<br />
    				<b>Email: </b> ' . $formx->use_field('email')->get_posted() . '<br />
			        <b>Telefone: </b> ' . $formx->use_field('phone')->get_posted() . '<br />
    				<b>Cidade: </b> ' . $formx->use_field('city')->get_posted() . '<br />
    				<b>Estado: </b> ' . $formx->use_field('state')->get_posted() . '<br />
    				<b>Assunto: </b> ' . $formx->use_field('subject')->get_posted() . '<br />
    				<b>Mensagem: </b> <br />' . $formx->use_field('message')->get_posted() . '<br />
    			</p>';
	           mail('carolguedes.atriz@gmail.com', utf8_encode(html_entity_decode('Site - Contato')), utf8_decode($msg), "MIME-Version: 1.0\nContent-type: text/html;");
	           $formx->reset();
	           $formx->set_is_sucess();
	        }
	    }
	    
	    return $formx;
	}
	
	public function doExchange($toToy)
	{
	    $exchangeType = isset($_REQUEST['exchange_type']) ? $_REQUEST['exchange_type'] : '';
	    $exchangeType = trim($exchangeType);
	    
	    $message = isset($_REQUEST['message']) ? $_REQUEST['message'] : '';
	    $message = str_replace('Digite a mensagem', '', $message);
	    $message = trim($message);
	    
	    $fromToyId = isset($_REQUEST['product']) ? $_REQUEST['product'] : '';
	    $fromToyId = trim($fromToyId);
	    
	    try {
    	    if ($exchangeType == '') {
    	        throw new Exception('O tipo da troca precisa ser informado');
    	    }
    	    
    	    if ($message == '') {
    	        throw new Exception('Voc&ecirc; precisa informar uma mensagem.');
    	    }
    	    
    	    if ($fromToyId == '') {
    	        throw new Exception('Voc&ecirc; precisa selecionar seu produto.');
    	    }
    	    
    	    $fromToy = CmsToy::getByIdForFrontend($fromToyId);
    	    
    	    if ($fromToy->num_rows() == 0) {
    	        throw new Exception('Produto n&atilde;o encontrado.');
    	    }
    	    
    	    $user = $this->getLoggedUser();
    	    
    	    $fromToy = $fromToy->row();
    	    
    	    if ($fromToy->cms_client_id !== $user['id']) {
    	        throw new Exception('O produto informado n&atilde;o pertence a voc&ecirc;.');
    	    }
    	    
    	    $exchange = new CmsExchange();
    	    $exchange->setFrom_toy($fromToy->id);
    	    $exchange->setTo_toy($toToy->id);
    	    $exchange->setExchange_type($exchangeType);
    	    $exchange->setFinalized(CmsExchange::FINALIZED_NO);
    	    $exchange->setCreated_at(date('Y-m-d H:i:s'));
    	    $exchange->setAccepted(CmsExchange::ACCEPTED_NOT_YET);
    	    $exchange->save();
    	    
    	    $exchangeMessage = new CmsExchangeMessage();
    	    $exchangeMessage->setCreated_at(date('Y-m-d H:i:s'));
    	    $exchangeMessage->setMessage($message);
    	    $exchangeMessage->setCms_exchange_id($exchange->getId());
    	    $exchangeMessage->setTo_client($toToy->cms_client_id);
    	    $exchangeMessage->setFrom_client($user['id']);

    	    $client = CmsClient::getById($toToy->cms_client_id);
    	    
    	    _mail($client->email, 'Interesse de Troca', 'interesse_troca', array(
    	       'name' => $client->name,
    	       'interested_name' => trim($user['name']),
    	       'interested_city' => trim($user['city']),
    	       'interested_state'=> trim($user['state']),
    	       'message' => $message,
    	       'url' => base_url() . URL_USUARIO_MEUS_DADOS_MENSAGENS
    	    ));
    	    
    	    $exchangeMessage->save();

	    } catch (Exception $e) {
	        throw new Exception(sprintf('<br /><h2 class="alert error">%s</h2>', $e->getMessage()));
	    }
	}
	
	public function finish($exchangeId, $userId)
	{
        if (CmsExchange::canFinishExchange($exchangeId, $userId)) {
            $star = isset($_POST['star']) ? $_POST['star'] : '';
            $star = trim($star);
            
            if ($star !== '') {
                $criteria = new Criteria();
                $criteria->setHydrate();
                $criteria->setWhere(array('id' => $exchangeId));
                
                $exchange = CmsExchange::findBy($criteria);
                
                if (!$exchange->count()) {
                    return;
                }
                
                $exchange = $exchange->offsetGet(0);
                
                $from = CmsToy::isFromUser($exchange->getFrom_toy(), $userId);
                
                if ($from) {
                    $exchange->setRating_from($star);
                
                } else {
                    $exchange->setRating_to($star);
                }
                
                if ($exchange->getRating_from() !== null && $exchange->getRating_to() !== null) {
                    $exchange->setFinalized(1);
                    $exchange->setFinalized_at(date('Y-m-d H:i:s'));

                    $fromUser = CmsExchange::getUserByExchangeId($exchange->getId(), true);
                    $toUser = CmsExchange::getUserByExchangeId($exchange->getId(), false);
                    
                    $params = array(
                        'fromUser' => $fromUser,
                        'fromToy' => CmsToy::getById($exchange->getFrom_toy(), false),
                        'toUser' => $toUser,
                        'toToy' => CmsToy::getById($exchange->getTo_toy(), false),
                    );
                    
                    _mail($fromUser->email, 'Troca Finalizada', 'troca_finalizada_de', $params);
                    _mail($toUser->email, 'Troca Finalizada', 'troca_finalizada_para', $params);

                    CmsToy::setAsExchanged($exchange->getFrom_toy());
                    CmsToy::setAsExchanged($exchange->getTo_toy());
                }
                
                $exchange->update();
            }
        }
	}
	
	public function reply($userId)
	{
	    $e = isset($_POST['e']) ? $_POST['e'] : '';
	    $e = trim($e);
	    
	    $m = isset($_POST['m']) ? $_POST['m'] : '';
	    $m = trim($m);
	    
	    if ($e == '') {
	        return;
	    }
	    
	    $criteria = new Criteria();
	    $criteria->setHydrate();
	    
	    $criteria->setWhere(array('id' => $e));
	    $exchange = CmsExchange::findBy($criteria);
	    
	    if ($exchange->count() == 0) {
	        return;
	    }

	    $exchange = $exchange->offsetGet(0);
	    
	    $from = CmsToy::isFromUser($exchange->getFrom_toy(), $userId);
	    $to = CmsToy::isFromUser($exchange->getTo_toy(), $userId);

	    if ($from == false && $to == false) {
	        return;
	    }
	    
	    if ($m == '') {
	        return;
	    }

        CmsExchangeMessage::createMessage($userId, $e, $m);
	}
}
 