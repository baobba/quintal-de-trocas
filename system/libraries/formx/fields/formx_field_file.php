<?php
	
	final class formx_field_file extends formx_field_basic {
		
		public function __construct($field_name = '', $method = '') {
        	
			parent::set_name($field_name)->set_type('file')->set_method($method);
        }
        
        public function upload_image($config = array())
        {
            $width 		= isset($config['width']) 		? $config['width'] 		: 350;
            $height 	= isset($config['height']) 		? $config['height'] 	: 250;
            $background = isset($config['background']) 	? $config['background'] : '#fff';
            $uploadPath	= isset($config['uploadPath']) 	? $config['uploadPath'] : './uploads/image';
        
            $CI =& get_instance();
            	
            $object = 'image'.time();
            	
            $CI->load->library('image', '', $object);
            	
            $CI->$object->setUploadPath($uploadPath);
            $CI->$object->setSavePath($uploadPath);
            	
            if ($CI->$object->upload(false, $this->get_name())->resize($width, $height, $background)->save()) {
                $this->set_post_value($CI->$object->getFileName());
                return true;
            }	
            
            return false;
        }
        
        public function upload_file($config = array())
        {
            $allowedTypes 	= isset($config['allowed']) 	? $config['allowed'] 	: 'doc|docx|pdf|csv|xls|xlsx|jpeg|jpg|bmp|gif|png';
            $fileName		= isset($config['fileName']) 	? $config['fileName'] 	: time();
            $uploadPath		= isset($config['uploadPath']) 	? $config['uploadPath'] : './uploads/file';
            	
            $uploadConfig['upload_path'] 	= $uploadPath;
            $uploadConfig['allowed_types'] 	= $allowedTypes;
            $uploadConfig['file_name']		= $fileName;
            	
            $CI =& get_instance();
            	
            $CI->load->library('upload');
        
            $CI->upload->initialize($uploadConfig);
            	
            if ($CI->upload->do_upload($this->get_name())) {
                $data = $CI->upload->data();
        
                $this->set_post_value($data['file_name']);
                return true;
            }
            
            return false;
        }
	}