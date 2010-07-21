<?php
// $Id$
// --------------------------------------------------------------
// Rapid Docs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------


class RDSection extends RMObject{
    
    /**
    * Meta values container
    */
    private $metas = array();

	function __construct($id=null, $res=0){

		$this->db =& Database::getInstance();
		$this->_dbtable = $this->db->prefix("pa_sections");
		$this->setNew();
		$this->initVarsFromTable();

		if ($id==null) return;
		
		if (is_numeric($id)){
			
			if (!$this->loadValues($id)) return;
			$this->unsetNew();
		}else{
			$sql = "SELECT * FROM ".$this->_dbtable." WHERE nameid='$id' AND id_res='$res'";
			$result = $this->db->query($sql);
			if ($this->db->getRowsNum($result)<=0) return;
			
			$row = $this->db->fetchArray($result);
			$this->assignVars($row);
			$this->unsetNew();
		}	
	
	}
    
    /**
    * Meta data
    */
    private function load_meta(){
        if (!empty($this->metas)) return;

        $result = $this->db->query("SELECT * FROM ".$this->db->prefix("pa_meta")." WHERE section='".$this->id()."'");
        while($row = $this->db->fetchArray($result)){
            $this->metas[$row['name']] = $row;
        }
    }
    
    /**
    * Get a single meta from section
    * @param string Meta name
    * @return string|array
    */
    public function meta($name=''){
        $this->load_meta();
        
        if (trim($name)=='') return false;
        
        if(!isset($this->metas[$name])) return false;
            
        return $this->metas[$name]['value'];
        
    }
    
    /**
    * Return all metas existing for a section
    * @return array
    */
    public function metas($values = true){
    
        $metas = array();
        
        if(!$values)
            return $this->metas;
        
        foreach ($this->metas as $data){
            $metas[$data['name']] = $data['value'];
        }
        
        return $metas;
        
    }

	public function id(){
		return $this->getVar('id_sec');
	}

	public function save(){
		if ($this->isNew()){
			$ret = $this->saveToTable();
		}
		else{
			$ret = $this->updateTable();
		}
        
        if(!$ret) return false;
        
        return $this->save_metas();
        

	}
    
    private function save_metas(){
        $this->db->queryF("DELETE FROM ".$this->db->prefix("pa_meta")." WHERE section='".$this->id()."'");
        if (empty($this->metas)) return true;
        $sql = "INSERT INTO ".$this->db->prefix("pa_meta")." (`name`,`value`,`section`) VALUES ";
        $values = '';
        foreach ($this->metas as $name => $value){
            if (is_array($value)) $value = $value['value'];
            $values .= ($values=='' ? '' : ',')."('".MyTextSanitizer::addSlashes($name)."','".MyTextSanitizer::addSlashes($value)."','".$this->id()."')";
        }
        
        if ($this->db->queryF($sql.$values)){
            return true;
        } else {
            $this->addError($this->db->error());
            return false;
        }
    }

	public function delete(){
		$ret=false;
	
		//Cambia las secciones hijas a secciones principales
		$sql="UPDATE ".$this->db->prefix('pa_sections')." SET parent=0 WHERE parent='".$this->id()."'";
		$result=$this->db->queryF($sql);

		if (!$result) return $ret;		

		$ret=$this->deleteFromTable();
	
		return $ret;
		
	}


}
