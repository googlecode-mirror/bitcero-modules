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
    
    echo "Error:".__('Session token invalid!','match');
    die();
    
}

$q = rmc_server_var($_GET, 'q', '');

$teams = MCHFunctions::all_teams(false, "name LIKE '%$q%'");

if($q!=''){
    
    foreach($teams as $team){
        echo $team['id_team'].'. '.$team['name']." <em>(".$team['category_object']['name'].")</em>\r\n";
    }
    
    die();
    
}

include RMTemplate::get()->get_template('mch_teams_field.php', 'module', 'match');