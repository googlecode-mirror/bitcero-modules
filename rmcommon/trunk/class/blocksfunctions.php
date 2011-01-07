<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class RMBlocksFunctions
{
    /**
    * Get the available widgets list
    * 
    * @return array
    */
    public function get_available_list($mods = null){
        $db =& Database::getInstance();
        
        if($mods==null || empty($mods))
            $mods = RMFunctions::get_modules_list();
        
        $list = array(); // Block list to return
        
        foreach($mods as $mod){
            
            if(!file_exists(XOOPS_ROOT_PATH.'/modules/'.$mod['dirname'].'/xoops_version.php'))
                continue;
            
            load_mod_locale($mod['dirname']);
            $module = new XoopsModule();
            $module->loadInfoAsVar($mod['dirname']);
            
            $list[$mod['dirname']] = array(
                'name' => $mod['name'],
                'blocks' => $module->getInfo('blocks')
            );
            
        }
                
        // Event generated to modify the available widgets list
        $list = RMEvents::get()->run_event('rmcommon.available.widgets', $list);
        return $list;
    }
    
    /**
    * Get blocks positions
    */
    public function block_positions(){
        $db =& Database::getInstance();
        $result = $db->query("SELECT * FROM " . $db->prefix("rmc_blocks_positions"));
        $pos = array();
        while($row = $db->fetchArray($result)){
            $pos[] = $row;
        }
        return $pos;
    }
}
