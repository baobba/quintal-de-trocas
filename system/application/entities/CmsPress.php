<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'AbstractEntity.php';

class CmsPress extends AbstractEntity {

	const TABLE_NAME = 'cms_press';

	protected $id;
	protected $cms_press_category_id;
	protected $name;
	protected $cover_image;
	protected $active;
	protected $url;
	protected $ordering;
	protected $publicated_at;

	public function getPublicated_at($asDate = false, $as = 'd-m-Y H:i:s') {

		return $this->formatDate('publicated_at', $asDate, $as);
	}
	
	public static function getPress($catId = null, $year = null)
	{
	    $where = array();
		$where['p.active'] = 1;

	    if ($catId) {
	        $where['p.cms_press_category_id'] = $catId;
	    }
	     
	    if ($year) {
	        $where['DATE_FORMAT(p.publicated_at, "%Y") = '] = $year;
	    }
	     
	    $db = self::getDb();
	    $db->from('cms_press p');
	    $db->select('p.*, c.name category_name');
	    $db->join('cms_press_category c', 'c.id = p.cms_press_category_id', 'LEFT');
	    $db->where($where);
	    $db->order_by('p.ordering');
	
	    return $db->get();
	}
}