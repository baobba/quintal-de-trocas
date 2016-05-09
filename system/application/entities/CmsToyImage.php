<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'AbstractEntity.php';

class CmsToyImage extends AbstractEntity {

	const TABLE_NAME = 'cms_toy_image';

	const NAME_MAIN = 'main';
	const NAME_EXTRA1 = 'extra1';
	const NAME_EXTRA2 = 'extra2';
	
	protected $id;
	protected $cms_toy_id;
	protected $name;
	protected $image;
	
	public static function getMainImageByToyId($toyId)
	{
	    $criteria = new Criteria();
	    $criteria->setWhere(array('cms_toy_id' => $toyId, 'name' => self::NAME_MAIN));
	    $criteria->setLimit(1);
	    $criteria->setHydrate();
	    
	    $image = self::findBy($criteria);
	    
	    return $image->count() ? $image->offsetGet(0) : null;
	}
	
	public static function getImageByToyId($toyId, $name)
	{
	    $criteria = new Criteria();
	    $criteria->setWhere(array('cms_toy_id' => $toyId, 'name' => $name));
	    $criteria->setLimit(1);
	    $criteria->setHydrate();
	     
	    $image = self::findBy($criteria);

	    return $image->count() ? $image->offsetGet(0) : null;
	    
	}
	
	public static function getByIdAndToyId($id, $toyId)
	{
	    $criteria = new Criteria();
	    $criteria->setWhere(array('cms_toy_id' => $toyId, 'id' => $id));
	    $criteria->setLimit(1);
	    $criteria->setHydrate();
	    
	    $image = self::findBy($criteria);
	    
	    return $image->count() ? $image->offsetGet(0) : null;
	}
	
	public static function getImages($toyId)
	{
	    $extra1 = self::getImageByToyId($toyId, self::NAME_EXTRA1);
	    $extra2 = self::getImageByToyId($toyId, self::NAME_EXTRA2);
	    
	    $extra1 = $extra1 ? base_url() . URL_UPLOAD_IMAGE . $extra1->getImage() : null;
	    $extra2 = $extra2 ? base_url() . URL_UPLOAD_IMAGE . $extra2->getImage() : null;
	    
	    $result = array();
	    
	    if ($extra1) {
	        $result[] = $extra1;
	    }
	    
	    if ($extra2) {
	        $result[] = $extra2;
	    }
	    
	    return $result;
	}
}