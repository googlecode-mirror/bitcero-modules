<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include '../admin/header.php';

global $xoopsLogger;
error_reporting(0);
$xoopsLogger->renderingEnabled = false;
$xoopsLogger->activated = false;

$functions = new DTFunctions();
$db = XoopsDatabaseFactory::getDatabaseConnection();

/**
 * Almacena los datos del grupo en la base de datos
 **/
function dt_save_group($edit=0){
    global $xoopsSecurity, $functions, $db;

    foreach ($_POST as $k=>$v){
        $$k=$v;
    }

    if (!$xoopsSecurity->check())
        $functions->dt_send_message(__('Session token not valid!','dtransport'), 1, 0);

    //Verificamos si el software es válido
    if ($item<=0)
        $functions->dt_send_message(__('Download item ID has not been specified!','dtransport'), 1, 1);

    //Verificamos si existe el software
    $sw = new DTSoftware($item);
    if ($sw->isNew())
        $functions->dt_send_message(__('Specified item does not exists!','dtransport'), 1, 1);

    if ($edit){
        //Verificamos si grupo es válido
        if ($id<=0)
            $functions->dt_send_message(__('A group ID has not been specified!','dtransport'), 1, 1);

        //Verificamos si el grupo existe
        $group = new DTFileGroup($id);
        if ($group->isNew())
            $functions->dt_send_message(__('Specified group does not exists!','dtransport'), 1, 1);

        //Verificamos si existe el nombre del grupo
        $sql = "SELECT COUNT(*) FROM ".$db->prefix('dtrans_groups')." WHERE name='".$name."' AND id_soft=".$item." AND id_group<>".$group->id();
        list($num) = $db->fetchRow($db->queryF($sql));

    }else{

        //Verificamos si existe el nombre del grupo
        $sql = "SELECT COUNT(*) FROM ".$db->prefix('dtrans_groups')." WHERE name='".$name."' AND id_soft=".$item;
        list($num) = $db->fetchRow($db->queryF($sql));

        $group = new DTFileGroup();
    }

    if ($num>0)
        $functions->dt_send_message(__('Another group with same name exists already!','dtransport'), 1, 1);

    $group->setName($name);
    $group->setSoftware($item);


    if ($group->save()){
        $ret = array(
            'message' => __('Database updated successfully!','dtransport'),
            'name'  => $name,
            'item'  => $item,
            'id'    => $group->id(),
            'action'=> $edit?'edit':'create'
        );
        $functions->dt_send_message($ret, 0, 1);
    }else{
        $functions->dt_send_message(__('Database could not be updated','dtransport').'<br />',$group->errors(), 1, 1);
    }

}

/**
 * Delete files from hard disk
 */
function dt_delete_hfile(){
    global $xoopsSecurity, $functions;

    $file = rmc_server_var($_POST, 'file', '');
    $secure = rmc_server_var($_POST, 'secure', 0);

    if(!$xoopsSecurity->check())
        $functions->dt_send_message(__('Session token invalid!','dtransport'), 1, 0);

    $rmu = RMUtilities::get();
    $mc = $rmu->module_config('dtransport');

    if($secure)
        $dir = rtrim($mc['directory_secure'],'/');
    else
        $dir = rtrim($mc['directory_insecure'],'/');


    if(!is_file($dir.'/'.$file))
        $functions->dt_send_message(array('message'=>__('Specified file does not exists!','dtransport')), 0, 1);

    if(!unlink($dir.'/'.$file))
        $functions->dt_send_message(sprintf(__('File %s could not be deleted! Please try again.','dtransport'), $file), 1, 1);

    $ret = array(
        'message' => sprintf(__('File %s was deleted successfully!','dtransport'), $file)
    );

    $functions->dt_send_message($ret, 0, 1);

}

function dt_get_identifier(){
    global $functions, $xoopsUser;

    $id = rmc_server_var($_POST, 'identifier', '');

    if($id=='')
        $functions->dt_send_message(__('Identifier could not be verified!','dtransport'),1 , 0);

    $tc = TextCleaner::getInstance();
    $data = explode("|", $tc->decrypt($id));

    $rmu = RMFunctions::get();

    if(session_id()!=$data[0] && $xoopsUser->uid()!=$data[1] || !$xoopsUser->isAdmin($rmu->load_module('dtransport')))
        $functions->dt_send_message(__('Critical error!','dtransport'), 1, 0);

    $ret = array('message'=>__('Verified','dtransport'));
    $functions->dt_send_message($ret, 0, 1);
}


/**
 * Save a new or edited file to database
 */
function dt_save_file($edit = 0){
    global $xoopsSecurity, $functions, $db;

    foreach ($_POST as $k=>$v){
        $$k=$v;
    }

    if (!$xoopsSecurity->check())
        $functions->dt_send_message(__('Session token not valid!','dtransport'), 1, 0);

    //Verificamos si el software es válido
    if ($item<=0)
        $functions->dt_send_message(__('Item download ID not provided!','dtransport'), 1, 1);

    //Verificamos si existe el software
    $sw = new DTSoftware($item);
    if ($sw->isNew())
        $functions->dt_send_message(__('Specified item download does nto exists!','dtransport'), 1, 1);

    if ($edit){

        //Verificamos si archivo es válido
        if ($id<=0)
            $functions->dt_send_message(__('File ID has not been provided!','dtransport'), 1, 1);

        //Verificamos si existe archivo
        $fl = new DTFile($id);
        if ($fl->isNew())
            $functions->dt_send_message(__('Specified file does not exists!','dtransport'), 1, 1);

        // Si es un archivo remoto eliminamos el archivo actual
        if(!$fl->remote() && $remote){
            $rmu = RMUtilities::get();
            $mc = $rmu->module_config('dtransport');
            $dir = $sw->getVar('secure') ? $mc['directory_secure'] : $mc['directory_insecure'];

            if(file_exists($dir.'/'.$fl->file()))
                unlink($dir.'/'.$fl->file());

            unset($dir, $mc, $rmu);
        }

    }else{

        $fl=new DTFile();

    }

    $fl->setSoftware($item);
    $fl->setRemote($remote);
    $fl->setFile($file);
    $fl->setDefault($default);
    $fl->setGroup($group);
    $fl->setDate(time());
    $fl->setTitle(trim($title));
    $fl->setMime($mime);
    $fl->setSize($size);

    if (!$fl->save()){
        $functions->dt_send_message(__('File could not be saved!','dtransport').'<br />'.$fl->errors(), 1, 1);
    }else{

        if($fl->isDefault())
            $db->queryF("UPDATE ".$db->prefix("dtrans_files")." SET `default`=0 WHERE id_soft=".$sw->id()." AND id_file !=".$fl->id());


        $ret = array('message'=>__('File saved successfully!','dtransport'));
        showMessage(sprintf(__('File %s saved successfully!','dtransport'), $fl->title()), RMMSG_SAVED);
        $functions->dt_send_message($ret, 0, 1);

    }
}


function dt_reasign_file(){
    global $xoopsSecurity, $functions;

    if(!$xoopsSecurity->check())
        $functions->dt_send_message(__('Session token not valid!','dtransport'), 1, 0);

    $id = rmc_server_var($_POST, 'id', 0);
    $idgroup = rmc_server_var($_POST, 'idgroup', 0);
    $item = rmc_server_var($_POST, 'item', 0);

    $file = new DTFile($id);
    if($file->isNew())
        $functions->dt_send_message(__('Specified file does not exists!','dtransport'), 1, 1);

    if($idgroup>0){
        $group = new DTFileGroup($idgroup);
        if($group->isNew())
            $functions->dt_send_message(__('Specified group does not exists!','dtransport'), 1, 1);
    }

    if($file->software()!=$item)
        $functions->dt_send_message(__('This file seems not belong to specified download item!','dtransport'),1, 1);

    $file->setGroup($idgroup);

    if($file->save())
        $functions->dt_send_message(array('message'=>__('File reasigned successfully!','dtransport')), 0, 1);
    else
        $functions->dt_send_message(__('File could not be reasigned!','dtransport'), 1, 1);

}

/**
 * Elimina archivos de la base de datos y el disco duro
 */
function dt_delete_file(){
    global $xoopsSecurity, $functions;

    if(!$xoopsSecurity->check())
        $functions->dt_send_message(__('Session token not valid!','dtransport'), 1, 0);

    $id = rmc_server_var($_POST, 'id', 0);
    $item = rmc_server_var($_POST, 'item', 0);

    $file = new DTFile($id);
    if($file->isNew())
        $functions->dt_send_message(__('Specified file does not exists!','dtransport'), 1, 1);

    $sw = new DTSoftware($item);
    if ($sw->isNew())
        $functions->dt_send_message(__('Specified item download does nto exists!','dtransport'), 1, 1);

    if($file->software()!=$item)
        $functions->dt_send_message(__('This file seems not belong to specified download item!','dtransport'),1, 1);

    if(!$file->delete())
        $functions->dt_send_message(__('File could not be deleted!','dtransport'), 1, 1);

    $rmu = RMUtilities::get();
    $mc = $rmu->module_config('dtransport');

    $dir = $sw->getVar('secure') ? $mc['directory_secure'] : $mc['directory_insecure'];

    unlink($dir.'/'.$file->file());

    $functions->dt_send_message(__('File deleted successfully!','dtransport'), 0, 1);

}


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    case 'save-group':
        dt_save_group(0);
        break;
    case 'update-group':
        dt_save_group(1);
        break;
    case 'delete_hfile':
        dt_delete_hfile();
        break;
    case 'delete-file':
        dt_delete_file();
        break;
    case 'identifier':
        dt_get_identifier();
        break;
    case 'save-file':
        dt_save_file(0);
        break;
    case 'save-edit':
        dt_save_file(1);
        break;
    case 'reasign-file':
        dt_reasign_file();
        break;
}
