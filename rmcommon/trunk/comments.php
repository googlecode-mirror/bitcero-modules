<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include_once '../../include/cp_header.php';
require_once XOOPS_ROOT_PATH.'/modules/rmcommon/admin_loader.php';
define('RMCLOCATION','comments');

function show_comments(){
    global $xoopsSecurity;
    
    $db = Database::getInstance();
    
    $keyw = rmc_server_var($_REQUEST, 'w', '');
    $filter = rmc_server_var($_REQUEST, 'filter', '');
    
    $sql = "SELECT COUNT(*) FROM ".$db->prefix("rmc_comments");
    $sql .= $keyw!='' || $filter!='' ? ' WHERE ' : '';
    $sql .= $keyw!='' ? "(content LIKE '%$keyw%' OR ip LIKE '%$keyw%')" : '';
    $sql .= $filter!='' ? ($keyw!=''?' AND':'')." status='$filter'" : '';
    /**
     * Paginacion de Resultados
     */
    $page = rmc_server_var($_GET, 'page', 1);
    $limit = 15;
    list($num) = $db->fetchRow($db->query($sql));
    
    $tpages = ceil($num / $limit);
    $page = $page > $tpages ? $tpages : $page;

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('comments.php?page={PAGE_NUM}');
    
    $sql = str_replace("COUNT(*)",'*', $sql);
    $sql .= " ORDER BY posted DESC LIMIT $start,$limit";
    $result = $db->query($sql);
    $comments = array();
    
    $ucache = array();
    $ecache = array();
        
    while($row = $db->fetchArray($result)){
        $com = new RMComment();
        $com->assignVars($row);
        
        // Editor data
        if(!isset($ecache[$com->getVar('user')])){
            $ecache[$com->getVar('user')] = new RMCommentUser($com->getVar('user'));
        }
            
        $editor = $ecache[$com->getVar('user')];
            
        if($editor->getVar('xuid')>0){
            
            if(!isset($ucache[$editor->getVar('xuid')])){
                $ucache[$editor->getVar('xuid')] = new XoopsUser($editor->getVar('xuid'));
            }
                
            $user = $ucache[$editor->getVar('xuid')];
                
            $poster = array(
                'id' => $user->getVar('uid'),
                'name'  => $user->getVar('uname'),
                'email' => $user->getVar('email'),
                'posts' => $user->getVar('posts'),
                'avatar'=> $user->getVar('user_avatar')!='' && $user->getVar('user_avatar')!='blank.gif' ? XOOPS_UPLOAD_URL.'/'.$user->getVar('user_avatar') : RMCURL.'/images/avatar.gif',
                'rank'  => $user->rank(),
            );
            
        } else {
                
            $poster = array(
                'id'    => 0,
                'name'  => $editor->getVar('name'),
                'email' => $editor->getVar('email'),
                'posts' => 0,
                'avatar'=> RMCURL.'/images/avatar.gif',
                'rank'  => ''
            );
                
        }
        
        // Get item
        $cpath = XOOPS_ROOT_PATH.'/modules/'.$row['id_obj'].'/class/'.$row['id_obj'].'controller.php';
        
        if(is_file($cpath)){
			if(!class_exists(ucfirst($row['id_obj']).'Controller'))
				include_once $cpath;
			
			$class = ucfirst($row['id_obj']).'Controller';
			$controller = new $class();
			$item = $controller->get_item($row['params'], $com);
			
        } else {
			
			$item = __('Unknow','rmcommon');
			
        }
        
        $comments[] = array(
            'id'        => $row['id_com'],
            'text'      => TextCleaner::getInstance()->clean_disabled_tags(TextCleaner::getInstance()->popuplinks(TextCleaner::getInstance()->nofollow($com->getVar('content')))),
            'poster'    => $poster,
            'posted'    => sprintf(__('Posted on %s'), formatTimestamp($com->getVar('posted'), 'l')),
            'ip'        => $com->getVar('ip'),
            'item'		=> $item,
            'status'	=> $com->getVar('status')
        );
    }
    
    $comments = RMEvents::get()->run_event('rmcommon.loading.admin.comments', $comments);
    
    xoops_cp_header();
    RMFunctions::create_toolbar();
    RMTemplate::get()->add_style('comms-admin.css', 'rmcommon');
    RMTemplate::get()->add_style('general.css', 'rmcommon');
    RMTemplate::get()->add_script('include/js/jquery.checkboxes.js');
    RMTemplate::get()->add_script('include/js/comments.js');
    $script = '<script type="text/javascript">delmes = "'.__('Do you really wish to delete this comment?','rmcommon').'";</script>';
    RMTemplate::get()->add_head($script);
    include RMTemplate::get()->get_template('rmc_comments.php','module','rmcommon');
    xoops_cp_footer();
    
}

/**
* Change comment status
* @param string status
*/
function set_comments_status($status){
	global $xoopsSecurity;
	
	if ($status!='waiting' && $status!='approved'){
		redirectMsg('comments.php', __('Invalid operation','rmcommon'), 1);
		die();
	}
	
	$coms = rmc_server_var($_POST, 'coms', array());
	$page = rmc_server_var($_POST, 'page', 1);
	$filter = rmc_server_var($_POST, 'filter', '');
	$w = rmc_server_var($_POST, 'w', '');
	
	$qs = "page=$page&filter=$filter&w=$w";
	
	if(!$xoopsSecurity->check()){
		redirectMsg('comments.php?'.$qs, __('Sorry, session token expired!','rmcommon'), 1);
		die();
	}
	
	if(!is_array($coms)){
		redirectMsg('comments.php?'.$qs, __('Unrecognized data!','rmcommon'), 1);
		die();
	}
	
	$db = Database::getInstance();
	$sql = "UPDATE ".$db->prefix("rmc_comments")." SET status='$status' WHERE id_com IN (".implode(",",$coms).")";
	
	if($db->queryF($sql)){
		
		RMEvents::get()->run_event('rmcommon.updated.comments',$coms, $status);
		
		redirectMsg('comments.php?'.$qs, __('Comments updated successfully!','rmcommon'), 0);
		die();
		
	} else {
		
		redirectMsg('comments.php?'.$qs, __('Errors occurrs while trying to update comments!','rmcommon'), 1);
		die();
		
	}
	
}

function delete_comments(){
	global $xoopsSecurity;
	
	$coms = rmc_server_var($_POST, 'coms', array());
	$page = rmc_server_var($_POST, 'page', 1);
	$filter = rmc_server_var($_POST, 'filter', '');
	$w = rmc_server_var($_POST, 'w', '');
	
	$qs = "page=$page&filter=$filter&w=$w";
	
	if(!$xoopsSecurity->check()){
		redirectMsg('comments.php?'.$qs, __('Sorry, session token expired!','rmcommon'), 1);
		die();
	}
	
	if(!is_array($coms)){
		redirectMsg('comments.php?'.$qs, __('Unrecognized data!','rmcommon'), 1);
		die();
	}
	
	// We need to delete each comment separated
	foreach ($coms as $id){
		$com = new RMComment($id);
		
		if($com->isNew()) continue;
		
		$cpath = XOOPS_ROOT_PATH.'/modules/'.$com->getVar('id_obj').'/class/'.$com->getVar('id_obj').'controller.php';
        
        if(!$com->delete()) return;
        
        if(is_file($cpath)){
			if(!class_exists(ucfirst($com->getVar('id_obj')).'Controller'))
				include_once $cpath;
			
			$class = ucfirst($com->getVar('id_obj')).'Controller';
			$controller = new $class();
			$item = $controller->reduce_comments_number($com);
			
        } else {
			
			$item = __('Unknow','rmcommon');
			
        }
		
	}
	
	redirectMsg('comments.php', __('Comments deleted successfully!','rmcommon'), 0);
	
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    case 'approve':
    	set_comments_status('approved');
    	break;
    case 'unapprove':
    	set_comments_status('waiting');
    	break;
    case 'delete':
    	delete_comments();
    	break;
    default:
        show_comments();
        break;
}