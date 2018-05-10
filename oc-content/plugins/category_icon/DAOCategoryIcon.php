<?php

/**
 * Created by WTEH.
 * Date: 3/20/2016
 * Time: 12:35 AM
 */
class DAOCategoryIcon extends DAO
{
    /**
     * @var
     */
    private static $instance;


    /**
     *
     */
    function __construct() {
        parent::__construct();
        $this->setTableName('bs_theme_category_icon') ;
        $this->setPrimaryKey('bs_key_id') ;
        $this->setFields( array('bs_key_id', 'bs_image_name', 'pk_i_id') ) ;
    }

    /**
     * @return DAOCategoryIcon
     */
    public static function newInstance()
    {
        if( !self::$instance instanceof self ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * @param $id
     * @return array
     */
    public function findByIconId($id)
    {
        $this->dao->select('*');
        $this->dao->from($this->getTableName());
        $this->dao->where('bs_key_id', $id);
        $result = $this->dao->get();

        if($result == false) {
            return array();
        }

        return $result->result();
    }

    /**
     * @param $id
     * @return array
     */
    public function findByIconCategoryId($id)
    {
        $this->dao->select('*');
        $this->dao->from($this->getTableName());
        $this->dao->where('pk_i_id', $id);
        $result = $this->dao->get();

        if($result == false) {
            return array();
        }

        return $result->result();
    }

    /**
     * @return array
     */
    public function getAllCategoryIcons()
    {
        $this->dao->select('*');
        $this->dao->from($this->getTableName());
        $result = $this->dao->get();

        if($result == false) {
            return array();
        }

        return $result->result();
    }

    /**
     * @return array
     */
    public function addCategoryIcons(array $values)
    {
        return $this->dao->insert($this->getTableName(), $values);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function deleteByIconId($id)
    {
        $cond = array(
            $this->getPrimaryKey() => $id
        );

        return $this->delete($cond);
    }

    /**
     * @return mixed
     */
    public function dropCategoriIconTable()
    {
        return $this->dao->query('DROP TABLE '.$this->getTableName());
    }

    /**
     * @param $sql
     * @return bool
     */
    public function importSql($sql)
    {
        return $this->dao->importSQL($sql);
    }
}