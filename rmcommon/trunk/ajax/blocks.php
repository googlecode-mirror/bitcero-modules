<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include '../../../mainfile.php';

// Deactivate the logger
error_reporting(0);
$xoopsLogger->activated = false;

function response($data, $error=0, $token=0){
    global $xoopsSecurity;
    
    if($token)
        $data = array_merge($data, array('token'=>$xoopsSecurity->createToken()));
    
    $data = array_merge($data, array('error'=>$error));
        
    echo json_encode($data);
    die();
    
}

// Check Security settings
if(!$xoopsSecurity->checkReferer(1)){
    response(array('message'=>__('Operation not allowed!','rmcommon')), 1, 0);
    die();
}


/**
* This function allos to insert a new block in database
*/
function insertBlock(){
    global $xoopsSecurity;
    
    $mod = rmc_server_var($_POST, 'module', '');
    $id = rmc_server_var($_POST, 'block', '');
    $token = rmc_server_var($_POST, 'XOOPS_TOKEN_REQUEST', '');
    $canvas = rmc_server_var($_POST, 'canvas', '');

    if (!$xoopsSecurity->check()){
        response(array('message'=>__('Sorry, you are not allowed to view this page','rmcommon')), 1, 0);
        die();
    }

    if($mod=='' || $id==''){
        
        $data = array(
            'message' => __('The block specified seems to be invalid. Please try again.','rmcommon')
        );
        
        response($data, 1, 0);
        
    }
    $module = RMFunctions::load_module($mod);
    if(!$module){
        response(array('message'=>__('The specified module does not exists!','rmcommon')), 1, 0);
    }

    $module->loadInfoAsVar($mod);
    $blocks = $module->getInfo('blocks');
    $ms = $module->name().'<br />';
    $found = false;
    foreach($blocks as $bk){
        $str = isset($bk['show_func']) ? $bk['show_func'] : '';
        $str .= isset($bk['edit_func']) ? $bk['edit_func'] : '';
        $str .= isset($bk['dir']) ? $bk['dir'] : $mod;
        $idb = md5($str);
        if($idb==$id){
            $found = true;
            break;
        }
    }
    
    if(!$found){
        response(array('message'=>__('The specified block does not exists, please verify your selection.','rmcommon')), 1, 1);
    }
    
    $block = new RMInternalBlock();

    if ($canvas<=0){
        $db = Database::getInstance();
        // Get a default side
        $sql = "SELECT id_position, name FROM ".$db->prefix("rmc_blocks_positions")." ORDER BY id_position LIMIT 0, 1";
        $result = $db->query($sql);
        if ($result)
            list($canvas, $canvas_name) = $db->fetchRow($result);
    }
    
    $block->setReadGroups(array(0));
    $block->setVar('name', $bk['name']);
    $block->setVar('element', $mod);
    $block->setVar('element_type', $bk['plugin']==1 ? 'plugin' : 'module');
    $block->setVar('canvas', $canvas);
    $block->setVar('visible', 0);
    $block->setVar('type', 'normal');
    $block->setVar('isactive', 1);
    $block->setVar('dirname', isset($bk['dir']) ? $bk['dir'] : $mod);
    $block->setVar('file', $bk['file']);
    $block->setVar('show_func', $bk['show_func']);
    $block->setVar('edit_func', $bk['edit_func']);
    $block->setVar('description', $bk['description']);
    $block->setVar('widget', $id);
    $block->setVar('options', is_array($bk['options']) ? serialize($bk['options']) : serialize(explode("|", $bk['options'])));
    $block->setVar('template', $bk['template']);
    $block->sections(array(0));
    
    if(!$block->save()){
        
        response(array('message'=>sprintf(__('Block could not be created due to: %s. Please try again!', 'rmcommon'), $block->errors())), 1, 1);
        
    }
    
    RMEvents::get()->run_event('rmcommon.block.added', $block);
    
    $pos = RMBlocksFunctions::block_positions();
    
    $ret = array(
        'id' => $block->id(),
        'name' => $block->getVar('name'),
        'module' => $block->getVar('element'),
        'description' => $block->getVar('description'),
        'canvas' => $pos[$canvas],
        'weight' => $block->getVar('weight'),
        'message' => __('Block added successfully! Please configure it.','rmcommon')
    );
    
    response($ret, 0, 1);
    die();
}

/**
* Return the form to configure blocks
*/
function configure_block(){
    global $xoopsSecurity, $xoopsLogger;
    
    if (!$xoopsSecurity->check()){
        response(array('message'=>__('Sorry, you are not allowed to view this page','rmcommon')), 1, 0);
        die();
    }
    
    $id = rmc_server_var($_POST, 'block', 0);
    $mod = rmc_server_var($_POST, 'module', '');
    
    if($id<=0){
        response(array('message'=>__('The block that you specified seems to be invalid. Please try again', 'rmcommon')), 1, 1);
    }
    
    $block = new RMInternalBlock($id);
    if($block->isNew()){
        response(array('message'=>__('The block that you specified does not exists!. Please try again', 'rmcommon')), 1, 1);
    }
    
    $positions = RMBlocksFunctions::block_positions();
    $form = new RMForm('','','');
    $canvas = new RMFormModules('', 'bk_mod', 1, 1, $block->sections(), 3, null, false, 1);
    
    // Groups
    $groups = new RMFormGroups('', 'bk_groups', true, 1, 3, $block->readGroups());
    
    $block_options = $block->getOptions();
    
    ob_start();
    include RMTemplate::get()->get_template('rmc_block_form.php', 'module', 'rmcommon');
    $form = ob_get_clean();
    
    $ret = array(
        'id'=>$block->id(),
        'content'=>$form,
    );
    response($ret, 0, 1);
    
    die();
    
}

function save_block_config(){
    global $xoopsSecurity;
    
    foreach($_POST as $k => $v){
        $$k = $v;
    }
    
    if(!$xoopsSecurity->check($XOOPS_TOKEN_REQUEST)){
        response(array('message'=>__('Session token expired. Please try again.','rmcommon')), 1, 0);
        die();
    }
    
    if($bid<=0){
        response(array('message'=>__('You must provide a block ID!','rmcommon')), 1, 1);
        die();
    }
    
    $block = new RMInternalBlock($bid);
    if($block->isNew()){
        response(array('message'=>__('Specified block does not exists!','rmcommon')), 1, 1);
        die();
    }
    
    if(isset($options)) $block->setVar('options', serialize($options));
    $block->setVar('name', $bk_name);
    $block->setVar('canvas', $bk_pos);
    $block->setVar('weight', $bk_weight);
    $block->setVar('visible', $bk_visible);
    $block->setVar('bcachetime', $bk_cache);
    
    // Set modules
    $block->sections($bk_mod);
    // Set Groups
    $block->setReadGroups($bk_groups);
    
    if($block->save()){
        response(array(
            'message' => __('Block updated successfully!','rmcommon')
        ), 0, 1);
    }
    
    die();
    
}


function save_block_position(){
    global $xoopsSecurity;
    
    if(!$xoopsSecurity->check($XOOPS_TOKEN_REQUEST)){
        response(array('message'=>__('Session token expired. Please try again.','rmcommon')), 1, 0);
        die();
    }
    
    $id = rmc_server_var($_POST, 'id', 0);
    $name = rmc_server_var($_POST, 'name', '');
    $tag = rmc_server_var($_POST, 'tag', '');
    $active = rmc_server_var($_POST, 'active', 1);
    
    if($id<=0){
        response(array('message'=>__('Specified position is not valid!','rmcommon')), 1, 1);
        die();
    }
    
    if($name==''||$tag==''){
        response(array('message'=>__('You must fill name and tag input fields!','rmcommon')), 1, 1);
        die();
    }
    
    $pos = new RMBlockPosition($id);
    if($pos->isNew()){
        response(array('message'=>__('Specified block position does not exists!','rmcommon')), 1, 1);
        die();
    }
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $sql = "SELECT COUNT(*) FROM ".$db->prefix("rmc_blocks_positions")." WHERE (name='$name' OR tag='$tag') AND id_position<>$id";
    
    list($num) = $db->fetchRow($db->query($sql));
    
    if($num>0){
        response(array('message'=>__('Already exists another block position with same name or tag!','rmcommon')), 1, 1);
        die();
    }
    
    $pos->setVar('name', $name);
    $pos->setVar('tag', $tag);
    $pos->setVar('active', $active);
    
    if($pos->save()){
        response(array('message'=>__('Changes saved successfully!','rmcommon')), 0, 1);
        die();
    } else {
        response(array('message'=>__('Changes could not be saved!','rmcommon')), 1, 1);
        die();
    }
    
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    case 'insert':
        insertBlock();
        break;
    case 'settings':
        configure_block();
        break;
    case 'saveconfig':
        save_block_config();
        break;
    case 'savepos':
        save_block_position();
        break;
}