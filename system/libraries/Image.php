<?php 

	@set_time_limit(0);
	error_reporting(2147483647);

	/**
	 * @desc
	 */
	class Image
	{
		/**
		 * @desc
		 */
		public $uploadPath = '';
		
		/**
		 * @desc
		 */
		public $savePath = '';
		
		/**
		 * @desc
		 */
		public $originalFile = array();
		
		/**
		 * @desc
		 */
		public $has_error = false;
		
		/**
		 * @desc
		 */
		public $error_msg = array();
		
		/**
		 * @desc
		 */
		public $img = null;
		
		/**
		 * @desc
		 */
		public $imgTmp = null;
		
		/**
		 * @desc
		 */
		public $fileName = '';
		
		/** 
		 * @desc PHP4 constructor
		 */
		public function Image() {}
		
		/**
		 * @desc
		 */
		public function __construct() {}
		
		/**
		 * @desc
		 */
		public function reset()
		{
			
			#$this->setUploadPath('.' . DIRECTORY_SEPARATOR);
			#$this->setSavePath('.' . DIRECTORY_SEPARATOR);
			
			$this->setOriginalFile(array());
			$this->setResourceImage(null);
			$this->setResourceTmpImage(null);
			
			$this->set_error(false);
			$this->error_msg = array();
			
		}
		
		/**
		 * @desc
		 */
		public function upload($fromLocal = false, $fileName = '')
		{
			
			$this->reset();
			
			if ($fromLocal)
			{
				
				$ext = explode('.', $fileName);
				$ext = end($ext);
				
				if (in_array(strtolower($ext), array('jpg', 'jpeg', 'png', 'gif', 'bmp')))
				{
				
					if (is_file($fileName))
					{
						
						$tmp_name = explode(DIRECTORY_SEPARATOR, $fileName);
						$name = end($tmp_name);
						
						$ext = explode('.', $name);
						$ext = end($ext);
						
						$path = str_replace($name, '', implode(DIRECTORY_SEPARATOR, $tmp_name));
						
						$file = array();
						
						$file['local']  = true;
						$file['name'] 	= str_replace('.' . $ext, '', $name);
						$file['path'] 	= $path;
						$file['ext'] 	= $ext;
						$file['size']	= filesize($fileName);
						
						$fileName = getimagesize($fileName);
						
						$file['mime'] 	= $fileName['mime'];
						$file['width']  = $fileName[0];
						$file['height'] = $fileName[1];
						
						// 1 = gif, 2 = jpeg, 3 = png, 6 = BMP
						// http://br2.php.net/manual/en/function.exif-imagetype.php
						$file['type'] 	= $fileName[2];
						
						$this->setOriginalFile($file);
						
					}else{
						
						$this->set_error(true, '<b>"' . $fileName . '"</b> não encontrado.');
						
					}
					
				}else{
								
					$this->set_error(true, 'Extens&atilde;o de arquivo n&atilde;o permitido  <b>"' . $ext . '"</b>. Permitidos (jpg, jpeg, gif, png, bmp).');
					
				}
				
			}else{
				
				if (isset($_FILES[$fileName]))
				{
					
					if (is_file($_FILES[$fileName]['tmp_name']))
					{
						
						$ext = explode('.', $_FILES[$fileName]['name']);
						$ext = end($ext);
						
						if (in_array(strtolower($ext), array('jpg', 'jpeg', 'png', 'gif', 'bmp')))
						{
						
							$name = uniqid(time());
							
							if (move_uploaded_file($_FILES[$fileName]['tmp_name'], $this->getUploadPath() . DIRECTORY_SEPARATOR . $name . '.' . $ext))
							{
	
								$file = array();
						
								$fileName = $this->getUploadPath() . DIRECTORY_SEPARATOR . $name. '.' . $ext;
								
								$tmp_name = explode(DIRECTORY_SEPARATOR, $fileName);
								$name = end($tmp_name);
								
								$ext = explode('.', $name);
								$ext = end($ext);
								
								$path = str_replace($name, '', implode(DIRECTORY_SEPARATOR, $tmp_name));
								
								$file = array();
								
								$file['local']  = false;
								$file['name'] 	= str_replace('.' . $ext, '', $name);
								$file['path'] 	= $path;
								$file['ext'] 	= $ext;
								$file['size']	= filesize($fileName);
								
								$fileName = getimagesize($fileName);
								
								$file['mime'] 	= $fileName['mime'];
								$file['width']  = $fileName[0];
								$file['height'] = $fileName[1];
								
								// 1 = gif, 2 = jpeg, 3 = png, 6 = BMP
								// http://br2.php.net/manual/en/function.exif-imagetype.php
								$file['type'] 	= $fileName[2];
								
								$this->setOriginalFile($file);
								
							}else{
								
									$this->set_error(true, 'Erro ao efetuar mover arquivo de upload de <b>"' . $fileName . '"</b>.');
								
							}
							
						}else{
								
								$this->set_error(true, 'Extens&atilde;o de arquivo n&atilde;o permitido  <b>"' . $ext . '"</b>. Permitidos (jpg, jpeg, gif, png, bmp).');
							
						}
						
					}else{
						
						$this->set_error(true, 'Erro ao efetuar upload de <b>"' . $fileName . '"</b>.');
						
					}
					
				}else{
					
					$this->set_error(true, '<b>"' . $fileName . '"</b> não enviado via POST.');
					
				}
				
			}
			
			return $this;
			
		}
		
		/**
		 * @desc
		 */
		public function resize($width = 0, $height = 0, $fillWithColor = null, $stop = false)
		{

			$erro = false;
			
			if (!$this->get_error())
			{
				
				$originalFile = $this->getOriginalFile();
				
				$oWidth 	= $originalFile['width'];
				$oHeight 	= $originalFile['height'];
				$oName		= $originalFile['name'];
				$oMime		= $originalFile['mime'];
				$oPath		= $originalFile['path'];
				$oExt		= $originalFile['ext'];
				$oType		= $originalFile['type'];
					
				if ($width > 0 | $height > 0)
				{
					
					// new width & height
					if ($width > 0 && $height > 0)
					{
						
						if (($oWidth >= $width && $oHeight >= $height) | ($oWidth >= $width))
						{
							
							$newWidth 	= $width;
							$newHeight 	= ($oHeight *  $newWidth) / $oWidth;
							
							if ($newHeight > $height)
							{
								
								$newHeight 	= $height;
								$newWidth 	= ($oWidth *  $newHeight) / $oHeight;
								
							}
							
						}elseif ($oHeight >= $height){
							
							$newHeight 	= $height;
							$newWidth 	= ($oWidth *  $newHeight) / $oHeight;

							if ($newWidth > $width)
							{
								
								$newWidth 	= $width;
								$newHeight 	= ($oHeight *  $newWidth) / $oWidth;
								
							}
							
						}else{
							
							$newHeight 	= $height;
							$newWidth 	= $width;
							
						}
						
					// new width	
					}elseif($width > 0){
						
						$newWidth 	= $width;
						$newHeight 	= ($oHeight *  $newWidth) / $oWidth;
						
					// new height
					}else{
						
						$newHeight 	= $height;
						$newWidth 	= ($oWidth *  $newHeight) / $oHeight;
						
					}
				
					$this->setImage($oType, $oPath . $oName . '.' . $oExt);
				
					$this->setImageTmp($newWidth, $newHeight);
					
					if ((($newWidth > $oWidth) | ($newHeight > $oHeight)))
					{
						
						$fillWithColor = $fillWithColor == null ? '#fff' : $fillWithColor;
						$fillWithColor = $this->html2rgb($fillWithColor);
						
						// png
						if ($oType == '3')
						{
						
							$background = imagecolorallocatealpha(
										$this->getImageTmp(),
										$fillWithColor[0],
										$fillWithColor[1],
										$fillWithColor[2],
										127
									);
											
						}else{
							
							$background = imagecolorallocate(
								$this->getImageTmp(),
								$fillWithColor[0],
								$fillWithColor[1],
								$fillWithColor[2]
							);
							
						}
						
						imagefill(
							$this->getImageTmp(),
							0,
							0,
							$background
						);
						
						if ($oType == '3')
						{
							
							imagealphablending($this->getImageTmp(), false);
							imagesavealpha($this->getImageTmp(), true); 
							
						}
						
						imagecopyresampled(
							$this->getImageTmp(),
							$this->getImage(),
							(($oWidth - $newWidth) / 2) * (-1),
							(($oHeight - $newHeight) / 2) * (-1),
							0,
							0,
							$oWidth,
							$oHeight,
							$oWidth,
							$oHeight
						);
						
					}else{
						
						imagecopyresampled(
							$this->getImageTmp(),
							$this->getImage(),
							0,
							0,
							0,
							0,
							$newWidth,
							$newHeight,
							$oWidth,
							$oHeight
						);
						
						##
						/**/
						$this->setResourceImage($this->getImageTmp());
						
						$this->setImageTmp($width, $height);
						
						$fillWithColor = $fillWithColor == null ? '#fff' : $fillWithColor;
						$fillWithColor = $this->html2rgb($fillWithColor);
						
						// png
						if ($oType == '3')
						{
						
							$background = imagecolorallocatealpha(
											$this->getImageTmp(),
											$fillWithColor[0],
											$fillWithColor[1],
											$fillWithColor[2],
											127
										);
											
						}else{
							
							$background	   = imagecolorallocate(
								$this->getImageTmp(),
								$fillWithColor[0],
								$fillWithColor[1],
								$fillWithColor[2]
							);
							
						}
						
						imagefill(
							$this->getImageTmp(),
							0,
							0,
							$background
						);
						
						if ($oType == '3')
						{
							
							imagealphablending($this->getImageTmp(), false);
							imagesavealpha($this->getImageTmp(), true); 
							
						}
						
						imagecopyresampled(
							$this->getImageTmp(),
							$this->getImage(),
							(($width - $newWidth) / 2),
							(($height - $newHeight) / 2),
							0,
							0,
							$newWidth,
							$newHeight,
							$newWidth,
							$newHeight
						);
						/**/
						
					}
					
					$this->setResourceImage($this->getImageTmp());
					
					/*/
					header('Content-type: image/jpeg');
					imagejpeg($this->getImage(), null, 100) ;
					imagedestroy($this->getImage());
					imagedestroy($this->getImageTmp());
					/**/
										
				}else{
					
					$this->set_error(true, '<b>$width</b> ou <b>$height</b> precisam ser maiores que zero.');
					
					$erro = true;
					
				}
				
			}else{
				
				$this->set_error(true, 'Não foi poss&iacute;vel realizar o m&eacute;todo <b>resize</b>, corrija os erros anteriores.');

				$erro = true;
				
			}
			
			if ($erro && $stop)
			{
				
				return false;
				
			}elseif (!$erro && $stop){
				
				return true;
				
			}else{
				
				return $this;
				
			}
			
		}
		
		public function save($name = '', $override = true, $quality = 90, $stop = true)
		{
			if (!$this->get_error() && $this->getImage() !== null)
			{
			
				$file = $this->getOriginalFile();
				
				$oName		= $file['name'];
				$oPath		= $file['path'];
				$oExt		= $file['ext'];
				$oLocal		= $file['local'];
				
				if ($name == '' && $override | (!$oLocal && $name == ''))
				{
						
					$oName2 = uniqid(time());
						
				}else{
					
					if ($name !== '')
					{

						
						$oName2 = $name;
					
						
					}else{

						$oName2 = $oName;
						
					}
					
				}
				
				$save = $this->getSavePath() . DIRECTORY_SEPARATOR . $oName2 . '.' . $oExt;
				
				$this->setFileName($oName2 . '.' . $oExt);
				
				$erro = false;
				
				switch(strtolower($oExt))
				{
					
					case 'jpg':
					case 'jpeg':
					case 'bmp':
						if (!@imagejpeg($this->getImage(), $save, $quality))
						{
							$erro = true;
						}
						break;
					case 'png':
						if (!@imagepng($this->getImage(), $save, round(abs(($quality / 11.111111)))))
						{
							$erro = true;
						}
						break;
					case 'gif':
						if (!@imagegif($this->getImage(), $save, $quality))
						{
							$erro = true;
						}
						break;
						
				}

				if (!$oLocal)
				{
					
					@unlink($this->getSavePath() . DIRECTORY_SEPARATOR . $oName . '.' . $oExt);
					
				}

				if($stop && $erro)
				{
					
					$this->set_error(true, 'Erro ao salvar imagem.');
					return false;
					
				}elseif($stop && !$erro){
					
					return true;
					
				}else{
					
					return $this;
					
				}
				
			}else{
				
				$this->set_error(true, 'Não foi poss&iacute;vel realizar o m&eacute;todo <b>save</b>, corrija os erros anteriores.');
				
				if ($stop)
				{
					return false;
					
				}else{
					
					return $this;
					
				}
				
			}
			
		}
		
		/**
		 * @desc
		 */
		private function setResourceTmpImage($resourceImage)
		{
			
			$this->imgTmp = $resourceImage;
			
		}
		
		/**
		 * @desc
		 */
		private function setResourceImage($resourceImage)
		{
			
			$this->img = $resourceImage;
			
		}
		
		/**
		 * @desc
		 */
		private function setImage($type, $from)
		{
			
			switch ($type)
			{
				case 1: 
					$this->img = imagecreatefromgif($from);
					break;
				case 2: 
					$this->img = imagecreatefromjpeg($from); 
					break;
				case 3: 
					$this->img = imagecreatefrompng($from); 
					break;
				case 6: 
					$this->img = $this->imagecreatefrombmp($from); 
					break;
				
			}
			
		}
		
		/**
		 * @desc
		 */
		private function getImage()
		{
			
			return $this->img;
			
		}
		
		/**
		 * @desc
		 */
		private function setImageTmp ($width, $height)
		{
			
			$this->imgTmp = imagecreatetruecolor($width, $height);

			$background = imagecolorallocatealpha($this->imgTmp, 255, 255, 255, 127);
			imagefill($this->imgTmp, 0, 0, $background);
			imagealphablending($this->imgTmp, false);
			imagesavealpha($this->imgTmp, true);
			
		}
		
		/**
		 * @desc
		 */
		private function getImageTmp()
		{
			
			return $this->imgTmp;
			
		}
		
		/**
		 * @desc
		 */
		private function getUploadPath()
		{
			
			return $this->uploadPath;
			
		}
		
		/**
		 * @desc
		 */
		private function getSavePath()
		{
			
			return $this->savePath;
			
		}
		
		/**
		 * @desc
		 */
		public function setUploadPath($uploadPath)
		{
			
			$this->uploadPath = $uploadPath;
			
			return $this;
			
		}
		
		/**
		 * @desc
		 */
		public function setSavePath($savePath)
		{
			
			$this->savePath = $savePath;
			
			return $this;
			
		}
		
		/**
		 * @desc
		 */
		private function getOriginalFile()
		{
			
			return $this->originalFile;
			
		}
		
		/**
		 * @desc
		 */
		private function setOriginalFile($file)
		{
			
			$this->originalFile = $file;
			
		}
		
			/**
		 * @desc
		 */
		public function getFileName()
		{
			
			return $this->fileName;
			
		}
		
		/**
		 * @desc
		 */
		private function setFileName($fileName)
		{
			
			$this->fileName = $fileName;
			
		}
		
		/**
		 * @desc
		 */
		private function set_error_msg($error_msg)
		{
			
			$this->error_msg[] = $error_msg;
			
		}
		
		/**
		 * @desc
		 */
		public function get_error()
		{
			
			return $this->has_error;
			
		}
		
		/**
		 * @desc
		 */
		private function set_error($error = true, $error_msg = '')
		{
			
			$this->has_error = $error;
			
			if ($error_msg !== '')
			{
				
				$this->set_error_msg($error_msg);
				
			}
			
		}
		
		/**
		 * @desc
		 */
		public function get_error_msg()
		{
			
			return $this->error_msg;
			
		}
		
		/* Helpers */
		
			/**
			 * @desc
			 * @param $color
			 */
			private function html2rgb($color)
			{
				
			    if ($color[0] == '#')
			    {
			    	
			    	$color = substr($color, 1);
			    	
			    }
			    
			    if (strlen($color) == 6)
			    {
			    	
			        list($r, $g, $b) = 
			        				array(
			        					$color[0].$color[1],
										$color[2].$color[3],
										$color[4].$color[5]
									);
			                                 
			    }elseif(strlen($color) == 3){
			    	
			        list($r, $g, $b) = 
			        				array(
			        					$color[0].$color[0],
			        					$color[1].$color[1],
			        					$color[2].$color[2]
			        				);
			        
			    }else{
			    	
			    	return array(255,255,255);
			    	
			    }
			    
			    return array(hexdec($r), hexdec($g), hexdec($b));
			    
			}
			
			/**
			 * @desc
			 * @param $filename
			 */
			private function imagecreatefrombmp($filename) {
				
				if(! $f1 = fopen($filename,"rb"))
				{
					return FALSE;
				}
				
				$FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1,14));
				
				if ($FILE['file_type'] != 19778)
				{
					return FALSE;
				}
			
				$BMP = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel'.
				'/Vcompression/Vsize_bitmap/Vhoriz_resolution'.
				'/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1, 40));
				
				$BMP['colors'] = pow(2,$BMP['bits_per_pixel']);
				
				if ($BMP['size_bitmap'] == 0)
				{
					$BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];
				}
				
				$BMP['bytes_per_pixel'] = $BMP['bits_per_pixel']/8;
				$BMP['bytes_per_pixel2'] = ceil($BMP['bytes_per_pixel']);
				$BMP['decal'] = ($BMP['width']*$BMP['bytes_per_pixel']/4);
				$BMP['decal'] -= floor($BMP['width']*$BMP['bytes_per_pixel']/4);
				$BMP['decal'] = 4-(4*$BMP['decal']);
				
				if ($BMP['decal'] == 4) 
				{
					$BMP['decal'] = 0;
				}
			
				$PALETTE = array();
				
				if ($BMP['colors'] < 16777216)
				{
					$PALETTE = unpack('V'.$BMP['colors'], fread($f1,$BMP['colors']*4));
				}
		
				$IMG = fread($f1,$BMP['size_bitmap']);
				$VIDE = chr(0);
			
				$res = imagecreatetruecolor($BMP['width'],$BMP['height']);
				$P = 0;
				$Y = $BMP['height']-1;
				
				while ($Y >= 0)
				{
					
					$X=0;
					
					while ($X < $BMP['width'])
					{
						
						if ($BMP['bits_per_pixel'] == 24)
						{
						
							$COLOR = @unpack("V",substr($IMG,$P,3).$VIDE);
							
						}elseif ($BMP['bits_per_pixel'] == 16)
				 		{ 
				 			
							$COLOR = @unpack("n",substr($IMG,$P,2));
							$COLOR[1] = $PALETTE[$COLOR[1]+1];
							
						}elseif ($BMP['bits_per_pixel'] == 8)
						{ 
							
							$COLOR = @unpack("n",$VIDE.substr($IMG,$P,1));
							$COLOR[1] = $PALETTE[$COLOR[1]+1];
							
				 		}elseif ($BMP['bits_per_pixel'] == 4)
						{
							
							$COLOR = @unpack("n",$VIDE.substr($IMG,floor($P),1));
							
						if (($P*2)%2 == 0)
						{
							
							$COLOR[1] = ($COLOR[1] >> 4);
							 
						}else{
							
							$COLOR[1] = ($COLOR[1] & 0x0F);
							
						}
						
						$COLOR[1] = $PALETTE[$COLOR[1]+1];
						
				 		}elseif ($BMP['bits_per_pixel'] == 1)
						{
							
							$COLOR = @unpack("n",$VIDE.substr($IMG,floor($P),1));
							
						if     (($P*8)%8 == 0) $COLOR[1] =  $COLOR[1]        >>7;
						elseif (($P*8)%8 == 1) $COLOR[1] = ($COLOR[1] & 0x40)>>6;
						elseif (($P*8)%8 == 2) $COLOR[1] = ($COLOR[1] & 0x20)>>5;
						elseif (($P*8)%8 == 3) $COLOR[1] = ($COLOR[1] & 0x10)>>4;
						elseif (($P*8)%8 == 4) $COLOR[1] = ($COLOR[1] & 0x8)>>3;
						elseif (($P*8)%8 == 5) $COLOR[1] = ($COLOR[1] & 0x4)>>2;
						elseif (($P*8)%8 == 6) $COLOR[1] = ($COLOR[1] & 0x2)>>1;
						elseif (($P*8)%8 == 7) $COLOR[1] = ($COLOR[1] & 0x1);
							$COLOR[1] = $PALETTE[$COLOR[1]+1];
				 		}else{
		
				 			return FALSE;
				 			
				 		}
				
				 		imagesetpixel($res, $X, $Y, $COLOR[1]);
				 		$X++;
				 		$P+= $BMP['bytes_per_pixel'];
					}
				
					$Y--;
					$P+= $BMP['decal'];
				
				}
			
				fclose($f1);
			
				return $res;
			 
			}
		
	}

	/* Example */
	/*/
	$image = new Image();
	$image->setSavePath('./testes');
	$image->setUploadPath('./testes');

	if ($image->upload(false, 'arquivo')->resize(350, 400)->save())
	{
		
		echo '<img src="testes/' . $image->getFileName() . '" />';
		
	}else{
		
		pre($image->get_error_msg());
		
	}
	
	function pre($array)
	{
		
		echo '<pre>';
		print_r($array);
		echo '</pre>';
		
	}
	
	echo '
		<form method="post" enctype="multipart/form-data">

			<input type="file" name="arquivo">
			<input type="submit" value="enviar">
	
		</form>
		';
	
	/**/
	
?>

