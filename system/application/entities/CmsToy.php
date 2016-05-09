<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'AbstractEntity.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'CmsToyImage.php';

class CmsToy extends AbstractEntity {

	const TABLE_NAME = 'cms_toy';

	protected $id;
	protected $cms_toy_brand_id;
	protected $cms_toy_category_id;
	protected $cms_toy_city_id;
	protected $cms_toy_age_id;
	protected $cms_client_id;
	protected $name;
	protected $description;
	protected $weight;
	protected $message;
	protected $created_at;
	protected $approved;
	protected $deleted;
	protected $brand_interest;
	protected $age_interest;
	protected $category_interest;

	public function getCreated_at($asDate = false, $as = 'd-m-Y H:i:s') {

		return $this->formatDate('created_at', $asDate, $as);
	}
	
	public static function getById($toyId, $hydrate = true)
	{
	    $criteria = new Criteria();
	    $criteria->setWhere(array('id' => $toyId));
	    $criteria->setHydrate($hydrate);
	    
	    $return = self::findBy($criteria);
	    
	    if ($hydrate) {
    	    $return = $return->offsetGet(0);
    	    
	    } else {
	        $return = array_shift($return);
	    }
	    
	    return $return;
	}
	
	public static function getByIdForFrontend($toyId)
	{
	    $sql = "
            SELECT   t.*, ti.image, c.city, c.state, c.neighborhood, tci.name city, ts.name state, tc.name category, tb.name brand, ta.name age
	          FROM   cms_toy t
	     LEFT JOIN   cms_client c ON t.cms_client_id = c.id
         LEFT JOIN   cms_toy_city tci ON t.cms_toy_city_id = tci.id
         LEFT JOIN   cms_toy_state ts ON tci.cms_toy_state_id = ts.id
	     LEFT JOIN   cms_toy_category tc ON t.cms_toy_category_id = tc.id
         LEFT JOIN   cms_toy_brand tb ON t.cms_toy_brand_id = tb.id
	     LEFT JOIN   cms_toy_age ta ON t.cms_toy_age_id = ta.id
         LEFT JOIN   cms_toy_image ti ON ti.cms_toy_id = t.id AND ti.name = '%s'
	         WHERE   t.approved = 1
	           AND   t.deleted = 0
	           AND   t.id = '%d'";
	    
	    return self::getDb()->query(sprintf($sql, CmsToyImage::NAME_MAIN, $toyId));
	}
	
	public static function isFromUser($toyId, $userId)
	{
	    $sql = sprintf("
            SELECT   count(t.id)
              FROM   cms_toy t
	         WHERE   t.id = %d
	           AND   t.cms_client_id = %d
	    ", $toyId, $userId);
	    
	    $isFromUser = (array) self::getDb()->query($sql)->row();
	    
	    return array_shift($isFromUser) == 1;
	}
	
	public static function getUserToysExchanged($userId, $limit = null)
	{
	    $sql = "
            SELECT   t.id, t.name, ti.image
	          FROM   cms_toy t
	     LEFT JOIN   cms_toy_image ti ON ti.cms_toy_id = t.id AND ti.name = '%s'
	         WHERE   t.cms_client_id = '%s'
	           AND   t.approved = 1
	           AND   t.deleted = 2";
	    
	    $limit = (int) $limit;
	    if ($limit > 0) {
	        $sql.= sprintf(' LIMIT %d', $limit);
	    }
	    
	    return self::getDb()->query(sprintf($sql, CmsToyImage::NAME_MAIN, $userId));
	}
        
        public static function getUserToys($userId, $limit = null)
	{
	    $sql = "
            SELECT   t.id, t.name, ti.image
	          FROM   cms_toy t
	     LEFT JOIN   cms_toy_image ti ON ti.cms_toy_id = t.id AND ti.name = '%s'
	         WHERE   t.cms_client_id = '%s'
	           AND   t.approved = 1
	           AND   t.deleted = 0";
	    
	    $limit = (int) $limit;
	    if ($limit > 0) {
	        $sql.= sprintf(' LIMIT %d', $limit);
	    }
	    
	    return self::getDb()->query(sprintf($sql, CmsToyImage::NAME_MAIN, $userId));
	}
	
	public static function getRelated($userId, $removeToyId, $limit = null)
	{
	    $sql = "
            SELECT   t.id, t.name, ti.image
	          FROM   cms_toy t
	     LEFT JOIN   cms_toy_image ti ON ti.cms_toy_id = t.id AND ti.name = '%s'
	         WHERE   t.cms_client_id = %d
	           AND   t.approved = 1
	           AND   t.deleted = 0
	           AND   t.id != %d";
	     
	    $sql = sprintf($sql, CmsToyImage::NAME_MAIN, $userId, $removeToyId);

	    $limit = (int) $limit;
	    if ($limit > 0) {
	        $sql.= sprintf(' LIMIT %d', $limit);
	    }
	     
	    return self::getDb()->query($sql);
	}
	
    public static function getUserToyByToyId($toyId)
	{
	    $sql = "
            SELECT   t.*
	          FROM   cms_toy t
	         WHERE   t.id = '%s'
	           AND   t.approved = 1
	           AND   t.deleted = 0";

	    return self::getDb()->query(sprintf($sql, $toyId))->row();
	}
	
	public static function getUserToy($userId)
	{
	    $sql = "
            SELECT   t.*
	          FROM   cms_toy t
	         WHERE   t.cms_client_id = '%s'
	           AND   t.approved = 1
	           AND   t.deleted = 0";

	    return self::getDb()->query(sprintf($sql, $userId))->row();
	}
	
	public static function setAsDeleted($toyId)
	{
	    $sql = "UPDATE cms_toy SET deleted = 1 WHERE id = %d";
	    
	    self::getDb()->query(sprintf($sql, $toyId));
	}
        
        public static function setAsExchanged($toyId)
	{
	    $sql = "UPDATE cms_toy SET deleted = 2 WHERE id = %d";
	    
	    self::getDb()->query(sprintf($sql, $toyId));
	}
	
	public static function countForPagination($toyCitySelected = array(), $toyStateSelected = array(), $toyAgeSelected = array(), $toyCategorySelected = array(), $toyBrandSelected = array())
	{
	    $where = array();
	    $where['t.approved'] = 1;
	    $where['t.deleted'] = 0;
	    
	    $db = self::getDb();
	    $db->from('cms_toy t');
	    $db->select('COUNT(t.id) total');
	    $db->where($where);
	    
	    if (count($toyAgeSelected) && $toyAgeSelected[0] != '' && $toyAgeSelected[0] != 0) {
	        $db->where_in('t.cms_toy_age_id', $toyAgeSelected);
	    }
	    
	    if (count($toyCitySelected) && $toyCitySelected[0] != '' && $toyCitySelected[0] != 0) {
	        $db->where_in('t.cms_toy_city_id', $toyCitySelected);
	    }
	    
	    if (count($toyStateSelected) && $toyStateSelected[0] != '' && $toyStateSelected[0] != 0) {
	        $db->join('cms_toy_city c', 'c.id = t.cms_toy_city_id', 'LEFT');
	        $db->where_in('c.cms_toy_state_id', $toyStateSelected);
	    }
	    
	    if (count($toyCategorySelected) && $toyCategorySelected[0] != '' && $toyCategorySelected[0] != 0) {
	        $db->where_in('t.cms_toy_category_id', $toyCategorySelected);
	    }
	    
	    if (count($toyBrandSelected) && $toyBrandSelected[0] != '' && $toyBrandSelected[0] != 0) {
	        $db->where_in('t.cms_toy_brand_id', $toyBrandSelected);
	    }
	     
	    $total = (array) $db->get()->row();

	    return array_shift($total);
	}
	
	public static function getToys($toyCitySelected = array(), $toyStateSelected = array(), $toyAgeSelected = array(), $toyCategorySelected = array(), $toyBrandSelected = array(), $orderBy = array(), $toyName = '', $limit, $offset)
	{
	    $where = array();
	    $where['t.approved'] = 1;
	    $where['t.deleted'] = 0;
	    #$where['ti.name'] = CmsToyImage::NAME_MAIN;

	    $db = self::getDb();
	    $db->from('cms_toy t');
	    
	    $toyName = trim($toyName);

	    if ($toyName !== '') {
	        $db->like('t.name', $toyName);
	    }
	    
	    $db->join('cms_toy_image ti', 'ti.cms_toy_id = t.id AND ti.name = "main"', 'LEFT');
	    $db->select('t.*, ti.image');
	    $db->where($where);
	    $db->limit($limit, $offset);
	    $db->order_by($orderBy[0], $orderBy[1]);

	    if (count($toyAgeSelected) && $toyAgeSelected[0] != '' && $toyAgeSelected[0] != 0) {
	        $db->where_in('t.cms_toy_age_id', $toyAgeSelected);
	    }
	    
	    if (count($toyCitySelected) && $toyCitySelected[0] != '' && $toyCitySelected[0] != 0) {
	        $db->where_in('t.cms_toy_city_id', $toyCitySelected);
	    }
	    
	    if (count($toyStateSelected) && $toyStateSelected[0] != '' && $toyStateSelected[0] != 0) {
	        $db->join('cms_toy_city c', 'c.id = t.cms_toy_city_id', 'LEFT');
	        $db->where_in('c.cms_toy_state_id', $toyStateSelected);
	    }
	    
	    if (count($toyCategorySelected) && $toyCategorySelected[0] != '' && $toyCategorySelected[0] != 0) {
	        $db->where_in('t.cms_toy_category_id', $toyCategorySelected);
	    }
	    
	    if (count($toyBrandSelected) && $toyBrandSelected[0] != '' && $toyBrandSelected[0] != 0) {
	        $db->where_in('t.cms_toy_brand_id', $toyBrandSelected);
	    }

	    $result = $db->get();
	    
	    return $result;
	}
	
	public static function deleteToy($toyId)
	{
	    $criteria = new Criteria();
	    $criteria->setWhere(array('cms_toy_id' => $toyId));
	    $criteria->setHydrate(true);
	    
	    foreach (CmsToyImage::findBy($criteria) as $toyImage) {
	        @unlink(FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . $toyImage->getImage());
	        $toyImage->delete();
	    }
	    
	    self::getDb()->query(sprintf("DELETE m FROM cms_exchange_message m LEFT JOIN cms_exchange e ON e.id = m.cms_exchange_id WHERE e.from_toy = %d OR e.to_toy = %d", $toyId, $toyId));
	    self::getDb()->query(sprintf("DELETE e FROM cms_exchange e WHERE e.from_toy = %d OR e.to_toy = %d", $toyId, $toyId));
	   	    
	    self::getById($toyId)->delete();
	}
}