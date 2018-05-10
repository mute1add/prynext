<?php

class Madhouse_NamedEntity extends Madhouse_Entity
{
    protected $name;
    
    public function __construct()
    {
        parent::__construct();
        $this->name = null;    
    }
    
    public function getName()
    {
        return $this->name;
    }
}

?>