<?php
// $Id$
// --------------------------------------------------------------
// Matches
// Module to publish and manage sports matches
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include '../admin/header.php';

// Deactivate the logger
error_reporting(0);
$xoopsLogger->activated = false;

if(!$xoopsSecurity->checkReferer()){
    
    response_error(__('Session token invalid!','match'));
    die();
    
}

function response_error($msg){
    
    $ret = array(
        'msg' => $msg,
        'error' => 1
    );
    
    echo json_encode($ret);
    die();
    
}

function m_send_team_data(){
    
    $id = rmc_server_var($_GET, 'id', 0);
    if($id<=0){
        response_error(__('No team id has been provided!','match'));
    }
    
    $team = new MCHTeam($id);
    if($team->isNew())
        response_error(__('Specified team does not exists', 'match'));
    
    $ret = array(
        'id'    => $team->id(),
        'name'  => $team->getVar('name'),
        'logo'  => MCH_UP_URL.'/'.$team->getVar('logo')
    );
    
    echo json_encode($ret);
    die();
    
}


function m_send_score(){
    
    $id = rmc_server_var($_GET,'id',0);
    if($id<=0){
        response_error(__('No role item has been specified!','match'));
    }
    
    $item = new MCHRoleItem($id);
    if($item->isNew())
        response_error(__('Specified role item does not exists', 'match'));
    
    $score = new MCHScoreItem();
    $score->byRole($item->id());
    
    $local = new MCHTeam($item->getVar('local'));
    $visitor = new MCHTeam($item->getVar('visitor'));
    
    include RMTemplate::get()->get_template('other/mch_score_editor.php', 'module', 'match');
    die();
    
}

$action = rmc_server_var($_REQUEST, 'action', '');
switch($action){
    case 'team-info':
        m_send_team_data();
        break;
    case 'get-score':
        m_send_score();
        break;
}