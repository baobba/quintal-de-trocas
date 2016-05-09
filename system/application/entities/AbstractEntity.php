<?php

require_once FCPATH . 'system' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'const_helper.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Criteria.php';

abstract class AbstractEntity {

    private $properties = array();
    
    /**
     * @var CI_DB_active_record
     */
    private $db;

    public function __construct()
    {
        $reflectionClass = new ReflectionClass($this);
        $propertyArray = $reflectionClass->getProperties();
        
        foreach ($propertyArray as $property) {
            $this->properties[] = $property->getName();
        }

        $this->db = self::getDb();
    }

    public function __call($property, $arguments)
    {
        # remote set/get string
        $property = lcfirst(substr($property, 3));
        
        if (!in_array($property, $this->properties)) {
            throw new InvalidArgumentException(utf8_encode(html_entity_decode('Propriedade Inv&aacute;lida [' . $property . ']')));
        }
        
        return isset($arguments[0]) ? ($this->$property = $arguments[0]) : $this->$property;
    }
    
    /**
     * @return CI_DB_active_record
     */
    public static function getDb()
    {
        $CI =& get_instance();
        return $CI->db;
    }

    public function save()
    {
        try { 
            $values  = array();
            $columns = $this->getColumns();
            
            foreach ($columns as $column) {
                $values[$column] = $this->$column;
            }
            
            $this->db->insert(static::TABLE_NAME, $values);
            
            $this->{$this->properties[0]} = $this->db->insert_id();
            
            return $this->{$this->properties[0]};
            
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update()
    {
        try {
            $values  = array();
            $columns = $this->getColumns();
            
            foreach ($columns as $column) {
                $values[$column] = $this->$column;
            }
            
            $primaryKey = $this->properties[0];

            $this->db->update(static::TABLE_NAME, $values, array($primaryKey => $this->$primaryKey));
            
            return $this->{$this->properties[0]};
            
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete()
    {
        try {
            $this->db->delete(static::TABLE_NAME, array($this->properties[0] => $this->{$this->properties[0]}));
            
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    /**
     * @return integer
     */
    public static function countAll(Criteria $criteria = null)
    {
        try {
            $db = self::parse($criteria);
            $db->select('COUNT(*) AS t');
            
            $result = $db->get(static::TABLE_NAME)->row();
    
            return (int) $result->t;
            
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param Criteria $criteria
     * 
     * @return CI_DB_active_record
     */
   	public static function parse(Criteria $criteria = null)
    {
   	    $db = self::getDb();
   	    
   	    if (!is_a($criteria, 'Criteria')) {
   	    	return $db;    
   	    }
   	    
   	    foreach ($criteria->getWhere_in() as $where_in) {
   	        $db->where_in($where_in['field'], $where_in['in']);
   	    }
   	    
   	    foreach ($criteria->getWhere() as $where) {
   	        $db->where($where);
   	    }
   	    
   	    foreach ($criteria->getLike() as $like) {
   	        $db->like($like['field'], $like['match'], $like['side']);
   	    } 
   	    
   	    if ($criteria->getLimit()) {
   	        $db->limit($criteria->getLimit());
   	    }
   	    
   	    if ($criteria->getOffset()) {
   	        $db->offset($criteria->getOffset());
   	    }
   	    
   	    if ($criteria->getOrder_by()) {
   	        $db->order_by($criteria->getOrder_by(), ($criteria->getOrder_by_direction() ? $criteria->getOrder_by_direction() : ''));
   	    }
   	    
   	    if ($criteria->getGroup_by()) {
   	        $db->group_by($criteria->getGroup_by());
   	    }
   	    
   	    return $db;
   	}
    
    /**
     * @param Criteria $criteria
     * @return ArrayObject
     */
    public static function findBy(Criteria $criteria = null)
    {
        $db = self::parse($criteria);
        
        $result = $db->get(static::TABLE_NAME)->result();

        if (is_a($criteria, 'Criteria')) {
            $result = (!$criteria->getHydrate()) ? $result : self::hydrateResult($result, get_called_class());;
        }
        
        return $result;
    }

    /**
     * @param array $result
     * @param string $className
     * @return ArrayObject
     */
    protected static function hydrateResult(array $result, $className)
    {
        $entityCollection = new ArrayObject();
        $entity = new $className();
        
        foreach ($result as $row) {
        	$clonnedEntity = clone $entity;
        	foreach ($row as $property => $value) {
        		$clonnedEntity->$property = $value;
        	}
        	
        	$entityCollection->append($clonnedEntity);
        }
        
        return $entityCollection;
    }
	
    protected function formatDate($field = '', $formatDate = false, $as = 'd-m-Y H:i:s')
    {
        if ($this->$field == '' | $this->$field == null | $this->$field == '0000-00-00' | $this->$field == '0000-00-00 00:00:00' | !$formatDate) {
            return $this->$field;
        }

        return date($as, strtotime($this->$field));
    }

    protected static function parseLimit($limit = 0, $offset = 0)
    {
        $offsetSql = '';
    
        if (is_numeric($offset) && $offset > 0) {
            $offsetSql.= $offset . ', ';
        }
    
        return (is_numeric($limit) && $limit > 0) ? ' LIMIT ' . $offsetSql . ' ' . (int) $limit : '';
    }
    
    /**
     * @param boolean $primary
     * @return array
     */
    private function getColumns($primary = false)
    {
        $properties = $this->properties;
        if (!$primary) {
            array_shift($properties);
        }
        
        return $properties;
    }
}
