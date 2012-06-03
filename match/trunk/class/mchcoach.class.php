<?php
// $Id$
// --------------------------------------------------------------
// Matches
// Module to publish and manage sports matches
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class MCHCoach extends RMObject
{
    
    public function __construct($id=null){
        
        $this->db =& XoopsDatabaseFactory::getDatabaseConnection();
        $this->_dbtable = $this->db->prefix("mch_coaches");
        $this->setNew();
        $this->initVarsFromTable();
        if ($id==''){
            return;
        }
        
        if (is_numeric($id)){
            if ($this->loadValues($id)) $this->unsetNew();
            return;
        } else {
            $this->primary = "nameid";
            if ($this->loadValues($id)) $this->unsetNew();
            $this->primary = "id_coach";
            return;
        }
        
    }
    
    public function id(){
        return $this->getVar('id_coach');
    }
    
    /**
    * Get the team link formated
    */
    public function permalink(){
        global $xoopsModule, $xoopsModuleConfig;
        
        if (isset($xoopsModule) && $xoopsModule->dirname()=='match'){
            $mc =& $xoopsModuleConfig;
        } else {
            $mc = RMUtilities::module_config('match');
        }
        
        $link = XOOPS_URL.'/';
        if ($mc['urlmode']){
            $link .= trim($mc['htbase'], '/').'/coach/'.$this->getVar('nameid').'/';
        } else {
            $link .= 'modules/match/index.php?p=coach&amp;id='.$this->getVar('nameid');
        }
        
        return $link;
        
    }
    
    /**
    * Save current team
    */
    public function save(){
        
        if($this->isNew())
            return $this->saveToTable();
        else
            return $this->updateTable();
        
    }
    
    public function delete(){
        
        // Delete pictures
        if($this->getVar('photo')!=''){
            @unlink(MCH_UP_PATH.'/coaches/'.$this->getVar('photo'));
            @unlink(MCH_UP_PATH.'/coaches/ths/'.$this->getVar('photo'));
        }
        
        return $this->deleteFromTable();
    }
    
}
