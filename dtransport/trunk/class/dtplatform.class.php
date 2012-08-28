<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage files for download in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class DTPlatform extends RMObject
{

	function __construct($id=null){

		$this->db = XoopsDatabaseFactory::getDatabaseConnection();
		$this->_dbtable = $this->db->prefix("dtrans_platforms");
		$this->setNew();
		$this->initVarsFromTable();

		if ($id==null) return;
		
		if (is_numeric($id)){
			
			if (!$this->loadValues($id)) return;
			$this->unsetNew();
            
		} else {
            
            $this->primary="nameid";
            if ($this->loadValues($id)) $this->unsetNew();
            $this->primary="id_platform";
            
        }
	
	}


	public function id(){
		return $this->getVar('id_platform');
	}

	public function name(){
		return $this->getVar('name');
	}

	public function setName($name){
		return $this->setVar('name',$name);
	}

    public function nameId(){
        return $this->getVar('nameid');
    }

    public function setNameId($val){
        return $this->setVar('nameid',$val);
    }

    public function permalink(){
        $rmu = RMUtilities::get();
        $mc = $rmu->module_config('dtransport');

        if($mc['permalinks']){
            return XOOPS_URL.'/'.trim($mc['htbase'], '/').'/platform/'.$this->nameId().'/';
        } else {
            return XOOPS_URL.'/modules/dtransport/index.php?p=platform&amp;id='.$this->id();
        }
    }

	public function save(){
		if ($this->isNew()){
			return $this->saveToTable();
		}
		else{
			return $this->updateTable();
		}

	}

	public function delete(){
			
		$sql="DELETE FROM ".$this->db->prefix('dtrans_platsoft')." WHERE id_platform=".$this->id();
		$result=$this->db->queryF($sql);

		if (!$result) return false;
			

		return $this->deleteFromTable();
	}


}
