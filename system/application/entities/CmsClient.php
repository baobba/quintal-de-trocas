<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'AbstractEntity.php';

class CmsClient extends AbstractEntity {

	const TABLE_NAME = 'cms_client';

	protected $id;
	protected $name;
	protected $avatar;
	protected $birth_date;
	protected $gender;
	protected $cpf;
	protected $phone;
	protected $zip_code;
	protected $address;
	protected $address_no;
	protected $complement;
	protected $city;
	protected $neighborhood;
	protected $state;
	protected $email;
	protected $password;
	protected $salt;
	protected $newsletter;
	protected $recovery_code;

	public function getBirth_date($asDate = false, $as = 'd-m-Y') {

		return $this->formatDate('birth_date', $asDate, $as);
	}
	
	public static function getNameById($id)
	{
	    return self::getDb()->query(sprintf('SELECT name FROM cms_client WHERE id = %d', $id))->row()->name;
	}
	
	public static function getById($id)
	{
	    return self::getDb()->query(sprintf('SELECT * FROM cms_client WHERE id = %d', $id))->row();
	}
	
	public static function getReputationByUserId($userId)
	{
	    $sql = "
            SELECT  sum(%s * 20) / count(*) reputation
              FROM  cms_exchange e 
              JOIN  cms_toy toy ON toy.id = %s
             WHERE  toy.cms_client_id = %d
               AND  toy.approved = 1
               AND  e.finalized = 1
               AND  e.accepted = 1
        "; 
	    
	    $from = (array) self::getDb()->query(sprintf($sql, 'e.rating_from', 'e.from_toy', $userId))->row();
	    $from = array_shift($from);
	    
	    $to = (array) self::getDb()->query(sprintf($sql, 'e.rating_to', 'e.to_toy', $userId))->row();
	    $to = array_shift($to);
	    
	    if ($from == null && $to == null) {
	        return 'N&atilde;o avaliado';
	    }
	    
	    return sprintf("%01.2f", (float) $from + (float) $to) . '%';
	}
}