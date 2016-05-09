<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'AbstractEntity.php';

class CmsExchangePoint extends AbstractEntity {

	const TABLE_NAME = 'cms_exchange_point';

	protected $id;
	protected $name;
	protected $address;
	protected $active;
	protected $ordering;
	protected $address_no;
	protected $zip_code;
	protected $complement;
	protected $state;
	protected $city;
	protected $neighborhood;
	protected $phone;
	protected $image;
	protected $offer;
	protected $description;
	
	public static function getDistinctStatesCombo()
	{
	    $combo = array();
	    $states = self::getDb()->query('SELECT distinct(state) FROM cms_exchange_point ORDER BY state ASC');
	    
	    foreach ($states->result() as $state) {
	        $combo[$state->state] = $state->state;
	    }
	    
	    return $combo;
	}
	
	public static function getDistinctCitiesCombo($state)
	{
	    $where = $state ? sprintf('WHERE state = "%s"', $state) : '';
	    
	    $combo = array();
	    $states = self::getDb()->query(sprintf('SELECT distinct(city) FROM cms_exchange_point %s ORDER BY city ASC', $where));
	     
	    foreach ($states->result() as $state) {
	        $combo[$state->city] = $state->city;
	    }
	     
	    return $combo;
	}
	
	public static function getExchangePoints($state, $city)
	{
	    $db = self::getDb();
	    $db->from('cms_exchange_point ep');
	    
	    $where = array();
	    if ($city) {
	        $db->or_like('ep.city', $city);
	    }
	     
	    if ($state) {
	        $db->or_like('ep.state', $state);
	    }

	    $where['ep.active'] = 1;
	    
	    $db->where($where);
	    
	    $db->order_by('ep.ordering');

	    return $db->get();
	}
}