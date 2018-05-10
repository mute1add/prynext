<?php

/**
 * Abstract class that any "entity" can extend.
 * 
 * An entity is a plain old php object (POPO) that contains data about
 * business class used through your plugin.
 *
 * @author Madhouse Design Co.
 * @package Madhouse
 * @since 1.10
 */
abstract class Madhouse_Entity
{
    /**
     * The unique-identifier (UID) of this entity.
     * @var int
     * @since 1.10
     */
    protected $id;

    /**
     * Default constructor for this entity.
     * Nothing special about this one, just init the UID to 0.
     * @since 1.10
     */    
    function __construct()
    {
        $this->id = 0;
    }
    
    /**
     * Returns the UID of this object.
     * @return an int.
     */
    public function getId()
    {
        return (int) $this->id;
    }
    
    /**
     * Serialize this object as a regular PHP array.
     * This is a default implementation that you might be wise to override.
     * @return an array
     * @since 1.10
     */
    public function toArray()
    {
        $var = get_object_vars($this);
        foreach($var as &$value){
           if(is_object($value) && method_exists($value, 'toArray')) {
              $value = $value->toArray();
           }
        }
        return $var;
    }
    
    /**
     * Utility to make this object serializable a JSON.
     * This is an example of how $this->toArray can be used.
     * @return a JSON-string.
     * @since 1.10
     */
    public function toJSON()
    {
        return json_encode($this->toArray());
    }
}

 ?>