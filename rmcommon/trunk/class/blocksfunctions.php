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
            $pos[$row['id_position']] = $row;
        }
        return $pos;
    }
    
    /**
     * Get blocks
     */
    public function construct_blocks(){
        global $xoopsConfig, $xoopsModule, $xoopsUser;
        
        $sides = array();
        
        foreach(self::block_positions() as $id => $row){
            $sides[$id] = $row['tag'];
            $blocks[$row['tag']] = array();
        }
        
        $startMod = ( $xoopsConfig['startpage'] == '--' ) ? 'system' : $xoopsConfig['startpage'];
	if ( @is_object( $xoopsModule ) ) {
            list( $mid, $dirname ) = array( $xoopsModule->getVar('mid'), $xoopsModule->getVar('dirname') );
            $isStart = ( substr( $_SERVER['PHP_SELF'], -9 ) == 'index.php' && $xoopsConfig['startpage'] == $dirname );
	} else {
            list( $mid, $dirname ) = array( 0, 'system' );
            $isStart = !@empty( $GLOBALS['xoopsOption']['show_cblock'] );
	}

	$groups = @is_object( $xoopsUser ) ? $xoopsUser->getGroups() : array( XOOPS_GROUP_ANONYMOUS );
        
        $subpage = isset($xoopsOption['module_subpage']) ? $xoopsOption['module_subpage'] : '';
        $barray = array(); // Array of retrieved blocks
        
        $barray = self::get_blocks($groups, $mid, $isStart, XOOPS_BLOCK_VISIBLE, '', 1, $subpage);
        
        foreach($barray as $block){
            
            $side = $sides[$block->getVar('canvas')];
            if($content = self::buildBlock($block)){
                $blocks[$side][$content['id']] = $content;
            }
        }
        
        unset($side,$sides,$content,$subpage,$barray,$groups,$startMod);
        
        return $blocks;
        
    }
    
    public function get_blocks($groupid, $mid=0, $toponlyblock=false, $visible=null, $orderby='b.weight,b.wid', $isactive=1, $subpage=''){
        
        $orderby = $orderby=='' ? 'b.weight,b.bid' : $orderby;
    	
        $db =& XoopsDatabaseFactory::getDatabaseConnection();
        $ret = array();
        $sql = "SELECT DISTINCT gperm_itemid FROM ".$db->prefix('group_permission')." WHERE (gperm_name = 'block_read') AND gperm_modid = 1";
        if ( is_array($groupid) ) {
            $sql .= ' AND gperm_groupid IN ('.implode(',', $groupid).',0)';
        } else {
            if (intval($groupid) > 0) {
                $sql .= ' AND gperm_groupid IN (0,'.$groupid.')';
            }
        }

        $result = $db->query($sql);
        
        $blockids = array();
        while ( $myrow = $db->fetchArray($result) ) {
            $blockids[] = $myrow['gperm_itemid'];
        }
        
        if (!empty($blockids)) {
            $sql = 'SELECT b.* FROM '.$db->prefix('rmc_blocks').' b, '.$db->prefix('rmc_bkmod').' m WHERE m.bid=b.bid';
            $sql .= ' AND b.isactive='.$isactive;
            if (isset($visible)) {
                $sql .= ' AND b.visible='.intval($visible);
            }
            $mid = intval($mid);
            if (!empty($mid)) {
                $sql .= ' AND m.mid IN (0,'.$mid;
                if ($toponlyblock) {
                    $sql .= ',1';
                }
                $sql .= ')';
            } else {
             /*   if ($toponlyblock) {*/
                    $sql .= ' AND m.mid IN (0,1)';
                /*} else {
                    $sql .= ' AND m.app_id=0';
                }*/
            }
            $sql .= $subpage!=''  ? " AND (m.page='$subpage' OR m.page='--')" : '';
            $sql .= ' AND b.bid IN ('.implode(',', $blockids).')';
            $sql .= ' ORDER BY '.$orderby;
            $result = $db->query($sql);
            //echo $sql; die();
            while ( $myrow = $db->fetchArray($result) ) {
                $block = new RMInternalBlock();
                $block->assignVars($myrow);
                $ret[$myrow['bid']] = $block;
                unset($block);
            }
        }

        return $ret;
        
    }
    
    function buildBlock($bobj){
        
        global $xoopsTpl, $xoTheme;
        $template = $xoopsTpl;
        
        $block = array(
            'id' => $bobj->getVar('bid') ,
            'module' => $bobj->getVar('dirname') ,
            'title' => $bobj->getVar('name') ,
            // 'name'        => strtolower( preg_replace( '/[^0-9a-zA-Z_]/', '', str_replace( ' ', '_', $bobj->getVar( 'name' ) ) ) ),
            'weight' => $bobj->getVar('weight'));

        $bcachetime = intval($bobj->getVar('bcachetime'));
        if (empty($bcachetime)) {
            $template->caching = 0;
        } else {
            $template->caching = 2;
            $template->cache_lifetime = $bcachetime;
        }
        $template->setCompileId($bobj->getVar('dirname', 'n'));
        if($bobj->getVar('element_type')=='plugin'){
            $tplName = XOOPS_ROOT_PATH.'/modules/'.$bobj->getVar('element').'/plugins/'.$bobj->getVar('dirname').'/templates/blocks/'.$bobj->getVar('template');
        } else {
            $tplName = ($tplName = $bobj->getVar('template')) ? "db:$tplName" : 'db:system_block_dummy.html';
        }
        $cacheid = $xoTheme->generateCacheId('blk_' . $bobj->getVar('bid'));
        /**
         * , $bobj->getVar( 'show_func', 'n' )
         */

        $xoopsLogger =& XoopsLogger::getInstance();
        if (!$bcachetime || !$template->is_cached($tplName, $cacheid)) {
            $xoopsLogger->addBlock($bobj->getVar('name'));
            if ($bresult = $bobj->buildBlock()) {
                $template->assign('block', $bresult);
                $block['content'] = $template->fetch($tplName, $cacheid);
            } else {
                $block = false;
            }
        } else {
            $xoopsLogger->addBlock($bobj->getVar('name'), true, $bcachetime);
            $block['content'] = $template->fetch($tplName, $cacheid);
        }
        $template->setCompileId();
        return $block;
    }
    
}
