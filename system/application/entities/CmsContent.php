<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'AbstractEntity.php';

class CmsContent extends AbstractEntity {

	const TABLE_NAME = 'cms_content';

	const ID_QUEM_SOMOS = 'quem_somos';
	const ID_HOME_BOX_1 = 'home_box_1';
	const ID_POLITICA_PRIVACIDADE = 'politica_privacidade';
	const ID_TERMOS_DE_USO = 'termos_de_uso';
	
	protected $id;
	protected $identification;
	protected $content;
	
    public static function getIdentificationsHeavyDescription()
	{
	    $id = new ArrayObject();
	    $id->offsetSet(self::ID_QUEM_SOMOS, 'Quem Somos');
	    $id->offsetSet(self::ID_HOME_BOX_1, 'Home (Box 1)');
	    $id->offsetSet(self::ID_POLITICA_PRIVACIDADE, 'Pol&iacute;tica de Privacidade');
	    $id->offsetSet(self::ID_TERMOS_DE_USO, 'Termos e Condi&ccedil;oes de Uso');
	    
	    return $id;
	}
	
	public static function getIdentificationAsString($key, $default = '')
	{
	    return ConstHelper::getAsString(self::getIdentificationsHeavyDescription(), $key, $default);
	}
	
	public static function getNotUsed()
	{
	    $found = self::findBy();
	    $types = self::getIdentificationsHeavyDescription();
	    
	    foreach ($found as $found) {
	        if ($types->offsetExists($found->identification) == false) {
	            continue;
	        }

	        $types->offsetUnset($found->identification);
	    }
	    
	    return $types;
	}
	
	public static function get($identification)
	{
	    $criteria = new Criteria();
	    $criteria->setWhere(array('identification' => $identification));
	    $criteria->setLimit(1);
	    $criteria->setHydrate();
	    
	    $found = parent::findBy($criteria);
	    $found = $found instanceof ArrayObject && $found->count() == 1 ? $found->offsetGet(0) : new CmsContent();
	    
	    return $found->getContent();
	}
}