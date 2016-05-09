<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'AbstractEntity.php';

class CmsNewsletter extends AbstractEntity {

	const TABLE_NAME = 'cms_newsletter';

	protected $id;
	protected $name;
	protected $email;
	protected $created_at;
	
	public static function insert($name, $email)
	{
	    $email = trim($email);
	    $name = trim($name);
	    
        if ($email === '' | $name === '') {
            return;
        } 
        
        self::removeEmail($email);
        
        $newsletter = new CmsNewsletter();
        $newsletter->setName($name);
        $newsletter->setEmail($email);
        $newsletter->setCreated_at(date('Y-m-d H:i:s'));
        $newsletter->save();
        
        return $newsletter;
	}
        
    public static function removeEmail($email)
    {
        return self::getDb()->query(sprintf('DELETE FROM cms_newsletter WHERE email = "%s";', $email));
    }
}