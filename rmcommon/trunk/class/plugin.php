<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* Class to manage plugins objects
*/

class RMPlugin extends RMObject
{
    private $dir = '';
    /**
    * The plugin object
    */
    private $plugin;
    
	public function __construct($id=null){
		
		$this->db =& Database::getInstance();
        $this->_dbtable = $this->db->prefix("rmc_plugins");
        $this->setNew();
        $this->initVarsFromTable();
        
        if ($id==null){
            return;
        }
        
        // If provided id is numeric
        if (is_numeric($id) && $this->loadValues($id)){
			$this->unsetNew();
            $this->load_from_dir($this->getVar('dir'));
			return true;
        }
        
        // If id is a directory name
        $this->primary = 'dir';
        if ($this->loadValues($id)){
			$this->unsetNew();
            $this->load_from_dir($this->getVar('dir'));
        }
        
        $this->primary = 'id_plugin';
		
	}
	
    /**
    * This method must be called before to access the plugin methods
    * @param string Directory name
    */
    public function load_from_dir($dir){
        
        if ($dir == '') return false;
        
        $path = RMCPATH.'/plugins/'.$dir;
        
        if (!is_file($path.'/'.strtolower($dir).'-plugin.php')) return false;
        
        include_once $path.'/'.strtolower($dir).'-plugin.php';
        $class = ucfirst($dir).'CUPlugin';
        
        if (!class_exists($class)) return false;
        
        $this->plugin = new $class();
        $this->setVar('dir', $dir);
        
        foreach ($this->plugin->info() as $k => $v){
			$this->setVar($k, $v);
        }
        
        return true;
    }
    
	public function id(){
		return $this->getVar('id_plugin');
	}
	
	public function plugin($dir = ''){
		
		$dir = $dir=='' ? $this->getVar('dir') : $dir;
		$class = ucfirst($dir).'CUPlugin';
		
		if (is_a($this->plugin, $class))
			return $this->plugin;
		
		if (!class_exists($class))
			include_once RMCPATH.'/plugins/'.$dir.'/'.strtolower($dir).'-plugin.php';
		
		$plugin = new $class();
		return $plugin;
		
	}
	
	public function get_info($name){
		return $this->plugin()->get_info($name);
	}
	
	public function save(){
		if ($this->isNew()){
			return $this->saveToTable();
		} else {
			return $this->updateTable();
		}
	}
	
}
