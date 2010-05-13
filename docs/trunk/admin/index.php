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
// @copyright: 2007 - 2008 Red México

define('BB_LOCATION','index');
include 'header.php';

$url = "http://redmexico.com.mx/modules/vcontrol/?id=3";

$cHead = "<script type='text/javascript'>
			var url = '".XOOPS_URL."/include/proxy.php?url=' + encodeURIComponent('$url');
         	new Ajax.Updater('versionInfo',url);
		 </script>\n";
$cHead .= '<link href="../styles/admin.css" media="all" rel="stylesheet" type="text/css" />';
xoops_cp_header($cHead);

$adminTemplate = "admin/forums_index.html";

// Presentamos las opciones principales del Módulo

//Página de inicio de usuarios
$tpl->append('options', array('text'=>_AS_BB_INDEX, 'info'=>_AS_BB_CLICK,
		'link'=>'../','icon'=>'../images/home48.png'));

//Categorías
$sql = "SELECT COUNT(*) FROM ".$db->prefix("exmbb_categories");
list($num) = $db->fetchRow($db->query($sql));
$tpl->append('options', array('text'=>_AS_BB_CATEGOS, 'info'=>sprintf(_AS_BB_CATEGOSNUM, $num),
		'link'=>'categos.php','icon'=>'../images/catego48.png'));
//Foros
$sql = "SELECT COUNT(*) FROM ".$db->prefix("exmbb_forums");
list($num) = $db->fetchRow($db->query($sql));
$tpl->append('options', array('text'=>_AS_BB_FORUMS, 'info'=>sprintf(_AS_BB_FORUMSNUM, $num),
		'link'=>'forums.php','icon'=>'../images/forum48.png'));
//Anuncios	
$sql = "SELECT COUNT(*) FROM ".$db->prefix("exmbb_announcements");
list($num) = $db->fetchRow($db->query($sql));
$tpl->append('options', array('text'=>_AS_BB_ANOUN, 'info'=>sprintf(_AS_BB_ANOUNNUM, $num),
		'link'=>'announcements.php','icon'=>'../images/announcement48.png'));
//Reportes
$sql = "SELECT COUNT(*) FROM ".$db->prefix('exmbb_report');
list($num) = $db->fetchRow($db->queryF($sql));
$tpl->append('options', array('text'=>_AS_BB_REPORTS, 'info'=>sprintf(_AS_BB_REPORTSNUM, $num),
		'link'=>'reports.php','icon'=>'../images/report48.png'));
//Purgar
$tpl->append('options', array('text'=>_AS_BB_PURGE, 'info'=>_AS_BB_CLICK,
		'link'=>'purge.php','icon'=>'../images/purge48.png'));

//Todos los temas
$sql = "SELECT COUNT(*) FROM ".$db->prefix('exmbb_topics');
list($num) = $db->fetchRow($db->queryF($sql));
$tpl->append('options', array('text'=>_AS_BB_TOPICS, 'info'=>sprintf(_AS_BB_TOPICSNUM, $num),
		'link'=>'../search.php','icon'=>'../images/topics48.png'));

//Temas sin respuesta
$sql = "SELECT COUNT(*) FROM ".$db->prefix('exmbb_topics')." WHERE replies=0";
list($num) = $db->fetchRow($db->queryF($sql));
$tpl->append('options', array('text'=>_AS_BB_ANUNSWERED, 'info'=>sprintf(_AS_BB_ANUNSWEREDNUM, $num),
		'link'=>'../search.php?themes=2','icon'=>'../images/topicsnoreply48.png'));

//Temas Recientes
$sql = "SELECT COUNT(*) FROM ".$db->prefix('exmbb_topics')." WHERE date>".(time()-($xoopsModuleConfig['time_topics']*3600));
list($num) = $db->fetchRow($db->queryF($sql));
$tpl->append('options', array('text'=>_AS_BB_RECENT, 'info'=>sprintf(_AS_BB_RECENTNUM, $num),
		'link'=>'../search.php?themes=1','icon'=>'../images/topicsnew48.png'));
//Link a Red México
$tpl->append('options', array('text'=>_AS_BB_REDMEX, 'info'=>_AS_BB_SITE,
		'link'=>'http://redmexico.com.mx','icon'=>'../images/rm48.png'));

// EXM System
$tpl->append('options', array('text'=>'EXM System', 'info'=>_AS_BB_SITE,
		'link'=>'http://exmsystem.org','icon'=>'../../../images/exm.png'));
		
//Ayuda
$tpl->append('options', array('text'=>_AS_BB_HELP, 'info'=>_AS_BB_CLICK,
		'link'=>'','icon'=>'../images/question.png'));

//Lista de Mensajes recientes
$tbl1= $db->prefix('exmbb_posts');
$tbl2= $db->prefix('exmbb_topics');
$tbl3=$db->prefix('exmbb_posts_text');
$sql=" SELECT a.*,b.id_topic,b.title,c.post_text FROM $tbl1 a, $tbl2 b, $tbl3 c WHERE a.id_topic=b.id_topic AND c.post_id=a.id_post 
AND a.post_time>".(time()-($xoopsModuleConfig['time_topics']*3600))." ORDER BY post_time DESC LIMIT 0,5";
$result=$db->queryF($sql);
while ($row=$db->fetchArray($result)){
	$post['id']=$row['id_topic'];
	$posts['date']=BBFunctions::formatDate($row['post_time']);
	$posts['by']= sprintf(_AS_BB_BY, $row['poster_name']);
	$tpl->append('topics',array('id'=>$row['id_topic'],'title'=>$row['title'],'text'=>substr($util->FilterTags($row['post_text']),0,100),
	'posts'=>$posts));
}

$tpl->assign('lang_lasttopics',_AS_BB_LASTTOPICS);
$tpl->assign('lang_versions',sprintf(_AS_BB_VERSIONS,$xoopsModule->getInfo('name')));

xoops_cp_footer();
?>
