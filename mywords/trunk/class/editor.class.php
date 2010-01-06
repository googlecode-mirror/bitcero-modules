<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class MWEditor extends RMObject
{
	public function __construct($id = null){
		
		$this->db =& Database::getInstance();
        $this->_dbtable = $this->db->prefix("mw_editors");
        $this->setNew();
        $this->initVarsFromTable();
        
        $id = intval($id);
        
        if ($id==null || $id<=0) return;
		
	}
}
