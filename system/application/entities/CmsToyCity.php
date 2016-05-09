<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'AbstractEntity.php';

class CmsToyCity extends AbstractEntity {

	const TABLE_NAME = 'cms_toy_city';

	protected $id;
	protected $name;
	protected $cms_toy_state_id;
	
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
	
	public static function getComboByStateId($stateId)
	{
	    $combo = array();
	
	    $criteria = new Criteria();
	    $criteria->setOrder_by('name');
	    $criteria->setWhere(array('cms_toy_state_id' => $stateId));
	
	    foreach (self::findBy($criteria) as $result) {
	        $combo[$result->id] = $result->name;
	    }
	
	    return $combo;
	}
	
	public static function getComboWithToys()
	{
	    $combo = array();
	
	    foreach (self::getDb()->query('
            SELECT c.* 
	          FROM cms_toy_city c
	     LEFT JOIN cms_toy t ON t.cms_toy_city_id = c.id
	         WHERE t.deleted = 0 AND t.approved = 1
	      ORDER BY c.name')->result() as $result) {
	        $combo[$result->id] = $result->name;
	    }
	
	    return $combo;
	}
	
	public static function getStateIdById($cityId)
	{
        return self::getDb()->query(sprintf('
            SELECT t.id
	          FROM cms_toy_city c
	     LEFT JOIN cms_toy_state t ON c.cms_toy_state_id = t.id
	         WHERE c.id = %d', $cityId))->row()->id;
	}
	
	public static function findWithStateByName($name)
	{
	    $combo = array();
	    
	    foreach (self::getDb()->query(sprintf('
            SELECT c.id, CONCAT(CONCAT(c.name, "/"), t.acronym) name
	          FROM cms_toy_city c
	     LEFT JOIN cms_toy_state t ON c.cms_toy_state_id = t.id
	         WHERE c.name RLIKE "%s"
	      ORDER BY c.name', $name))->result() as $result) {
    	      $combo[$result->id] = $result->name;
	    }
	    
	    return $combo;
	}
	
	public static function getNameById($id)
	{
	    $row = self::getDb()->query(sprintf('SELECT name FROM cms_toy_city WHERE id = %d', $id))->row();
	    
	    return $row ? $row->name : '';
	}
}
