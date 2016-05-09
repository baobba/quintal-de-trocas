<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'AbstractEntity.php';

class CmsExchangeMessage extends AbstractEntity {

	const TABLE_NAME = 'cms_exchange_message';

	protected $id;
	protected $cms_exchange_id;
	protected $from_client;
	protected $to_client;
	protected $created_at;
	protected $message;
	
	public static function getMessages($exchangeId)
	{
	    $db = self::getDb();
	    
	    $result = $db->query(sprintf("
               SELECT  e.id exchange_id,
	                   e.finalized,
		               to_client.id to_client_id,
		               to_client.avatar to_client_avatar,
                       to_client.name to_client_name,
		               client_message.id client_message_id,
		               client_message.avatar client_message_avatar,
	                   client_message.name client_message_name,
	                   exchange_to_toy.id exchange_to_toy_id,
	                   exchange_to_toy.name exchange_to_toy_name,
	                   exchange_from_toy.id exchange_from_toy_id,
	                   exchange_from_toy.name exchange_from_toy_name,
                       em.message,
		               em.created_at,
	                   e.exchange_type type 
                 FROM  cms_exchange_message em
            LEFT JOIN  cms_exchange e ON e.id = em.cms_exchange_id
            LEFT JOIN  cms_toy exchange_to_toy ON exchange_to_toy.id = e.to_toy
            LEFT JOIN  cms_toy exchange_from_toy ON exchange_from_toy.id = e.from_toy
            LEFT JOIN  cms_client to_client ON to_client.id = em.to_client
            LEFT JOIN  cms_client client_message ON client_message.id = em.from_client
                WHERE  (e.id = %d)
             ORDER BY  em.id ASC", $exchangeId));
	    
	    return $result->result();
	}
	
	public static function createMessage($userId, $eId, $message)
	{
	    $criteria = new Criteria();
	    $criteria->setHydrate();
	    $criteria->setWhere(array('cms_exchange_id' => $eId));
	    
	    $e = self::findBy($criteria);
	    $e = $e->offsetGet(0);
	    
	    if ($e->getFrom_client() == $userId) {
	        $id = $e->getTo_client();
    	     
	    } else {
	        $id = $e->getFrom_client();
	    }
	    
	    $criteria = new Criteria();
	    $criteria->setWhere(array('id' => $id));
	    
	    $client = CmsClient::findBy($criteria);
	    $client = array_shift($client);
	    
        _mail($client->email, 'Nova mensagem', 'nova_mensagem', array(
           'name' => $client->name,
           'message' => $message,
        ));
        
	    $eMessage = new CmsExchangeMessage();
	    $eMessage->setCms_exchange_id($eId);
	    $eMessage->setFrom_client($userId);
	    $eMessage->setTo_client($e->getFrom_client() == $userId ? $e->getTo_client() : $e->getFrom_client());
	    $eMessage->SetCreated_at(date('Y-m-d H:i:s'));
	    $eMessage->setMessage($message);
	    $eMessage->save();
	}
}