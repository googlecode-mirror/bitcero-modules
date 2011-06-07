<?php
// $Id$
// --------------------------------------------------------------
// Matches
// Module to publish and manage sports matches
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class MCHFunctions
{
    public function toolbar(){
        RMTemplate::get()->add_tool(__('Dashboard','match'), './index.php', '../images/dashboard.png', 'dashboard');
        RMTemplate::get()->add_tool(__('Categories','match'), './categories.php', '../images/category.png', 'categories');
        RMTemplate::get()->add_tool(__('Championships','match'), './champ.php', '../images/champ.png', 'championships');
        RMTemplate::get()->add_tool(__('Fields','match'), './fields.php', '../images/field.png', 'fields');
        RMTemplate::get()->add_tool(__('Teams','match'), './teams.php', '../images/teams.png', 'teams');
        RMTemplate::get()->add_tool(__('Players','match'), './roster.php', '../images/players.png', 'roster');
        RMTemplate::get()->add_tool(__('Coaches','match'), './coaches.php', '../images/coaches.png', 'coaches');
        RMTemplate::get()->add_tool(__('Role Play','match'), './role.php', '../images/role.png', 'role');
    }
    
    /**
    * Get categories tree
    * @param int Id of the category where search must start
    * @return array
    */
    public function categories_tree(&$categories, $parent = 0, $indent = 0, $include_subs = true, $exclude=0, $order="id_cat DESC"){
        
        $db = Database::getInstance();
        
        $sql = "SELECT * FROM ".$db->prefix("mch_categories")." WHERE parent='$parent' ORDER BY $order";
        $result = $db->query($sql);
        while ($row = $db->fetchArray($result)){
            if ($row['id_cat']==$exclude) continue;
            
            $cat = new MCHCategory();
            $cat->assignVars($row);
            
            $row['indent'] = $indent;
            $row['link'] = $cat->permalink();
            $row['description'] = $cat->getVar('description');
            $row['id'] = $cat->id();
            $categories[$row['id_cat']] = $row;
            if ($include_subs) MCHFunctions::categories_tree($categories, $row['id_cat'], $indent+1, $include_subs, $exclude);
        }        
        
    }
    
    /**
    * Get the page according to given id
    * 
    * @param int Element identifier
    */
    public function page_from_item($id, $w=''){
        global $xoopsModuleConfig;
        
        $db = Database::getInstance();
        
        if($w=='team'){
            $tb = $db->prefix('mch_teams');
            $fd = 'id_team';
            $ord = 'wins,active';
        } elseif($w=='player') {
            $tb = $db->prefix('mch_players');
            $fd = 'id_player';
            $ord = 'lastname,surname,name';
        }
        
        $result = $db->query("SELECT $fd FROM $tb WHERE $fd='$id'");
        if (!$db->getRowsNum($result)) return;

        list($id) = $db->fetchRow($result);

        // Determine on what page the post is located (depending on $pun_user['disp_posts'])
        $result = $db->query("SELECT $fd FROM $tb WHERE $fd='$id' ORDER BY $ord");
        $num = $db->getRowsNum($result);
        
        for ($i = 0; $i < $num; ++$i)
        {
            list($cur_id) = $db->fetchRow($result);
            if ($cur_id == $pid)
                break;
        }
        ++$i;    // we started at 0
        $_REQUEST['page'] = ceil($i / 15);
        return $id;
    }
    
    /**
    * Get all teams list
    * @param bool Return objects MCHTeam
    * @return array|object
    */
    public function all_teams($o = false, $q=''){
        
        $db = Database::getInstance();
        $sql = "SELECT * FROM ".$db->prefix("mch_teams").($q!=''?" WHERE $q":'')." ORDER BY `name`,category";
        $result = $db->query($sql);
        
        if($db->getRowsNum($result)<=0) return;
        
        $teams = array();
        $cache_cat = array();
        
        while($row = $db->fetchArray($result)){
            
            if(isset($cache_cat[$row['category']])){
                $cat = $cache_cat[$row['category']];
            } else {
                $cache_cat[$row['category']] = new MCHCategory($row['category']);
                $cat = $cache_cat[$row['category']];
            }
            
            if($o){
                
                $teams[$row['id_team']] = new MCHTeam();
                $teams[$row['id_team']]->assignVars($row);
            } else {
                $row['category_object'] = array(
                    'name' => $cat->getVar('name'),
                    'id' => $cat->id()
                );
                $teams[] = $row;
            }            
            
        }
        
        return $teams;
        
    }
    
    /**
    * Get the name of a position according to its number
    * @param int Position ID
    * @return string
    */
    public function position_name($position){
        
        $names = array(
            1 => __('Pitcher','match'),
            2 => __('Catcher','match'),
            3 => __('First base','match'),
            4 => __('Second base','match'),
            5 => __('Third base','match'),
            6 => __('Shortstop','match'),
            7 => __('Left field','match'),
            8 => __('Center field','match'),
            9 => __('Right field','match'),
        );
        
        return $names[$position];
        
    }
    
    /**
    * Get the name of a charge according to its number
    * @param int Charge ID
    * @return string
    */
    public function charge_name($charge){
        
        $names = array(
            1 => __('Manager','match'),
            2 => __('Coach','match'),
            3 => __('Assitant','match')
        );
        
        return $names[$charge];
        
    }
    
    /**
    * Get the fields list
    * @return array
    */
    public function all_fields($obj = false){
        
        $db = Database::getInstance();
        $sql = "SELECT * FROM ".$db->prefix("mch_fields")." ORDER BY `name`";
        $result = $db->query($sql);
        
        if($db->getRowsNum($result)<=0) return;
        
        $fields = array();
        
        while($row = $db->fetchArray($result)){
            
            if($obj){
                
                $fields[$row['id_field']] = new MCHField();
                $fields[$row['id_field']]->assignVars($row);
                
            } else {
                
                $fields[] = $row;
            }            
            
        }
        
        return $fields;
        
    }
    
    /**
    * Get championships list
    */
    public function all_championships(){
        
        $db = Database::getInstance();
        $sql = "SELECT * FROM ".$db->prefix("mch_champs")." ORDER BY start DESC";
        $result = $db->query($sql);
        
        while($row = $db->fetchArray($result)){
        
            $ch = new MCHChampionship();
            $ch->assignVars($row);
            
            $champs[$ch->id()] = array(
                'id' => $ch->id(),
                'name' => $ch->getVar('name'),
                'nameid' => $ch->getVar('nameid'),
                'start' => $ch->getVar('start'),
                'end' => $ch->getVar('end'),
                'current' => $ch->getVar('start')<=time() && $ch->getVar('end')>=time()?true:false
            );
            
            unset($ch);
            
        }
        
        return $champs;
    }
    
    public function get_ranking($champ, $category){
        
        function sort_teams($a, $b){
            if($a==$b) return 0;
            
            return $a<$b?1:-1;
        }
        
        
        $teams = MCHFunctions::all_teams(true, "category=$category");
        $tranking = array();
        foreach($teams as $team){
            $tranking[$team->id()] = array(
                'wons' => $team->won_games($champ),
                'id' => $team->id(),
                'name' => $team->getVar('name'),
                'logo' => $team->getVar('logo')
            );
        }
        
        $ord = array();
        foreach($tranking as $r){
            $ord[$r['id']] = $r['wons'];
        }
        
        uasort($ord, 'sort_teams');
        
        $ranking = array();
        foreach($ord as $k => $v){
            $ranking[] = $tranking[$k];
        }
        
        unset($tranking);
        
        return $ranking;
    
    }
    
}