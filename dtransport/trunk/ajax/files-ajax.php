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

    if ($xoopsSecurity->check())
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


    if (!$group->save()){
        $ret = array(
            'message' => __('Database updated successfully!','dtransport'),
            'name' => $name,
            'item' => $item
        );
        $functions->dt_send_message($item, 0, 1);
    }else{
        $functions->dt_send_message(__('Database could not be updated','stransport').'<br />',$group->errors(), 1, 1);
    }

}


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    case 'save-group':
        dt_save_download(0);
        break;
    case 'update-group':
        dt_save_download(1);
        break;
}
