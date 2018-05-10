<?php

abstract class Madhouse_EntitiesManager
{
	private $_entities;
	private $_notfounds;

	function __construct()
    {
        $this->_entities = array();
        $this->_notfounds = array();
    }

    /**
     * Find an entity by its id.
     * 	Main method : retrieve from the database or returns the existing entity.
     * @param  int $id id of the entity to find.
     * @return Any the entity (either cached or from the database).
     */
	public function find($id)
	{
		if(in_array($id, $this->_notfounds)) {
			return $this->notFound();
		}

		// Find the User $id in this manager.
		$fu = Madhouse_Utils_Collections::findById($this->_entities, $id);

		if($fu == null) {
			// Retrieve the user from the database.
			$u = $this->findByPrimaryKey($id);
	        if(empty($u)) {
	        	array_push($this->_notfounds, $id);
	            return $this->notFound();
	        }

			// Build the MadhouseUser object.
			$mu = $this->mutate($u);

			// Registers the new user in this manager.
			array_push($this->_entities, $mu);

			return $mu;
		}

		return $fu;
	}

	/**
	 * Abstract method to find the entity in the database.
	 * @param  int $id primary key value of the entity.
	 * @return array the row from the database.
	 */
	abstract protected function findByPrimaryKey($id);

	/**
	 * Modify (if needed) the entity before being stored in the Entity manager.
	 * @param  Any $e 	the row from the database.
	 * @return Any 	   	the entity modified as you see fit.
	 */
    protected function mutate($e) {
    	return $e;
    }

    /**
     * Returns something if not found in the database.
     * @return Any 		the "not found" entity as you see fit.
     */
    abstract protected function notFound();
}

?>