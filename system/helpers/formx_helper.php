<?php 

if (!defined('BASEPATH'))  {
    exit('No direct script access allowed');
}

class FormxHelper {
    
    public static function autocompleteCheck(AbstractEntity $entity, $id)
    {
        if (trim($id) == '') {
            return false;
        }
        
        return $entity::findBy(array('id' => $id))->count() == 1;
    }

    public static function convertFulltimeToSqlDate($date = '')
    {
        $date = explode(' ', $date);
        $hour = $date[1];
        $date = explode('/', $date[0]);
        
        return $date[2] . '-' . $date[1] . '-' . $date[0] . ' ' . $hour;
    }
}
