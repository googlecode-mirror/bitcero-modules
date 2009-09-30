<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class RMFunctions
{
	public function get(){
		static $instance;
		
		if (!isset($instance))
			$instance = new RMCFunctions();
		
		return $instance;
		
	}
	
	/**
	* Check the number of images category on database
	*/
	public function get_num_records($table, $filters=''){
		
		$db = Database::getInstance();
		
		$sql = "SELECT COUNT(*) FROM ".$db->prefix($table);
		$sql .= $filters!='' ? " WHERE $filters" : '';
		
		list($num) = $db->fetchRow($db->query($sql));
		
		return $num;
		
	}
	
	/**
	* Create the module toolbar. This function must be called only from rmcommon module administration
	*/
	public function create_toolbar(){
		
		RMTemplate::get()->add_tool(__('Dashboard','rmcommon'), 'index.php', 'images/dashboard.png', 'dashboard');
		RMTemplate::get()->add_tool(__('Images','rmcommon'), 'images.php', 'images/images.png', 'imgmanager');
		RMTemplate::get()->add_tool(__('Plugins','rmcommon'), 'plugins.php', 'images/plugin.png', 'plguinsmng');
		
	}
	
}
