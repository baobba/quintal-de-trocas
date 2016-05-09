<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'AbstractEntity.php';

class CmsToyBrand extends AbstractEntity {

	const TABLE_NAME = 'cms_toy_brand';

	protected $id;
	protected $name;
	protected $ordering;
	
	public static function getCombo()
	{
	    $combo = array();
	
	    $criteria = new Criteria();
	    $criteria->setOrder_by('ordering');
	
	    foreach (self::findBy($criteria) as $result) {
	        $combo[$result->id] = $result->name;
	    }
	
	    return $combo;
	}
	
	public static function getNameById($id)
	{
	    return self::getDb()->query(sprintf('SELECT name FROM cms_toy_brand WHERE id = %d', $id))->row()->name;
	}
}