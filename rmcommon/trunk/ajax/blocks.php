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
    foreach($blocks as $idb => $bk){
        if($idb==$id){
            $found = true;
            break;
        }
    }
    
    if(!$found){
        response(array('message'=>__('The specified block does not exists, please verify your selection.','rmcommon')), 1, 1);
    }
    
    $block = new RMBlock();

    if ($canvas<=0){
        $db = Database::getInstance();
        // Get a default side
        $sql = "SELECT id_position, name FROM ".$db->prefix("rmc_blocks_positions")." ORDER BY id_position LIMIT 0, 1";
        $result = $db->query($sql);
        if ($result)
            list($canvas, $canvas_name) = $db->fetchRow($result);
    }
    
    $block->setVar('name', $bk['name']);
    $block->setVar('element', $mod);
    $block->setVar('element_type', 'module');
    $block->setVar('canvas', $canvas);
    $block->setVar('visible', 1);
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
    global $xoopsSecurity;
    
    if (!$xoopsSecurity->check()){
        response(array('message'=>__('Sorry, you are not allowed to view this page','rmcommon')), 1, 0);
        die();
    }
    
    $id = rmc_server_var($_POST, 'block', 0);
    $mod = rmc_server_var($_POST, 'module', '');
    
    if($id<=0){
        response(array('message'=>__('The block that you specified seems to be invalid. Please try again', 'rmcommon')), 1, 1);
    }
    
    $block = new RMBlock($id);
    if($block->isNew()){
        response(array('message'=>__('The block that you specified does not exists!. Please try again', 'rmcommon')), 1, 1);
    }
    
    $positions = RMBlocksFunctions::block_positions();
    $form = new RMForm('','','');
    $canvas = new RMFormModules('', 'bk_mod', 1, 1, array(), 3, null, false, 1);
    
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


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    case 'insert':
        insertBlock();
        break;
    case 'settings':
        configure_block();
        break;
}