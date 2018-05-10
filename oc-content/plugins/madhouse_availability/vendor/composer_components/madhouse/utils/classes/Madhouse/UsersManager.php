<?php

class Madhouse_UsersManager extends Madhouse_EntitiesManager
{

    private static $instance;

	public static function newInstance()
    {
        if(!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }

	protected function findByPrimaryKey($id)
	{
		return User::newInstance()->findByPrimaryKey($id);
	}

    protected function mutate($e)
    {
    	return Madhouse_User::create($e);
    }

    protected function notFound()
    {
    	return Madhouse_User::create(
    		array(
    			"pk_i_id" => 0,
    			"s_name"  => __("'Dead' User", "madhouse_utils")
    		)
    	);
    }
}

?>