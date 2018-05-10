<?php

/**
 * Adds support for Mustache.php/Mustache.js to Osclass.
 *
 * By using the same mechanism for mustache templates as the ones used for
 * for scripts and styles. It allow to register and enqueue templates to be
 * rendered (used) in views files. See helpers/hMustaches.php to understand
 * how to use this class.
 * 
 * @author Madhouse Design Co.
 * @package Madhouse
 * @subpackage Utils
 * @since 1.00
 */
class Madhouse_Utils_Mustaches extends Dependencies
{
    private static $instance;

    public static function newInstance()
    {
        if(!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function __construct()
    {
        parent::__construct();
    }
	
	public function registerMustache($id, $file, $dependencies=NULL) {
		$this->register($id, $file, $dependencies);
	}

	public function unregisterMustache($id) {
		$this->unregister($id);
	}

	public function enqueueMustache($id) {
		$this->queue[$id] = $id;
	}

	public function dequeueMustache($id) {
		unset($this->queue[$id]);
	}

	public function getMustaches() {
		$mustaches = array();
		parent::order();
		foreach ($this->resolved as $id) {
			if(isset($this->registered[$id]["url"])) {
				array_push($mustaches, array("id" => $id, "url" => $this->registered[$id]["url"]));
			}
		}
		return $mustaches;
	}

	public function getMustache($id) {
		parent::order();
		if(isset($this->registered[$id]["url"])) {
			return array("id" => $id, "url" => $this->registered[$id]["url"]);
		}
		return NULL;
	}

	public function printMustaches() {
		foreach ($this->getMustaches() as $mustache) {
			echo '<script id=' . $mustache["id"] . ' type="text/mustache-template">';
			require_once($mustache["url"]);
			echo '</script>';
		}
	}

	public function render($id, $values) {
		$mustache = $this->getMustache($id);
		if($mustache == NULL) {
			return;
		}

		$m = new Mustache_Engine();
		return $m->render(file_get_contents($mustache["url"]), $values);
	}
}

?>