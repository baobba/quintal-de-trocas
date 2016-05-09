<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'AbstractEntity.php';

class CmsNews extends AbstractEntity {

	const TABLE_NAME = 'cms_news';

	protected $id;
	protected $cms_news_author_id;
	protected $cms_news_category_id;
	protected $name;
	protected $friendly_url;
	protected $content;
	protected $publicated_at;
	protected $cover_image;
	protected $ordering;
	protected $active;
	protected $show_footer;

	public function getPublicated_at($asDate = false, $as = 'd-m-Y H:i:s') {

		return $this->formatDate('publicated_at', $asDate, $as);
	}
	
	/**
	 * @return CI_DB_result
	 */
	public static function getNewsFooter()
	{
        return self::getDb()->query("SELECT n.*, c.name category_name FROM cms_news n LEFT JOIN cms_news_category c ON c.id = n.cms_news_category_id WHERE n.show_footer = 1 AND n.active = 1 ORDER BY n.id ASC LIMIT 3");
	}
	
	public static function getNews($catId = null, $authorId = null, $limit, $offset)
	{
	    $where = array();
	    if ($catId) {
	        $where['n.cms_news_category_id'] = $catId;
	    }
	    
	    if ($authorId) {
	        $where['n.cms_news_author_id'] = $authorId;
	    }
	    
	    $where['n.active'] = 1;
	    
	    $db = self::getDb();
	    $db->from('cms_news n');
	    $db->select('n.*, c.name category_name, a.name author_name');
	    $db->join('cms_news_category c', 'c.id = n.cms_news_category_id', 'LEFT');
	    $db->join('cms_news_author a', 'a.id = n.cms_news_author_id', 'LEFT');
	    $db->where($where);
	    $db->limit($limit, $offset);
	    $db->order_by('n.ordering');

	    return $db->get();
	}
	
	public static function countForPagination($catId, $authorId)
	{
	    $where = array();
	    if ($catId) {
	        $where['n.cms_news_category_id'] = $catId;
	    }
	     
	    if ($authorId) {
	        $where['n.cms_news_author_id'] = $authorId;
	    }
	    
	    $where['n.active'] = 1;

	    $db = self::getDb();
	    $db->from('cms_news n');
	    $db->select('COUNT(n.id) total');
	    $db->where($where);
	    
	    $total = (array) $db->get()->row();
	    
	    return array_shift($total);
	}

	public static function getNewsBySlug($slug)
	{
	    return self::getDb()->query(sprintf("
            SELECT   n.*, c.name category_name, a.name author_name
	          FROM   cms_news n 
	     LEFT JOIN   cms_news_category c ON c.id = n.cms_news_category_id
	     LEFT JOIN   cms_news_author a ON a.id = n.cms_news_author_id 
	         WHERE   n.active = 1 AND n.friendly_url = '%s';", $slug))->result();
	}	
}