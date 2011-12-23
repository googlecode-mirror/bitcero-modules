<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','blocks');
include '../../include/cp_header.php';

function createSQL()
{
    $mid = isset($_GET['mid']) ? intval($_GET['mid']) : 0;
    $subpage = isset($_GET['subpage']) ? $_GET['subpage'] : '';
    $group = isset($_GET['group']) ? intval($_GET['group']) : 0;
    $visible = isset($_GET['visible']) ? intval($_GET['visible']) : -1;
    $pos = isset($_GET['pos']) ? intval($_GET['pos']) : -1;
    
    $wid_globals = array(
        'mid'=>$mid,
        'subpage'=>$subpage,
        'group'=>$group,
        'visible'=>$visible
    );
    
    $db = Database::getInstance();

    // Obtenemos los widgets
    $tblw = $db->prefix("rmc_blocks");
    $tbll = $db->prefix("rmc_bkmod");
    $tblp = $db->prefix("group_permission");
    
    $sql = "SELECT $tblw.* FROM $tblw ".($subpage!='' || $mid>0 ? ", $tbll" : '').($group > 0 ? ", $tblp" : '');
    
    $and = false;
    
    if ($mid>0){
        $sql .= " WHERE ($tbll.mid='$mid' AND $tblw.bid=$tbll.bid ".($subpage!='' ? " AND $tbll.subpage='$subpage'" : '').") ";
        $and = true;
    }
    
    if ($group>0){
        $sql .= $and ? " AND " : " WHERE ";
        $sql .= " ($tblp.gperm_itemid=$tblw.bid AND $tblp.gperm_name='block_read' AND $tblp.gperm_groupid='$group')";
    }
    
    if ($pos>0){
        $sql .= $and ? " AND " : ' WHERE ';
        $sql .= " $tblw.canvas='$pos'";
    }
    
    $sql .= " ORDER BY weight";
    
    return $sql;

}

function show_rm_blocks()
{
    global $xoopsModule, $xoopsConfig, $wid_globals, $xoopsSecurity, $rmc_config;
    define('RMCSUBLOCATION','blocks');
    $db = Database::getInstance();
    
    $modules = RMFunctions::get_modules_list(1);
    
    // ** API Event **
    // Allows other methods to add o modify the list of available widgets
    $modules = RMEvents::get()->run_event('rmcommon.blocks.modules', $modules);

    // Cargamos los grupos
    $sql = "SELECT groupid, name FROM " . $db->prefix("groups") . " ORDER BY name";
    $result = $db->query($sql);
    $groups = array();
    while ($row = $db->fetchArray($result)) {
        $groups[] = array('id' => $row['groupid'], 'name' => $row['name']);
    }
    
    // Cargamos las posiciones de bloques
    $bpos = RMBlocksFunctions::block_positions();

    $sql = createSQL();
    $result = $db->query($sql);
    $blocks = array();
    $used_blocks = array();
    while ($row = $db->fetchArray($result)) {
        $mod = RMFunctions::load_module($row['element']);
        if(!$mod) continue;
        $used_blocks[] = array(
            'id' => $row['bid'], 
            'title' => $row['name'],
            'module' => array('id' => $mod->mid(), 'dir' => $mod->dirname(), 'name' => $mod->name()), 
            'canvas' => $bpos[$row['canvas']], 
            'weight' => $row['weight'], 
            'visible'=>$row['visible'],
            'type'=>$row['type'],
            'options'=>$row['edit_func']!='' ? 1 : 0,
            'description'=>$row['description']
        );
    }
    
    // ** API **
    // Event for manege the used widgets list
    $used_blocks = RMEvents::get()->run_event('rmcommon.used.blocks.list', $used_blocks);

    $positions = array();
    foreach ($bpos as $row){
        $positions[] = array('id' => $row['id_position'], 'name' => $row['name']);
    }
    
    $positions = RMEvents::get()->run_event('rmcommon.block.positions.list', $positions);

    xoops_cp_location('<a href="./">' . $xoopsModule->getVar('name') .
        '</a> &raquo; ' . __('Blocks','rmcommon'));
    RMTemplate::get()->add_style('blocks.css', 'rmcommon');
    RMTemplate::get()->add_local_script('blocks.js', 'rmcommon', 'include');
    RMTemplate::get()->add_local_script('jkmenu.js', 'rmcommon', 'include');
    RMTemplate::get()->add_style('forms.css', 'rmcommon');
    RMTemplate::get()->add_local_script('jquery-ui.min.js', 'rmcommon', 'include');
    
    if(!$rmc_config['blocks_enable']){
        showMessage(__('Internal blocks manager is currenlty disabled!','rmcommon'), 0);
    }
    
    xoops_cp_header();
    
    // Available Widgets
    
    $blocks = RMBlocksFunctions::get_available_list($modules);
    
    // Position
    $the_position = isset($_GET['pos']) ? intval($_GET['pos']) : '';

    include RMTemplate::get()->get_template("rmc_blocks.php", 'module', 'rmcommon');
    
    xoops_cp_footer();
}

/**
* Save the current positions
*/
function save_position($edit = 0){
    global $xoopsSecurity;
    
    if (!$xoopsSecurity->check()){
        redirectMsg('blocks.php', __('You are not allowed to do this action!','rmcommon'), 1);
        die();
    }
    
    $name = rmc_server_var($_POST, 'posname', '');
    $tag = rmc_server_var($_POST, 'postag', '');
    
    if($name=='' || $tag==''){
        redirectMsg(__('Please provide a name and tag for this new position!','rmcommon'));
        die();
    }
    
    if($edit){
        
        $id = rmc_server_var($_POST, 'id', '');
        if($id<=0)
            redirectMsg('blocks.php',__('You must specify a valid position ID!','rmcommon'), 1);
        
        $pos = new RMBlockPosition($id);
        if($pos->isNew())
            redirectMsg('blocks.php', __('Specified position does not exists!','rmcommon'), 1);
        
    } else {
        $pos = new RMBlockPosition();
    }
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    
    $pos->setVar('name',$name);
    $pos->setVar('tag',$tag);
    $pos->setVar('active',1);
    
    $sql = "SELECT COUNT(*) FROM ".$db->prefix("rmc_blocks_positions")." WHERE name='$name' OR tag='$tag'";
    if($edit) $sql .= " AND id_position<>$id";
    
    list($num) = $db->fetchRow($db->query($sql));

    if($num>0)
        redirectMsg('blocks.php', __('Already exists another position with same name or same tag!','rmcommon'), 1);
    
    if($pos->save())
        redirectMsg('blocks.php',__('Database updated successfully!','rmcommon'));
    else
        redirectMsg('blocks.php', __('Errors ocurred while trying to save data','rmcommon').'<br />'.$pos->errors());
    
}

/**
* Change the current visibility status for a set of selected widgets
*/
function toggle_visibility($s){
    global $exmSecurity;
    
    if (!$exmSecurity->check() || !$exmSecurity->checkReferer()){
        redirectMsg('widgets.php', __('You are not allowed to do this action!','rmcommon'), 1);
        die();
    }
    
    $ids = exm_server_var($_POST, 'widget', array());
    
    if(empty($ids) || !is_array($ids)){
        redirectMsg('widgets.php', __('An error was found while trying to do this action','rmcommon'), 1);
        die();
    }
    
    $db = EXMDatabase::get();
    $db->queryF("UPDATE ".$db->prefix("widgets")." SET visible=$s WHERE wid IN (".join(",",$ids).")");
    
    if ($db->error()==''){
        redirectMsg('widgets.php', __('Database updated successfully','global'), 0);
    } else {
        redirectMsg('widgets.php', __('There was an error while trying this action: ','rmcommon').$db->error(), 1);
    }
    
}

/**
* Delete a set of selected widgets
*/
function delete_widgets(){
    
    global $exmSecurity;
    
    if (!$exmSecurity->check() || !$exmSecurity->checkReferer()){
        redirectMsg('widgets.php', __('You are not allowed to do this action!','rmcommon'), 1);
        die();
    }
    
    $ids = exm_server_var($_POST, 'widget', array());
    
    if(empty($ids) || !is_array($ids)){
        redirectMsg('widgets.php', __('You must select at least one widget!','rmcommon'), 1);
        die();
    }
    
    $error = '';
    foreach ($ids as $id){
        $widget = new EXMWidget($id);
        // API: Before delete a widget
        $widget = EXMEventsApi::get()->run_event('exm_event_deleting_widget',$widget);
        if (!$widget->delete()) $error .= $widget->errors();
    }
    
    if ($errors!=''){
        redirectMsg('widgets.php', __('There was some errors:','rmcommon').$error, 1);
    } else {
        redirectMsg('widgets.php',__('Database updated successfully','global'), 0);
    }
    
}

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

switch($action){
    case 'save_position':
        save_position();
        break;
    case 'hide-widget':
        toggle_visibility(0);
        break;
    case 'show-widget':
        toggle_visibility(1);
        break;
    case 'delete-widgets':
        delete_widgets();
        break;
    case 'upload-widget':
        upload_widget();
        break;
    default:
        show_rm_blocks();
        break;
}