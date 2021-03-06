<?php
// $Id$
// --------------------------------------------------------------
// RapidDocs
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

		$this->db =& XoopsDatabaseFactory::getDatabaseConnection();
		$this->_dbtable = $this->db->prefix("rd_sections");
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

        $result = $this->db->query("SELECT * FROM ".$this->db->prefix("rd_meta")." WHERE section='".$this->id()."' AND edit='0'");
        while($row = $this->db->fetchArray($result)){
            $this->metas[$row['name']] = $row;
        }
    }
    
    /**
    * Add metas to the current section
    */
    public function add_meta($key, $value){
        if ($key=='') return;
        $this->metas[$key] = $value;
    }
    
    /**
    * Clear metas array
    */
    public function clear_metas(){
        $this->metas = array();
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
        $this->load_meta();
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
    
    /**
    * Get the permalink for this section
    */
    public function permalink($edit = 0){
        global $standalone;
        $config = RMUtilities::module_config('docs');
        
        $res = new RDResource($this->getVar('id_res'));
        
        if ($res->getVar('single') && defined('RD_LOCATION') && RD_LOCATION=='resource_content'){
            return "#".$this->getVar('nameid');
        }
        
        if ($config['permalinks']){
            
            if($this->getVar('parent')>0){
                $sec = new RDSection($this->getVar('parent'));
                $perma = $sec->permalink().'#'.($edit ? '<span>'.$this->getVar('nameid').'</span>' : $this->getVar('nameid'));
            } else {
                $perma = ($config['subdomain']!='' ? $config['subdomain'] : XOOPS_URL).$config['htpath'].'/'.$res->getVar('nameid').'/'.($edit ? '<span>'.$this->getVar('nameid').'</span>' : $this->getVar('nameid')).'/';
                $perma .= $standalone ? 'standalone/1/' : '';
            }
            
            
        } else {
            
            if($this->getVar('parent')>0){
                $sec = new RDSection($this->getVar('parent'));
                $perma = $sec->permalink().'#'.$this->getVar('nameid');
            } else {
                $perma = XOOPS_URL.'/modules/docs/index.php?page=content&amp;id='.$this->id();
                $perma .= $standalone ? '&amp;standalone=1' : '';
            }
            
        }
        
        return $perma;
        
    }
    
    public function editlink(){
        
        $config = RMUtilities::module_config('docs');
        if ($config['permalinks']){
            $link = RDFunctions::url().'/edit/'.$this->id().'/'.$this->getVar('id_res');
        } else {
            $link = RDFunctions::url().'?page=edit&id='.$this->id().'&res='.$this->getVar('id_res');
        }
        
        return $link;
        
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
        $this->db->queryF("DELETE FROM ".$this->db->prefix("rd_meta")." WHERE section='".$this->id()."'");
        if (empty($this->metas)) return true;
        $sql = "INSERT INTO ".$this->db->prefix("rd_meta")." (`name`,`value`,`section`,`edit`) VALUES ";
        $values = '';
        foreach ($this->metas as $name => $value){
            if (is_array($value)) $value = $value['value'];
            $values .= ($values=='' ? '' : ',')."('".MyTextSanitizer::addSlashes($name)."','".MyTextSanitizer::addSlashes($value)."','".$this->id()."','0')";
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
	
		// Change the parent on child sections
		$sql="UPDATE ".$this->db->prefix('rd_sections')." SET parent=0 WHERE parent='".$this->id()."'";
		$result=$this->db->queryF($sql);

		if (!$result) return $ret;		

		$ret=$this->deleteFromTable();
	
		return $ret;
		
	}


}
