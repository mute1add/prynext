<?php
class ModelAvatarfree extends DAO
    {
      	private static $instance ;

      
        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self ;
            }
            return self::$instance ;
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
                throw new Exception( "Error importSQL::ModelAvatarfree<br>".$file ) ;
            }
        }
		
      
        public function getTable_Avatarfree()
        {
            return DB_TABLE_PREFIX.'t_avatar_free' ;
        }
		
		
		
		
		public function uninstall()
        {
			$this->dao->query(sprintf('DROP TABLE %s', $this->getTable_Avatarfree()) ) ;
		}
		
		
		public function getAvatarfree($user ="")
        {
			$this->dao->select();
            $this->dao->from( $this->getTable_Avatarfree() ) ;
			$this->dao->where('fk_i_user_id', $user);
			
			$result = $this->dao->get();
            if($result == false) {
                return 0;
            }
            $avatar = $result->row();
            return $avatar['avatar'];
			
		
        }
	
		
		public function insertAvatarfree( $avatar, $user )
        {
            return $this->dao->insert($this->getTable_Avatarfree(), array('avatar' => $avatar, 'fk_i_user_id' => $user)) ;
        }
		
		public function updateAvatarfree( $avatar, $user) {
			$aSet = array('avatar' => $avatar);
			$aWhere = array('fk_i_user_id' => $user);
		
			return $this->_updatefree($this->getTable_Avatarfree(), $aSet, $aWhere);
		  }
		
		
		 
       // update
        function _updatefree($table, $values, $where)
        {
            $this->dao->from($table) ;
            $this->dao->set($values) ;
            $this->dao->where($where) ;
            return $this->dao->update() ;
        }
    }
?>