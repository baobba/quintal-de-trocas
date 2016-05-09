<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'AbstractEntity.php';

class CmsNewsAuthor extends AbstractEntity {

	const TABLE_NAME = 'cms_news_author';

	protected $id;
	protected $name;
	
	public static function getCombo()
	{
	    $combo = array();
	    $criteria = new Criteria();
	    $criteria->setOrder_by('name');
	    
	    foreach (self::findBy($criteria) as $result) {
	        $combo[$result->id] = $result->name;
	    }
	    
	    return $combo;
	}
}