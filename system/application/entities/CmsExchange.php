<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'AbstractEntity.php';

class CmsExchange extends AbstractEntity {

	const TABLE_NAME = 'cms_exchange';

	const TYPE_PONTO_TROCA = 0;
	const TYPE_PONTO_CORREIOS = 1;
	
	const ACCEPTED_NOT_YET = 0;
	const ACCEPTED_YES = 1;
	const ACCEPTED_NO = 2;
	
	const FINALIZED_YES = 1;
	const FINALIZED_NO = 0;

	protected $id;
	protected $from_toy;
	protected $to_toy;
	protected $created_at;
	protected $exchange_type;
	protected $finalized;
	protected $rating_from;
	protected $rating_to;
	protected $finalized_at;
	protected $accepted;
	
	public static function getExchangesByUser($userId)
	{
	    $sql = "
        SELECT   e.id, e.accepted, e.finalized_at, e.finalized, e.exchange_type type,
	             e.rating_from from_rating, e.rating_to to_rating,
	             CASE WHEN e.from_toy = from_toy.id AND from_toy.cms_client_id = %d THEN 1 ELSE 0 END my_exchange,
	            
	             from_client.id from_client_id, from_client.name from_client_name, from_client.address from_client_address, from_client.address_no from_client_address_no,
	             from_client.city from_client_city, from_client.state from_client_state,
	             from_client.complement from_client_complement, from_client.zip_code from_client_zip_code,
	             from_client.neighborhood from_client_neighborhood,

    	         to_client.id to_client_id, to_client.name to_client_name, to_client.address to_client_address, to_client.address_no to_client_address_no,
	             to_client.city to_client_city, to_client.state to_client_state,
	             to_client.complement to_client_complement, to_client.zip_code to_client_zip_code,
	             to_client.neighborhood to_client_neighborhood,
	            
	             from_toy.id from_toy_id, from_toy.name from_toy_name, from_toy_image.image from_toy_image,
	             to_toy.id to_toy_id, to_toy.name to_toy_name, to_toy_image.image to_toy_image
	             
	      FROM   cms_exchange e
	      JOIN   cms_toy from_toy ON from_toy.id = e.from_toy
	      JOIN   cms_toy_image from_toy_image ON from_toy_image.cms_toy_id = from_toy.id AND from_toy_image.name = '%s'
	      JOIN   cms_client from_client ON from_client.id = from_toy.cms_client_id
	                    
	      JOIN   cms_toy to_toy ON to_toy.id = e.to_toy
	      JOIN   cms_toy_image to_toy_image ON to_toy_image.cms_toy_id = to_toy.id AND to_toy_image.name = '%s'
          JOIN   cms_client to_client ON to_client.id = to_toy.cms_client_id

   	     WHERE   (from_toy.cms_client_id = %d OR to_toy.cms_client_id = %d)
	       AND   from_toy.approved = 1
	       AND   to_toy.approved = 1
      GROUP BY   e.id
	  ORDER BY   e.id DESC"; 	    
	      
	    return self::getDb()->query(sprintf($sql, $userId, CmsToyImage::NAME_MAIN,  CmsToyImage::NAME_MAIN, $userId, $userId));
	}
	
	public static function canAcceptExchange($exchangeId, $userId)
	{
	    $sql = "
        SELECT   CASE WHEN e.from_toy = from_toy.id AND from_toy.cms_client_id = %d THEN 1 ELSE 0 END my_exchange
   	      FROM   cms_exchange e
	      JOIN   cms_toy from_toy ON from_toy.id = e.from_toy
          JOIN   cms_toy to_toy ON to_toy.id = e.to_toy
         WHERE   (from_toy.cms_client_id = %d OR to_toy.cms_client_id = %d)
           AND   from_toy.approved = 1 
	       AND   to_toy.approved = 1
	       AND   e.finalized = 0
	       AND   e.accepted = %d
	       AND   e.id = %d
      GROUP BY   e.id";

	    $result = self::getDb()->query(sprintf($sql, $userId, $userId, $userId, self::ACCEPTED_NOT_YET, $exchangeId))->result();
	    
	    return is_array($result) ? $result[0]->my_exchange == 0 : false;
	}
	
	public static function canFinishExchange($exchangeId, $userId)
	{
	    $sql = "
        SELECT   e.*,
	             CASE WHEN e.from_toy = from_toy.id AND from_toy.cms_client_id = %d THEN 1 ELSE 0 END my_exchange
   	      FROM   cms_exchange e
	      JOIN   cms_toy from_toy ON from_toy.id = e.from_toy
          JOIN   cms_toy to_toy ON to_toy.id = e.to_toy
         WHERE   (from_toy.cms_client_id = %d OR to_toy.cms_client_id = %d)
           AND   from_toy.approved = 1
	       AND   to_toy.approved = 1
	       AND   e.finalized = 0
	       AND   e.accepted = %d
	       AND   e.id = %d
      GROUP BY   e.id";

	    $result = self::getDb()->query(sprintf($sql, $userId, $userId, $userId, self::ACCEPTED_YES, $exchangeId))->row();
	     
	    if ($result instanceof stdClass) {
	        if ($result->my_exchange == 0 && $result->rating_to === null) {
    	       return true;
	        }
	        
	        if ($result->my_exchange == 1 && $result->rating_from === null) {
	            return true;
	        }
	    }
	    
	    return false;
	}
	
	public static function acceptExchange($exchangeId)
	{
	   self::getDb()->query(sprintf("UPDATE cms_exchange SET accepted = %d WHERE id = %d", self::ACCEPTED_YES, $exchangeId));    
	}
	
	public static function declineExchange($exchangeId)
	{
	    self::getDb()->query(sprintf("UPDATE cms_exchange SET accepted = %d WHERE id = %d", self::ACCEPTED_NO, $exchangeId));
	}
	
	public static function getUserByExchangeId($exchangeId, $from = true)
	{
	    $join = $from ? 'e.from_toy' : 'e.to_toy';
	    
	    $sql = "SELECT  c.*
	              FROM  cms_exchange e
	         LEFT JOIN  cms_toy t ON $join = t.id
	         LEFT JOIN  cms_client c ON t.cms_client_id = c.id
	             WHERE  e.id = %d";

	    return self::getDb()->query(sprintf($sql, $exchangeId))->row();
	}
}