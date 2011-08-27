<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function rmc_bkcomments_show($options){
    
    $db = Database::getInstance();
    
    $sql = "SELECT * FROM ".$db->prefix("rmc_comments")." ORDER BY id_com DESC";
    $limit = $options[0]>0 ? $options[0] : 10;
    $sql .= " LIMIT 0,$limit";
    $result = $db->query($sql);
    $comments = array();
    
    $ucache = array();
    $ecache = array();
    $mods = array();
        
    while($row = $db->fetchArray($result)){
        $com = new RMComment();
        $com->assignVars($row);
        
        if($options[3]){
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
        }
        
        // Get item
        $cpath = XOOPS_ROOT_PATH.'/modules/'.$row['id_obj'].'/class/'.$row['id_obj'].'controller.php';
        
        if(is_file($cpath)){
			if(!class_exists(ucfirst($row['id_obj']).'Controller'))
				include_once $cpath;
			
			$class = ucfirst($row['id_obj']).'Controller';
			$controller = new $class();
			$item = $controller->get_item($row['params'], $com);
			$item_url = $controller->get_item_url($row['params'], $com);
        } else {
			
			$item = __('Unknow','rmcommon');
			$item_url = '';
			
        }
        
        if(isset($mods[$row['id_obj']])){
            $mod = $mods[$row['id_obj']];
        } else {
            $m = RMFunctions::load_module($row['id_obj']);
            $mod = $m->getVar('name');
            $mods[$row['id_obj']] = $mod;
        }
        
        $comments[] = array(
            'id'        => $row['id_com'],
            'text'      => TextCleaner::truncate(TextCleaner::getInstance()->clean_disabled_tags(TextCleaner::getInstance()->popuplinks(TextCleaner::getInstance()->nofollow($com->getVar('content')))), 50),
            'poster'    => isset($poster) ? $poster : null,
            'posted'    => formatTimestamp($com->getVar('posted'), 'l'),
            'item'		=> $item,
			'item_url'  => $item_url,
            'module'	=> $row['id_obj'],
            'status'	=> $com->getVar('status'),
            'module'    => $mod
        );
    }
    
    $comments = RMEvents::get()->run_event('rmcommon.loading.admin.comments', $comments);
    $block['comments'] = $comments;
    $block['show_module'] = $options[1];
    $block['show_name'] = $options[2];
    $block['show_user'] = $options[3];
    $block['show_date'] = $options[4];
    
    RMTemplate::get()->add_xoops_style('bk_comments.css', 'rmcommon');
    
    return $block;
    
}

function rmc_bkcomments_edit($options){
    
    $form = '</td></tr>';
    $form .= '<tr><td class="head">'.__('Number of Comments:','rmcommon').'</td><td class="odd">';
    $form .= '<input type="text" size="5" name="options[0]" value="'.$options[0].'" />';
    $form .= '<tr><td class="head">'.__('Show module name:','rmcommon').'</td><td class="odd">';
    $form .= '<label><input type="radio" name="options[1]" value="1"'.($options[1]==1?' checked="checked"':'').' />'.__('Yes','rmcommon').'</label>';
    $form .= '<label><input type="radio" name="options[1]" value="0"'.($options[1]==0?' checked="checked"':'').' />'.__('No','rmcommon').'</label></td></tr>';
    $form .= '<tr><td class="head">'.__('Show item name:','rmcommon').'</td><td class="odd">';
    $form .= '<label><input type="radio" name="options[2]" value="1"'.($options[2]==1?' checked="checked"':'').' />'.__('Yes','rmcommon').'</label>';
    $form .= '<label><input type="radio" name="options[2]" value="0"'.($options[2]==0?' checked="checked"':'').' />'.__('No','rmcommon').'</label></td></tr>';
    $form .= '<tr><td class="head">'.__('Show user name:','rmcommon').'</td><td class="odd">';
    $form .= '<label><input type="radio" name="options[3]" value="1"'.($options[3]==1?' checked="checked"':'').' />'.__('Yes','rmcommon').'</label>';
    $form .= '<label><input type="radio" name="options[3]" value="0"'.($options[3]==0?' checked="checked"':'').' />'.__('No','rmcommon').'</label></td></tr>';
    $form .= '<tr><td class="head">'.__('Show date:','rmcommon').'</td><td class="odd">';
    $form .= '<label><input type="radio" name="options[4]" value="1"'.($options[4]==1?' checked="checked"':'').' />'.__('Yes','rmcommon').'</label>';
    $form .= '<label><input type="radio" name="options[4]" value="0"'.($options[4]==0?' checked="checked"':'').' />'.__('No','rmcommon').'</label>';
    
    return $form;
    
}