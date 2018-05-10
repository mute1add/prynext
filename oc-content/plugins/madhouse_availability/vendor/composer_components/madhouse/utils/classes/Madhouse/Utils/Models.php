<?php

/**
 * Common model layer utilities.
 *
 * Tired of repeating yourself ? Want something robust ?
 * These utilities should do the job when dealing with the model layer of your plugin.
 *
 * @package Madhouse
 * @subpackage Utils
 * @since 1.10
 */
class Madhouse_Utils_Models
{

    /**
     * Imports an SQL file into the Osclass database.
     *
     * Really useful when installing a plugin to create its model schema,
     * or delete its schema on uninstall. Avoid to have an install/uninstall
     * method in every plugin data-access object.
     *
     * @param $file absolute file path to the SQL file to import.
     * @returns void.
     * @throws Exception if the import fails.
     * @since 1.10
     */
    public static function import($path)
    {
        // Check if the file exists.
        if(! file_exists($path)) {
            throw new Exception(sprintf("'%s' not found!", $path));
        }

        // Try to import it. Throws Exception if failure.
        $conn = DBConnectionClass::newInstance()->getOsclassDb();
        $dao = new DBCommandClass($conn);
        if(! $dao->importSQL(file_get_contents($path))){
        	throw new Exception("Import failed with: '" . $dao->getErrorDesc() . "'");
        }
    }

    /**
     * Run a SQL query and returns the result.
     * @param $dao a working DAO instance.
     * @param $query the custom query to run.
     * @return an associative array (first result in set).
     * @throws Exception if no element is found.
     * @since 1.10
     */
    public static function get(DAO $dao, $beforeOrQuery, $after=null, $failIfNoResults=true, $defaultValue=null)
    {
        if(is_null($after)) {
            // Define a dummy lambda function that returns all results.
            $after = function($r, $dao) { return $r->result(); };
        } else {
            if(! is_callable($after)) {
                // TODO throw new Madhouse_InvalidArgumentException("$after must be callable");
            }
        }

        $res = false;
        if(is_callable($beforeOrQuery)) {
            // Resets DAO internal variables for SELECT operations.
            $dao->dao->_resetSelect();

            // Prepare the DAO to make the query.
            $beforeOrQuery($dao);

            // Make the query.
            $res = $dao->dao->get();
        } else {
            // Run the "custom" query.
            $res = $dao->dao->query($beforeOrQuery);
        }

        if($res === false) {
            // Fails if something went wrong.
            // TODO Madhouse_DAO_Exception
            throw new Madhouse_QueryFailedException($dao->dao->getErrorDesc());
        }

        if($res->numRows() === 0) {
            if($failIfNoResults) {
                // Fails if no results.
                // TODO Madhouse_DAO_ElementNotFoundException
                throw new Madhouse_NoResultsException(sprintf("Query returned no results on table '%s' / '%s'", $dao->getTableName(), $dao->dao->lastQuery()));
            }
            // Returns a default "no results" value.
            return $defaultValue;
        }

        return $after($res, $dao);
    }

    /**
     * Finds an element by its name.
     *
     * This is an attempt to make a findByName() method generic for any DAO classes.
     *
     * @param $dao instance of a working DAO class related to the type of element we're looking for.
     * @param $field column name to consider as the name.
     * @param $value value that we are looking for.
     * @returns an associative array containing all the element informations
     * @throws Exception if the element could not be found.
     * @since 1.10
     */
    public static function findByField($dao, $field, $value, $failIfNoResults=true, $defaultValue=null)
    {
        return self::get(
            $dao,
            function($dao) use ($field, $value) {
                $dao->dao->select();
                $dao->dao->from($dao->getTableName());
                $dao->dao->where($field, $value);
            },
            function($r, $dao) {
                return $r->row();
            },
            $failIfNoResults,
            $defaultValue
        );
    }

    /**
     * Extend the object data with locales informations.
     * @param  DAO $dao            DAO class.
     * @param  String $tableName   Description table name to query.
     * @param  String $fkFieldName The fk_i_* field name.
     * @param  Array $data         The data array to extend.
     * @param  String $locale      A locale code to retrieve.
     * @return Array               The locales informations as Osclass is formatting them.
     * @since  1.30
     */
    public static function extendData($dao, $tableName, $fkFieldName, $data, $locale=null)
    {

        // Get locales from the database.
        return self::get(
            $dao,
            function($dao) use ($tableName, $fkFieldName, $data, $locale) {
                $dao->dao->select("*");
                $dao->dao->from($tableName);
                $dao->dao->where($fkFieldName, $data[$dao->getPrimaryKey()]);
                if( !is_null($locale) ) {
                    $dao->dao->where('fk_c_locale_code', $locale);
                }
            },
            function($r, $dao) use ($data) {
                $results = array();
                $aDescriptions = $r->result();
                foreach($aDescriptions as $description) {
                    $results[$description['fk_c_locale_code']] = $description;
                }

                return $results;
            },
            false,
            array()
        );
    }

    public static function countByField($dao, $field, $value=null)
    {
        $dao->dao->select("COUNT(1) AS count");
        $dao->dao->from($dao->getTableName());
        if(is_null($value)) {
            $dao->dao->groupBy($field);
        } else {
            $dao->dao->where($field, $value);
        }

        // Perform the query.
        $result = $dao->dao->get();

        // Query has failed.
        if($result === false) {
        	throw new Exception($dao->dao->getErrorDesc());
        }

        if(is_null($value)) {
            return $result->result();
        } else {
            $res = $result->row();
            return $res["count"];
        }
    }

    public static function countWhere($dao, $where)
    {
        return self::get(
            $dao,
            function($dao) use ($where) {
                $dao->dao->select("COUNT(1) AS count");
                $dao->dao->from($dao->getTableName());
                if(! is_null($where)) {
                    if(! is_array($where)) {
                        $where = array($where => null);
                    }

                    foreach ($where as $key => $value) {
                        $dao->dao->where($key, $value);
                    }
                }
            },
            function($r, $dao) {
                return $r->rowObject()->count;
            }
        );
    }

    public static function countToday($dao, $field, $where=null)
    {
        return self::countBetweenDates($dao, $field, date('Y-m-d H:i:s', strtotime('today midnight')), date('Y-m-d H:i:s', strtotime('now')), $where);
    }

    public static function countYesterday($dao, $field, $where=null)
    {
        return self::countBetweenDates($dao, $field, date('Y-m-d H:i:s', strtotime('yesterday midnight')), date('Y-m-d H:i:s', strtotime('today midnight')), $where);
    }

    public static function countThisWeek($dao, $field, $where=null)
    {
        return self::countBetweenDates($dao, $field, date('Y-m-d H:i:s', strtotime('monday this week midnight')), date('Y-m-d H:i:s', strtotime('now')), $where);
    }

    public static function countLastWeek($dao, $field, $where=null)
    {
        return self::countBetweenDates($dao, $field, date('Y-m-d H:i:s', strtotime('monday last week midnight')), date('Y-m-d H:i:s', strtotime('monday this week midnight')), $where);
    }

    public static function countThisMonth($dao, $field, $where=null)
    {
        return self::countBetweenDates($dao, $field, date('Y-m-d H:i:s', strtotime('first day of this month midnight')), date('Y-m-d H:i:s', strtotime('now')), $where);
    }

    public static function countLastMonth($dao, $field, $where=null)
    {
        return self::countBetweenDates($dao, $field, date('Y-m-d H:i:s', strtotime('first day of last month midnight')), date('Y-m-d H:i:s', strtotime('last day of last month midnight')), $where);
    }

    public static function countBetweenDates($dao, $field, $from, $to, $where=null)
    {
        return self::get(
            $dao,
            function($dao) use ($field, $from, $to, $where) {
                $dao->dao->select("COUNT(1) AS count");
                $dao->dao->from($dao->getTableName());
                $dao->dao->where(sprintf("%s BETWEEN '%s' AND '%s'", $field, $from, $to));
                if(! is_null($where)) {
                    if(! is_array($where)) {
                        $where = array($where => null);
                    }

                    foreach ($where as $key => $value) {
                        $dao->dao->where($key, $value);
                    }
                }
            },
            function($r, $dao) {
                return $r->rowObject()->count;
            }
        );
    }

	/**
	 * Retrieve a Madhouse_User from its primary key.
	 * @param $userId the id of the user to retrieve.
	 * @returns a MadhouseUser object.
	 * @since 1.00
	 */
	public static function findUserByPrimaryKey($userId)
	{
		return Madhouse_UsersManager::newInstance()->find($userId);
	}

	/**
	 * Retrieve a bunch of Madhouse_User objects from their primary keys.
	 * @param $userIds array of integer (of user ids).
	 * @returns a list of MadhouseUser objects (Array<Madhouse_User>).
	 * @since 1.10
	 */
	public static function findUsersByPrimaryKey(array $userIds)
	{
	    return array_map(
	    	function($v) {
                return Madhouse_Utils_Models::findUserByPrimaryKey($v);
	    	}, array_unique($userIds)
	    	// array_unique is useful to not make the same request more than one time !
	    );
	}
}

?>