<?php
// $Id$
// --------------------------------------------------------------
// Matches
// Module to publish and manage sports matches
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','role');
include 'header.php';

function m_show_roleplay(){
    global $xoopsModule, $xoopsModuleConfig, $xoopsSecurity;
    
    $champ = rmc_server_var($_REQUEST,'champ',0);
    $category = rmc_server_var($_REQUEST,'category',0);
    $team = rmc_server_var($_REQUEST,'team',0);
    $sday = rmc_server_var($_REQUEST,'sday',0);
    
    $db = Database::getInstance();
    
    $champs = MCHFunctions::all_championships();
    
    // Select role item
    if($champ>0 && $category>0){
        
        $sql = "SELECT * FROM ".$db->prefix("mch_role")." WHERE champ='".$champ."' AND category='".$category."'";
        if($team>0) $sql .= " AND (local='$team' OR visitor='$team')";
        if($sday>0) $sql .= " AND time<=$sday AND time>=".($sday-86400);
        $sql .= " ORDER BY `time`";
        $result = $db->query($sql);
        
        $role = array();
        $tcache = array();
        $fcache = array();
        $tf = new RMTimeFormatter('',__('%M% %d%, %Y% - %h%:%i%','match'));
        $i = 0;
        
        $days = array();
        $pday = 0;
        
        while($row = $db->fetchArray($result)){
            $item = new MCHRoleItem();
            $item->assignVars($row);
            
            if (isset($tcache[$item->getVar('local')])){
                $local = $tcache[$item->getVar('local')];
            } else {
                $tcache[$item->getVar('local')] = new MCHTeam($item->getVar('local'));
                $local = $tcache[$item->getVar('local')];
            }
            
            if (isset($tcache[$item->getVar('visitor')])){
                $visitor = $tcache[$item->getVar('visitor')];
            } else {
                $tcache[$item->getVar('visitor')] = new MCHTeam($item->getVar('visitor'));
                $visitor = $tcache[$item->getVar('visitor')];
            }
            
            if (isset($fcache[$item->getVar('field')])){
                $field = $fcache[$item->getVar('field')];
            } else {
                $fcache[$item->getVar('field')] = new MCHField($item->getVar('field'));
                $field = $fcache[$item->getVar('field')];
            }
            
            $role[$i] = array(
                'id' => $item->id(),
                'local' => array(
                    'id' => $local->id(),
                    'logo' => MCH_UP_URL.'/'.$local->getVar('logo'),
                    'name' => $local->getVar('name')
                ),
                'visitor' => array(
                    'id' => $visitor->id(),
                    'logo' => MCH_UP_URL.'/'.$visitor->getVar('logo'),
                    'name' => $visitor->getVar('name')
                ),
                'date' => $tf->format($item->getVar('time')),
                'hour' => $tf->format($item->getVar('time'), '%h%:%i%'),
                'time' => $item->getVar('time'),
                'field' => array(
                    'id' => $field->id(),
                    'name' => $field->getVar('name')
                ),
                'past' => $item->getVar('time')<time()?true:false
            );
            
            if($role[$i]['past']){
                $score = new MCHScoreItem();
                $score->byRole($item->id());
                $role[$i]['local']['score'] = $score->getVar('local');
                $role[$i]['visitor']['score'] = $score->getVar('visitor');
            }
            
            // Add days to combo
            if($pday<=0){
                $pday = mktime(0, 0, 1, date("m",$item->getVar('time')), date("d", $item->getVar('time')), date('Y', $item->getVar('time')));
                $days[] = $pday;
            }
            
            $now = mktime(23, 59, 0, date("m",$item->getVar('time')), date("d", $item->getVar('time')), date('Y', $item->getVar('time')));
            if($now>$pday+(86400)){
                $pday = $now;
                $days[] = $pday;
            }
            
            $i++;
            
        }
        
    }
    
    // Charge days if incomplete
    if($champ>0 && $category>0 && $sday>0){
        
        $sql = "SELECT * FROM ".$db->prefix("mch_role")." WHERE champ='".$champ."' AND category='".$category."'";
        if($team>0) $sql .= " AND (local='$team' OR visitor='$team')";
        $sql .= " ORDER BY `time`";
        $result = $db->query($sql);
        
        $days = array();
        $pday = 0;
        while($row = $db->fetchArray($result)){
            $item = new MCHRoleItem();
            $item->assignVars($row);
            // Add days to combo
            if($pday<=0){
                $pday = mktime(0, 0, 1, date("m",$item->getVar('time')), date("d", $item->getVar('time')), date('Y', $item->getVar('time')));
                $days[] = $pday;
            }
            
            $now = mktime(23, 59, 0, date("m",$item->getVar('time')), date("d", $item->getVar('time')), date('Y', $item->getVar('time')));
            if($now>$pday+(86400)){
                $pday = $now;
                $days[] = $pday;
            }
        }
        
    }
    
    // Categories
    $categories = array();
    MCHFunctions::categories_tree($categories);
    
    // Teams
    $teams = MCHFunctions::all_teams(false,'category='.$category);
    
    // Fields
    $fields = MCHFunctions::all_fields();
    
    // Date field
    $form = new RMForm('','','');
    $datetime = new RMFormDate('', 'date', '', '', 1);
    $datetime->options('stepMinute: 15');
    
    MCHFunctions::toolbar();
    xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; ".__('Role Play','match'));
    RMTemplate::get()->assign('xoops_pagetitle', __('Coaches','match'));
    RMTemplate::get()->add_style('admin.css', 'match');
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
    RMTemplate::get()->add_local_script('admin_match.js','match');
    RMTemplate::get()->add_head("<script type='text/javascript'>\nvar mch_message='".__('Do you really want to delete selected items?','match')."';\n
        var mch_select_message = '".__('You must select some role item before to execute this action!','match')."';</script>");
    xoops_cp_header();
    
    $match_extra_options = RMEvents::get()->run_event('match.more.options');
    
    include RMTemplate::get()->get_template("admin/mch_roleplay.php", 'module', 'match');
    xoops_cp_footer();
    
}

function m_save_roleitem($edit=0){
    global $xoopsModule, $xoopsModuleConfig, $xoopsSecurity;
    
    $query = '';
    foreach ($_POST as $k => $v){
        $$k = $v;
        if ($k == 'XOOPS_TOKEN_REQUEST' || $k=='action' || $k=='sbt') continue;
        $query .= $query=='' ? "$k=".urlencode($v) : "&$k=".urlencode($v);
    }
    
    $action = $edit ? '?action=edit&id='.$id : '?';
    
    if (!$xoopsSecurity->check()){
        redirectMsg('role.php?action='.($edit ? 'edit&id='.$id : 'new').'&'.$query, __('Session token expired!','match'), 1);
        die();
    }
    
    if($local<=0 || $visitor<=0 || $field<=0 || $date<=0){
        redirectMsg('role.php?action='.($edit ? 'edit&id='.$id : 'new').'&'.$query, __('Please fill all required data!','match'), 1);
    }
    
    if ($edit){
        
        if ($id<=0){
            redirectMsg('./role.php',__('Role item ID not valid!','match'),1);
            die();
        }

        //Verificamos que el trabajo exista
        $item = new MCHRoleItem($id);
        if ($item->isNew()){
            redirectMsg('./role.php',__('Specified role item does not exists!','match'),1);
            die();
        }
        
        
    }else{
        $item = new MCHRoleItem();
    }
    
    $db = Database::getInstance();
    // Check if work exists already
    if ($edit){
        $sql = "SELECT COUNT(*) FROM ".$db->prefix("mch_role")." WHERE time='$time' AND champ='".$champ."' AND id_role<>'$id' AND (local='".$local."' OR visitor='".$visitor."')";
    } else {
        $sql = "SELECT COUNT(*) FROM ".$db->prefix("mch_role")." WHERE time='$time' AND champ='".$champ."' AND (local='".$local."' OR visitor='".$visitor."')";
    }
    list($num)=$db->fetchRow($db->query($sql));
    if ($num>0){
        redirectMsg("fields.php".$action.$query, __('A field with same name already exists!','match'), 1);
        die();
    }

    $item->setVar('local', $local);
    $item->setVar('visitor', $visitor);
    $item->setVar('time', $date);
    $item->setVar('field', $field);
    $item->setVar('champ', $champ);
    $item->setVar('category', $category);
    
    
    if (!$item->save()){
        redirectMsg('./role.php'.$action.$query,__('Errors ocurred while trying to update database!','match').$item->errors(),1);
        die();
    }else{    
        redirectMsg('./role.php?champ='.$champ.'&category='.$category.'&id='.$item->id(),__('Role item saved successfully!','match'),0);
        die();
    }
    
}




$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    
    case 'saverole':
        m_save_roleitem();
        break;
    case 'saveedit':
        m_save_roleitem(1);
        break;
    case 'delete':
        m_delete_roles();
        break;
    default:
        m_show_roleplay();
        break;
    
}
