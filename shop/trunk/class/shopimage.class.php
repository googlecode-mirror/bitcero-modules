<?php
// $Id$
// --------------------------------------------------------------
// MiniShop 3
// Module for catalogs
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------


class ShopImage extends RMObject
{

	public function __construct($id=null){
		
		$this->db =& XoopsDatabaseFactory::getDatabaseConnection();
		$this->_dbtable = $this->db->prefix("shop_images");
		$this->setNew();
		$this->initVarsFromTable();
		
		 if ($id==null) return;
        
        	if (!$this->loadValues(intval($id))) return;
        
	        $this->unsetNew();
		
	}	

	public function id(){
		return $this->getVar('id_image');
	}

	public function save(){
		if ($this->isNew()){
			return $this->saveToTable();
		} else {
			return $this->updateTable();
		}
	}

	public function delete(){
		//Eliminamos las imágenes
		@unlink(XOOPS_UPLOAD_PATH.'/minishop/'.$this->getVar('file'));
		@unlink(XOOPS_UPLOAD_PATH.'/minishop/ths/'.$this->getVar('file'));

		return $this->deleteFromTable();
	}

} 
