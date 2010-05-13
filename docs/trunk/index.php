<?php
// $Id$
// --------------------------------------------------------------
// Foros EXMBB
// Módulo para el manejo de Foros en EXM
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.xoopsmexico.net
// --------------------------------------------
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License as
// published by the Free Software Foundation; either version 2 of
// the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public
// License along with this program; if not, write to the Free
// Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
// MA 02111-1307 USA
// --------------------------------------------------------------
// @author: BitC3R0

define('BB_LOCATION','index');
include '../../mainfile.php';

if ($xoopsModuleConfig['showcats']){
    /**
    * Cargamos las categorías y los foros ordenados por categorías   
    */
    $xoopsOption['template_main'] = 'exmbb_index_categos.html';
    $xoopsOption['module_subpage'] = "index";
    include 'header.php';
    
    $categos = BBCategoryHandler::getObjects(1);
    
    foreach ($categos as $catego){
        if (!$catego->groupAllowed($xoopsUser ? $xoopsUser->getGroups() : array(0,XOOPS_GROUP_ANONYMOUS))) continue;
        
        $forums = BBForumHandler::getForums($catego->id(), 1, true);
        $ret = array(); 
        foreach ($forums as $forum){
        	$last = new BBPost($forum->lastPostId());
		    $lastpost = array();
		    if (!$last->isNew()){
    			$lastpost['date'] = BBFunctions::formatDate($last->date());
    			$lastpost['by'] = sprintf(_MS_EXMBB_BY, $last->uname());
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
    $xoopsOption['template_main'] = 'exmbb_index_forums.html';
    $xoopsOption['module_subpage'] = "index";
    include 'header.php';
    
    $fHand = new BBForumHandler();
    $forums = $fHand->getForums(0,1,true);
    foreach ($forums as $forum){
    	$last = new BBPost($forum->lastPostId());
		    $lastpost = array();
		    if (!$last->isNew()){
    			$lastpost['date'] = BBFunctions::formatDate($last->date());
    			$lastpost['by'] = sprintf(_MS_EXMBB_BY, $last->uname());
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

if ($user =& BBFunctions::getLastUser()){
    $tpl->assign('user', array('id'=>$user->uid(),'uname'=>$user->uname()));
}

unset($user);

// Usuarios Conectados
$tpl->assign('register_num', BBFunctions::getOnlineCount(1));
$tpl->assign('anonymous_num', BBFunctions::getOnlineCount(0));
$tpl->assign('total_users', BBFunctions::totalUsers());
$tpl->assign('total_topics', BBFunctions::totalTopics());
$tpl->assign('total_posts', BBFunctions::totalPosts());

$tpl->assign('lang_forum', _MS_EXMBB_FORUM);
$tpl->assign('lang_topics', _MS_EXMBB_TOPICS);
$tpl->assign('lang_posts', _MS_EXMBB_POSTS);
$tpl->assign('lang_lastpost', _MS_EXMBB_LASTPOST);
$tpl->assign('lang_lastuser', _MS_EXMBB_LASTUSER);
$tpl->assign('lang_regnum', _MS_EXMBB_REGNUM);
$tpl->assign('lang_annum', _MS_EXMBB_ANNUM);
$tpl->assign('lang_totalusers', _MS_EXMBB_TOTALUSERS);
$tpl->assign('lang_totaltopics', _MS_EXMBB_TOTALTOPICS);
$tpl->assign('lang_totalposts', _MS_EXMBB_TOTALPOSTS);

$tpl->assign('xoops_pagetitle', $xoopsModuleConfig['forum_title']);

BBFunctions::makeHeader();
BBFunctions::loadAnnouncements(0);

include 'footer.php';

?>
