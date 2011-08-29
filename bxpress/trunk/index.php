<?php
// $Id$
// --------------------------------------------------------------
// bXpress Forums
// An simple forums module for XOOPS and Common Utilities
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include '../../mainfile.php';

if ($xoopsModuleConfig['showcats']){
    /**
    * Cargamos las categorías y los foros ordenados por categorías   
    */
    $xoopsOption['template_main'] = 'bxpress_index_categos.html';
    $xoopsOption['module_subpage'] = "index";
    include 'header.php';
    
    $categos = bXCategoryHandler::getObjects(1);
    
    foreach ($categos as $catego){
        if (!$catego->groupAllowed($xoopsUser ? $xoopsUser->getGroups() : array(0,XOOPS_GROUP_ANONYMOUS))) continue;
        
        $forums = bXForumHandler::getForums($catego->id(), 1, true);
        $ret = array(); 
        foreach ($forums as $forum){
        	$last = new bXPost($forum->lastPostId());
		    $lastpost = array();
		    if (!$last->isNew()){
    			$lastpost['date'] = bXFunctions::formatDate($last->date());
    			$lastpost['by'] = sprintf(__('by %s','bxpress'), $last->uname());
    			$lastpost['id'] = $last->id();
    			$lastpost['topic'] = $last->topic();
    			if ($xoopsUser){
    				$lastpost['new'] = $last->date()>$xoopsUser->last_login() && (time()-$last->date()) < $xoopsModuleConfig['time_new'];
    			} else {
    				$lastpost['new'] = (time()-$last->date())<=$xoopsModuleConfig['time_new'];
				}
			}
            $ret[] = array('id'=>$forum->id(),'idname'=>$forum->friendName(),
                    'name'=>$forum->name(), 'desc'=>$forum->description(),'topics'=>$forum->topics(),
                    'posts'=>$forum->posts(),'link'=>$forum->makeLink(1),'last'=>$lastpost);
        }
        unset($forums);   
        
        $tpl->append('categos', array('id'=>$catego->id(), 'title'=>$catego->title(), 'forums'=>$ret));
    }
       
} else {
    /**
    * Cargamos solo los foros
    */
    $xoopsOption['template_main'] = 'bxpress_index_forums.html';
    $xoopsOption['module_subpage'] = "index";
    include 'header.php';
    
    $fHand = new bXForumHandler();
    $forums = $fHand->getForums(0,1,true);
    foreach ($forums as $forum){
    	$last = new bXPost($forum->lastPostId());
		    $lastpost = array();
		    if (!$last->isNew()){
    			$lastpost['date'] = bXFunctions::formatDate($last->date());
    			$lastpost['by'] = sprintf(__('by %s','bxpress'), $last->uname());
    			$lastpost['id'] = $last->id();
    			$lastpost['topic'] = $last->topic();
    			if ($xoopsUser){
    				$lastpost['new'] = $last->date()>$xoopsUser->last_login() && (time()-$last->date()) < $xoopsModuleConfig['time_new'];
    			} else {
    				$lastpost['new'] = (time()-$last->date())<=$xoopsModuleConfig['time_new'];
				}
			}
        $tpl->append('forums', array('id'=>$forum->id(),'idname'=>$forum->friendName(),
                'name'=>$forum->name(), 'desc'=>$forum->description(),'topics'=>$forum->topics(),
                'posts'=>$forum->posts(),'link'=>$forum->makeLink(),'last'=>$lastpost));
    }
    
}

$user = bXFunctions::getLastUser();

if ($user){
    $tpl->assign('user', array('id'=>$user->uid(),'uname'=>$user->uname()));
}

unset($user);

// Usuarios Conectados
$tpl->assign('register_num', bXFunctions::getOnlineCount(1));
$tpl->assign('anonymous_num', bXFunctions::getOnlineCount(0));
$tpl->assign('total_users', bXFunctions::totalUsers());
$tpl->assign('total_topics', bXFunctions::totalTopics());
$tpl->assign('total_posts', bXFunctions::totalPosts());

$tpl->assign('lang_forum', _MS_bxpress_FORUM);
$tpl->assign('lang_topics', _MS_bxpress_TOPICS);
$tpl->assign('lang_posts', _MS_bxpress_POSTS);
$tpl->assign('lang_lastpost', _MS_bxpress_LASTPOST);
$tpl->assign('lang_lastuser', _MS_bxpress_LASTUSER);
$tpl->assign('lang_regnum', _MS_bxpress_REGNUM);
$tpl->assign('lang_annum', _MS_bxpress_ANNUM);
$tpl->assign('lang_totalusers', _MS_bxpress_TOTALUSERS);
$tpl->assign('lang_totaltopics', _MS_bxpress_TOTALTOPICS);
$tpl->assign('lang_totalposts', _MS_bxpress_TOTALPOSTS);

$tpl->assign('xoops_pagetitle', $xoopsModuleConfig['forum_title']);

bXFunctions::makeHeader();
bXFunctions::loadAnnouncements(0);

include 'footer.php';
