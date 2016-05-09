<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'AbstractEntity.php';

class CmsPressCategory extends AbstractEntity {

	const TABLE_NAME = 'cms_press_category';

	protected $id;
	protected $name;
	
	public static function getCombo()
	{
	    $combo = array();
	
	    foreach (self::findBy() as $result) {
	        $combo[$result->id] = $result->name;
	    }
	
	    return $combo;
	}
}