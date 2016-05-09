<?php 

class FriendlyUrlFinder {
    
    /**
     * @param string $friendly_url
     * @param AbstractEntity $entity
     * 
     * @return ArrayObject
     */
    public static function find($friendlyUrl, AbstractEntity $entity)
    {
        if ($friendlyUrl == '') {
            return new ArrayObject();
        }
        
        $criteria = new Criteria();
        $criteria->setWhere(array('friendly_url' => $friendlyUrl))->setHydrate(true);
        
        $entity = get_class($entity);
        return $entity::findBy($criteria);
    }
}