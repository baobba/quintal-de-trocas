<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'AbstractEntity.php';

class CmsToyState extends AbstractEntity {

	const TABLE_NAME = 'cms_toy_state';

	protected $id;
	protected $name;
	protected $acronym;
	
	public static function getCombo()
	{
	    $combo = array();
	
	    $criteria = new Criteria();
	    $criteria->setOrder_by('name');

	    foreach (self::findBy($criteria) as $result) {
	        $combo[$result->id] = $result->name . '/' . $result->acronym; 
	    }
	
	    return $combo;
	}
	
	public static function getComboWithToys()
	{
	    $combo = array();
	
	    foreach (self::getDb()->query('
            SELECT s.* 
	          FROM cms_toy_state s 
	     LEFT JOIN cms_toy_city c ON c.cms_toy_state_id = s.id  
	     LEFT JOIN cms_toy t ON t.cms_toy_city_id = c.id WHERE t.deleted = 0 AND t.approved = 1
	      ORDER BY s.name')->result() as $result) {
	        $combo[$result->id] = $result->name;
	    }
	
	    return $combo;
	}
	
	public static function getNameById($id)
	{
	    $row = self::getDb()->query(sprintf('SELECT name FROM cms_toy_state WHERE id = %d', $id))->row();

	    return $row ? $row->name : '';
	}
	
	public static function getNameByCityId($id)
	{
	    $row = self::getDb()->query(sprintf('SELECT s.name FROM cms_toy_state s LEFT JOIN cms_toy_city c ON c.cms_toy_state_id = s.id WHERE c.id = %d', $id))->row();
	    
	    return $row ? $row->name : '';
	}
}