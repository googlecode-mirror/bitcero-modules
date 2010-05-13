<?php
// $Id$
// --------------------------------------------------------------
// Reporte de Errores
// CopyRight  2007 - 2008. Red México
// Autor: gina
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
// @copyright:  2007 - 2008. Red México
// @author: gina


define('BB_LOCATION','search');
include '../../mainfile.php';
$xoopsOption['template_main'] = "exmbb_search.html";
$xoopsOption['module_subpage'] = "search";
include 'header.php';

$myts=&MyTextSanitizer::getInstance();
BBFunctions::makeHeader();

$search = isset($_REQUEST['search']) ? $myts->addSlashes($_REQUEST['search']) : '';
//$type 0 Todas las palabras
//$type 1 Cualquiera de las palabras
//$type 2 Frase exacta
$type = isset($_REQUEST['type']) ? intval($_REQUEST['type']) : 0; 
//$topics 1 Temas recientes
//$topics 2 Temas sin respuestas
$themes = isset($_REQUEST['themes']) ? intval($_REQUEST['themes']) : 0; 


$tbl1 = $db->prefix("exmbb_topics");
$tbl2 = $db->prefix("exmbb_posts_text");
$tbl3 = $db->prefix("exmbb_posts");
$tbl4 = $db->prefix("exmbb_forums");


//Barra de navegación

$sql = "SELECT COUNT(*) FROM $tbl1 a,$tbl2 b, $tbl3 c,$tbl4 d WHERE ";
$sql1='';
$sql2='';

if ($themes==0 && $search==''){
		$sql.="  a.id_forum=b.id_forum";
}else{

		if ($search){
			$sql1 ='(';
			$sql2 =')';
			//Comprobamos el tipo de búsqueda a realizar
			if ($type==0 || $type==1){
				if ($type==0) $query = "AND"; 
				else $query="OR";
	
				//Separamos la frase en palabras para realizar la búsqueda
				$words = explode(" ",$search);
		
				foreach($words as $k){
					//Verificamos el tamaño de la palabra
					if (strlen($k) <= 2) continue;
				
					$sql1.=($sql1=='(' ? '' : $query). " ( a.title LIKE '%$k%' OR b.post_text LIKE '%$k%') ";
				}
	
			}else{

				$sql1.=" (a.title LIKE '%$search%' OR b.post_text LIKE '%$search%') ";
			}
		}
	
	
	$sql2.=($sql1 ? " AND " : '')." c.approved=1 AND a.id_topic=c.id_topic AND b.post_id=c.id_post AND d.id_forum=c.id_forum ";

	$sql2.=($themes ? ($themes==1 ? " AND a.date>".(time()-($xoopsModuleConfig['time_topics']*3600)) :  
       ($themes==2 ? " AND a.replies=0" : '')):'');
		

}

list($num)=$db->fetchRow($db->queryF($sql.$sql1.$sql2));
   
 
$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
$limit = $xoopsModuleConfig['topicperpage'] > 0 ? $xoopsModuleConfig['topicperpage'] : 15;
if ($page > 0){ $page -= 1; }
        
$start = $page * $limit;
$tpages = (int)($num / $limit);
if($num % $limit > 0) $tpages++;
    
$pactual = $page + 1;
if ($pactual>$tpages){
    $rest = $pactual - $tpages;
    $pactual = $pactual - $rest + 1;
    $start = ($pactual - 1) * $limit;
}
    
if ($tpages > 0) {
    $nav = new XoopsPageNav($num, $limit, $start, 'pag', 'limit='.$limit.'&search='.$search.'&type='.$type.'&themes='.$themes, 0);
    $tpl->assign('itemsNavPage', $nav->renderNav(4, 1));
}


//Fin barra de navegación


$sql1='';
$sql2='';
	
if ($themes==0 && $search==''){
		$sql=" SELECT a.*,b.name FROM $tbl1 a,$tbl4 b WHERE a.id_forum=b.id_forum";
		$sql1.="  ORDER BY a.sticky DESC, a.date DESC LIMIT $start,$limit "; 
}else{
	
	
	$sql = "SELECT a.id_topic,a.title,a.views,a.poster_name,a.date,a.replies,a.sticky,a.status,a.last_post,b.post_text,c.*,d.name FROM 
	$tbl1 a,$tbl2 b, $tbl3 c,$tbl4 d WHERE ";

		if ($search){
			$sql1 ='(';
			$sql2 =')';
			//Comprobamos el tipo de búsqueda a realizar
			if ($type==0 || $type==1){
				if ($type==0) $query = "AND"; 
				else $query="OR";
	
				//Separamos la frase en palabras para realizar la búsqueda
				$words = explode(" ",$search);
		
				foreach($words as $k){
					//Verificamos el tamaño de la palabra
					if (strlen($k) <= 2) continue;
				
					$sql1.=($sql1=='(' ? '' : $query). " ( a.title LIKE '%$k%' OR b.post_text LIKE '%$k%') ";
				}
	
			}else{

				$sql1.=" (a.title LIKE '%$search%' OR b.post_text LIKE '%$search%') ";
			}
		}
	
	
	$sql2.=($sql1 ? " AND " : '')." c.approved=1 AND a.id_topic=c.id_topic AND b.post_id=c.id_post AND d.id_forum=c.id_forum ";

	$sql2.=($themes ? ($themes==1 ? " AND a.date>".(time()-($xoopsModuleConfig['time_topics']*3600)) :  
       ($themes==2 ? " AND a.replies=0" : '')):'');
	$sql2.="  ORDER BY a.sticky DESC, a.date DESC LIMIT $start,$limit ";
	

}




$result=$db->queryF($sql.$sql1.$sql2);
while ($rows=$db->fetchArray($result)){
	$date = BBFunctions::formatDate($rows['date']);
	$lastpost = array();
	$firstpost = array();
	if (!$search && $themes==0){
		$firstpost =BBFunctions::getFirstId($rows['id_topic']);
		$last = new BBPost($rows['last_post']);
		$lastpost['date'] = BBFunctions::formatDate($last->date());
		$lastpost['by'] = sprintf(_MS_EXMBB_BY, $last->uname());
		$lastpost['id'] = $last->id();
    		if ($xoopsUser){
    			$lastpost['new'] = $last->date()>$xoopsUser->last_login() && (time()-$last->date()) < $xoopsModuleConfig['time_new'];
	    	} else {
    			$lastpost['new'] = (time()-$last->date())<=$xoopsModuleConfig['time_new'];
		}
	
	}
	
	$tpl->append('topics',array('id'=>$rows['id_topic'],'title'=>$rows['title'],'sticky'=>$rows['sticky'],'user'=>$rows['poster_name'],
	'replies'=>$rows['replies'],'views'=>$rows['views'],'closed'=>$rows['status'],'date'=>$date,'by'=>sprintf(_MS_EXMBB_BY, $rows['poster_name']),
	'forum'=>$rows['name'],'id_post'=>$rows['id_post'],'post_text'=>substr($util->filterTags($rows['post_text']),0,50),'last'=>$lastpost,'firstpost'=>$firstpost));
}

$tpl->assign('lang_search',_MS_EXMBB_SEARCH);
$tpl->assign('lang_recenttopics',_MS_EXMBB_RECENTTOPICS);
$tpl->assign('lang_alltopics',_MS_EXMBB_ALLTOPICS);
$tpl->assign('lang_anunswered',_MS_EXMBB_ANUNSWERED);
$tpl->assign('themes',$themes);
$tpl->assign('search',$search);
$tpl->assign('type',$type);
$tpl->assign('lang_allwords',_MS_EXMBB_ALLWORDS);
$tpl->assign('lang_anywords',_MS_EXMBB_ANYWORDS);
$tpl->assign('lang_exactphrase',_MS_EXMBB_EXACTPHRASE);
$tpl->assign('lang_pages',_MS_EXMBB_PAGES);
$tpl->assign('lang_topic', _MS_EXMBB_TOPIC);
$tpl->assign('lang_replies', _MS_EXMBB_REPLIES);
$tpl->assign('lang_views', _MS_EXMBB_VIEWS);
$tpl->assign('lang_sticky', _MS_EXMBB_STICKY);
$tpl->assign('lang_closed', _MS_EXMBB_CLOSED);
$tpl->assign('lang_forum',_MS_EXMBB_FORUM);
$tpl->assign('lang_date',_MS_EXMBB_DATE);
$tpl->assign('lang_last',_MS_EXMBB_LAST);


include('footer.php');
?>
