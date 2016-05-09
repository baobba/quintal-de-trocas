<?php

class Criteria {

    private $where = array();
    private $where_in = array();
    private $like = array();
    private $order_by;
    private $order_by_direction;
    private $limit;
    private $offset;
    private $group_by;
    private $hydrate = false;
    
	/**
     * @return $where
     */
    public function getWhere()
    {
        return $this->where;
    }

	/**
     * @return $where_in
     */
    public function getWhere_in()
    {
        return $this->where_in;
    }
    
	/**
     * @return $like
     */
    public function getLike()
    {
        return $this->like;
    }

	/**
     * @return $order_by
     */
    public function getOrder_by()
    {
        return $this->order_by;
    }

	/**
     * @return $order_by_direction
     */
    public function getOrder_by_direction()
    {
        return $this->order_by_direction;
    }

	/**
     * @return $limit
     */
    public function getLimit()
    {
        return $this->limit;
    }

	/**
     * @return $offset
     */
    public function getOffset()
    {
        return $this->offset;
    }

	/**
     * @return $group_by
     */
    public function getGroup_by() {
        return $this->group_by;
    }

	/**
     * @return $hydrate
     */
    public function getHydrate()
    {
        return $this->hydrate;
    }

	/**
     * @param array $where
     */
    public function setWhere($where) 
    {
        $this->where[] = $where;
        
        return $this;
    }
    
	/**
     * @param array $where_in
     */
    public function setWhere_in($field, $in)
    {
        $this->where_in[] = array('field' => $field, 'in' => $in);

        return $this;
    }

	/**
     * @param array $like
     */
    public function setLike($field, $match, $side = 'both')
    {
        $this->like[] = array('field' => $field, 'match' => $match, 'side' => $side);

        return $this;
    }

	/**
     * @param string $order_by
     */
    public function setOrder_by($order_by, $order_by_direction = null)
    {
        $this->order_by = $order_by;
        if ($order_by_direction) {
            $this->order_by_direction = $order_by_direction;
        }

        return $this;
    }

	/**
     * @param string $order_by_direction
     */
    public function setOrder_by_direction($order_by_direction)
    {
        $this->order_by_direction = $order_by_direction;

        return $this;
    }

	/**
     * @param integer $limit
     */
    public function setLimit($limit)
    {
        $this->limit = (int) $limit;

        return $this;
    }

	/**
     * @param integer $offset
     */
    public function setOffset($offset)
    {
        $this->offset = (int) $offset;

        return $this;
    }

	/**
     * @param string $group_by
     */
    public function setGroup_by($group_by)
    {
        $this->group_by = $group_by;

        return $this;
    }

	/**
     * @param boolean $hydrate
     */
    public function setHydrate($hydrate = true)
    {
        $this->hydrate = (boolean) $hydrate;

        return $this;
    }   
}
