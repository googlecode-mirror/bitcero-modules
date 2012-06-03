<?php
// $Id$
// --------------------------------------------------------------
// Matches
// Module to publish and manage sports matches
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class MCHTeam extends RMObject
{
    private $cache_wons = array();
    
    public function __construct($id=null){
        
        $this->db =& XoopsDatabaseFactory::getDatabaseConnection();
        $this->_dbtable = $this->db->prefix("mch_teams");
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
            $this->primary = "id_team";
            return;
        }
        
    }
    
    public function id(){
        return $this->getVar('id_team');
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
            $link .= trim($mc['htbase'], '/').'/team/'.$this->getVar('nameid').'/';
        } else {
            $link .= 'modules/match/index.php?p=team&amp;id='.$this->getVar('nameid');
        }
        
        return $link;
        
    }
    
    /**
    * Get all players assigned to this team
    */
    public function get_players($obj = false){
        
        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $sql = "SELECT * FROM ".$db->prefix("mch_players")." WHERE team=".$this->id();
        $result = $db->query($sql);
        if($db->getRowsNum($result)<=0) return;
        
        $ret = array();
        while($row = $db->fetchArray($result)){
            
            if($obj){
                $ret[$row['id_player']] = new MCHPlayer();
                $ret[$row['id_player']]->assignVars($row);
            } else {
                $ret[] = $row;
            }
            
        }
        
        return $ret;
        
    }
    
    /**
    * Get all coaches assigned to this team
    */
    public function get_coaches($obj = false){
        
        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $sql = "SELECT * FROM ".$db->prefix("mch_coaches")." WHERE team=".$this->id();
        $result = $db->query($sql);
        if($db->getRowsNum($result)<=0) return;
        
        $ret = array();
        while($row = $db->fetchArray($result)){
            
            if($obj){
                $ret[$row['id_coach']] = new MCHCoach();
                $ret[$row['id_coach']]->assignVars($row);
            } else {
                $ret[] = $row;
            }
            
        }
        
        return $ret;
        
    }
    
    /**
    * Get wins
    */
    public function won_games($champ){
        
        if($champ<=0) return;
        
        if(isset($this->cache_wons[$champ]))
            return $this->cache_wons[$champ];
        
        $result = $this->db->query("SELECT COUNT(*) FROM ".$this->db->prefix("mch_score")." WHERE win=".$this->id()." AND champ=$champ");
        
        list($total) = $this->db->fetchRow($result);
        
        $this->cache_wons[$champ] = $total;
        
        return $total;
        
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
        
        $players = $this->get_players(true);
        foreach($players as $player){
            $player->delete();
        }
        
        $coaches = $this->get_coaches(true);
        foreach($coaches as $coach){
            $coach->delete();
        }
        
        return $this->deleteFromTable();
    }
    
}
