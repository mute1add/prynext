<?php

/*
 * Copyright (C) 2016 Puiu Calin
 * This program is a commercial software: is forbidden to use this software without licence, 
 * on multiple installations, and by purchasing from other source than those authorized for the sale of software.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

class Premium_OFF extends DAO {

    private static $instance;

    public static function newInstance() {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    function __construct() {
        parent::__construct();
    }

    public function getTable_items() {
        return DB_TABLE_PREFIX . 't_item';
    }

    public function get_expired_premium_items() {
        $date = date('Y-m-d H:i:s');
        $this->dao->select('pk_i_id');
        $this->dao->from($this->getTable_items());
        $this->dao->where('b_premium', 1);
        $this->dao->where("'$date' > dt_expiration");
        $this->dao->limit(10);
        $result = $this->dao->get();
        if (!$result->result()) {
            return NULL;
        }
        return $result->result();
    }

    public function update_ad($id) {
        $this->dao->update($this->getTable_items(), array('b_premium' => 0), array('pk_i_id' => $id));
    }

}
