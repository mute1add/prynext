<?php
class ModelSlides extends DAO {
	/**
		* It references to self object: Modelslides
		* It is used as a singleton
		* 
		* @access private
		* @since unknown
		* @var Currency
		*/
	private static $instance ;
	/**
		* It creates a new Modelslides object class ir if it has been created
		* before, it return the previous object
		* 
		* @access public
		* @since unknown
		* @return Currency
		*/
	public static function newInstance() {
		if( !self::$instance instanceof self ) {
			self::$instance = new self ;
		}
		return self::$instance ;
	}
	/**
		* Construct
		*/
	function __construct() {
		parent::__construct();
		$this->setTableName('t_rslides') ;
		$this->setPrimaryKey('id') ;
		$this->setFields( array('id', 'uniqname', 'imagename', 'caption', 'description', 'link') ) ;
	}
		/**
		* Return table name Slides
		* @return string
		*/
	public function getTable() {
		return DB_TABLE_PREFIX.'t_rslides';
	}
		/**
		* Import sql file
		* @param type $file 
		*/
	public function import($file) {
		$path = osc_plugin_resource($file) ;
		$sql = file_get_contents($path);
		if(! $this->dao->importSQL($sql) ){
			throw new Exception( $this->dao->getErrorLevel().' - '.$this->dao->getErrorDesc() ) ;
		}
	}
		/**
		* Remove data and tables related to the plugin.
		*/
	public function uninstall() {
		$this->dao->query(sprintf('DROP TABLE %s', $this->getTable()) ) ;
	}
		public function saveSlides($uniqname,$imagename,$caption,$description,$link) {
		ModelSlides::newInstance()->insert(array(
		'id' => '',
		'uniqname' => $uniqname,
		'imagename' => $imagename,
		'caption' => $caption,
		'description' => $description,
		'link' => $link
		));
	}
		public function getSlides() {
		$this->dao->select() ;
		$this->dao->from($this->getTable()) ;
		$results = $this->dao->get() ;
		if( !$results ) {
			return array() ;
		}
		return $results->result();
	}
		public function getSlidesById($id) {
		$this->dao->select() ;
		$this->dao->from($this->getTable()) ;
		$this->dao->where('id', $id );
		$result = $this->dao->get() ;
		if( !$result ) {
			return array() ;
		}
		return $result->row();
	}
		public function getSlidesByImage($imagename) {
		$this->dao->select() ;
		$this->dao->from($this->getTable()) ;
		$this->dao->where('uniqname', $imagename );
		$result = $this->dao->get() ;
		if( !$result ) {
			return array() ;
		}
		return $result->row();
	}
	function updateSlides($id,$uniqname,$imagename,$caption,$description,$link){
		$this->dao->from($this->getTable()) ;
		$this->dao->set(array(
		'uniqname' => $uniqname,
		'imagename' => $imagename,
		'caption' => $caption,
		'description' => $description,
		'link' => $link
		)) ;
		$this->dao->where(array(
		'id' => $id
		)) ;
		return $this->dao->update() ;
	}
		public function deleteSlides($id)
	{
		$this->dao->from($this->getTable()) ;
		$this->dao->where(array(
		'id' => $id
		)) ;
		return $this->dao->delete();
	}
	}
?>