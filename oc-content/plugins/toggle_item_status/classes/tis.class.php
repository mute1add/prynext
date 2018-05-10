<?php
/*
Plugin Name: Toggle Item Status
Plugin URI: http://amfearliath.tk/osclass-toggle-item-status
Description: User can mark items as sold or make them available again
Version: 1.0.2
Author: Liath
Author URI: http://amfearliath.tk
Short Name: toggle_item_status
Plugin update URI: toggle-item-status

DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE 
Version 2, December 2004 

Copyright (C) 2004 Sam Hocevar <sam@hocevar.net> 

Everyone is permitted to copy and distribute verbatim or modified 
copies of this license document, and changing it is allowed as long 
as the name is changed. 

DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE 
TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION 

0. You just DO WHAT THE FUCK YOU WANT TO.
*/ 

class t_i_s extends DAO {
    
    private static $instance ;
    
    public static function newInstance() {
        if( !self::$instance instanceof self ) {
            self::$instance = new self ;
        }
        return self::$instance ;
    }
    
    function __construct() {
        parent::__construct();
    }
    
    function tis_table() {
        return '`'.DB_TABLE_PREFIX.'t_item_toggle_status`';
    }
    
    function tis_install() {        
        $file = osc_plugin_resource('toggle_item_status/assets/create_table.sql');
        $sql = file_get_contents($file);

        if (!$this->dao->importSQL($sql)) {
            throw new Exception( "Error importSQL::order_now<br>".$file ) ;
        }            
    }
    
    function tis_uninstall() {    
        $this->dao->query(sprintf('DROP TABLE %s', $this->tis_table()));        
    }
    
    function tis_check_user($user, $item) {
        if (osc_logged_user_id() == $item) {
            return true;
        } else {
            return false;
        }  
    }
    
    function tis_check_status($id) {        
        $this->dao->select('*');
        $this->dao->from($this->tis_table());
        $this->dao->where('ti_id', $id);

        $result = $this->dao->get();
        if (!$result) { return false; }
        
        $row = $result->row();        

        if ($row && $row["ti_status"] == 1) { 
            return true;
        } else{
            return false; 
        }            
    }
    
    function tis_get_data($id) {        
        $this->dao->select('*');
        $this->dao->from($this->tis_table());
        $this->dao->where('ti_id', $id);

        $result = $this->dao->get();
        if (!$result) { return false; }
        
        return $result->row();        
            
    }
    
    function tis_insert($data) {        
        $this->dao->insert($this->tis_table(), $data);
    }
    
    function tis_update($values, $where) {
        $this->dao->from($this->tis_table());
        $this->dao->set($values);
        $this->dao->where($where);
        
        if (!$this->dao->update()) {
            return false;
        }
        
        return true;
    }
    
    function tis_change_status($param) {            
        $ti_id     = $param['ti_id'];
        $ti_status = $param['ti_status'];
        
        if ($param['ti_status'] == "ti_sold"){
            $check = $this->tis_get_data($ti_id);
            
            if (!$check) {
                $this->tis_insert(array('ti_id' => $ti_id, 'ti_status' => '1'));
            } else{
                $this->tis_update(array('ti_status' => '1'), array('ti_id' => $ti_id));
            }
        }
        
        elseif ($ti_status == "ti_available"){
            $this->dao->query(sprintf('DELETE FROM %s WHERE ti_id = "%s"', $this->tis_table(), $ti_id));
        }
    }
    
    public static function tis_status_box($id) {
        if (t_i_s::newInstance()->tis_check_status($id)) {
            echo '<div id="tis_status_box" class="active">'.__('No longer available', 'toggle_item_status').'</div>';
        } else {
            echo '<div id="tis_status_box"></div>';
        }
    }
    
    function tis_show_status($id, $item) {
        
        if ($this->tis_check_user($id, $item)) {
            if ($this->tis_check_status($id)) {
                $status = 'ti_available';
                $translation = __('Mark as available', 'toggle_item_status');
                $url = osc_item_url();        
            } else {
                $status = 'ti_sold';
                $translation = __('Mark as sold', 'toggle_item_status');    
                $url = osc_item_url();     
            }
            
            $form = '
                    <div id="toggle_item_status">
                        <form id="tis_sold" method="post" action="'.$url.'">
                            <input type="hidden" name="page" value="item" />
                            <input type="hidden" name="ti_id" value="'.$id.'" />
                            <input type="hidden" name="ti_status" value="'.$status.'" />
                            <button type="submit">'.$translation.'</button>
                        </form>
                    </div>';
            
            echo $form;  
        }
    }    
}
?>