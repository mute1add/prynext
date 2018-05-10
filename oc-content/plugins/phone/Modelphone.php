<?php
	class Modelphone extends DAO
	{
		
		private static $instance;
		
		public static function newInstance()
		{
			if( !self::$instance instanceof self ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
		
		function __construct()
		{
			parent::__construct();
		}
		
		public function import($file)
		{
			$path = osc_plugin_resource($file) ;
			$sql = file_get_contents($path);
			
			if(! $this->dao->importSQL($sql) ){
				throw new Exception( "Error importSQL::Modelcredit<br>".$file ) ;
			}
		}
		public function install_db_phone(){
			$this->import('phone/struct.sql');
		}
		
		public function uninstall_db_phone(){
			$this->dao->query(sprintf('DROP TABLE %s', $this->getTable_phone()) ) ;
		}
		public function getTable_phone()
        {
            return DB_TABLE_PREFIX.'t_item_phone';
		} 
		
		public function t_check_value($id){
			$this->dao->select('*');
			$this->dao->from($this->getTable_phone());
			$this->dao->where('fk_i_item_id', $id);
			$result = $this->dao->get();
			return $result->row();
		}
		public function t_insert_number($id, $number){
			$item = $this->t_check_value($id);
			if (isset($item['fk_i_item_id'])){
				$this->dao->update($this->getTable_phone(), array('s_phone' => $number), array('fk_i_item_id' => $id));	
				} else {
				$this->dao->insert($this->getTable_phone(), array('fk_i_item_id' => $id, 's_phone' => $number));
			}
		}
		
		public function delete_number($id){
			$this->dao->delete($this->getTable_phone(), array('fk_i_item_id' => $id));
		}
		
		public function delete_folder(){
    $dir =(osc_base_path() . 'oc-content/uploads/phone');
	$files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
      (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir); 
		}
	}
	
?>