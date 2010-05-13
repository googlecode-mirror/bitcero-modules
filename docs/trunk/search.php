<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2

define('AH_LOCATION','search');
include '../../mainfile.php';
$xoopsOption['template_main'] = "ahelp_search.html";
$xoopsOption['module_subpage'] = 'search';
include 'header.php';

$myts=&MyTextSanitizer::getInstance();

$search = isset($_REQUEST['search']) ? $myts->addSlashes($_REQUEST['search']) : '';
//$type 0 Todas las palabras
//$type 1 Cualquiera de las palabras
//$type 2 Frase exacta
$type = isset($_REQUEST['type']) ? intval($_REQUEST['type']) : 0; 
//$mode 1 Publicaciones mejor votadas
//$mode 2 Publicaciones mas populares
//$mode 3 Publicaciones mas recientes
$mode = isset($_REQUEST['mode']) ? intval($_REQUEST['mode']) : 0; 
$item = isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;


//Verificamos si porporcionaron una palabra para la búsqueda
$phrase=trim($search);
if ($phrase=='' && !$mode){
	redirect_header(ah_make_link(),1,_MS_AH_NOTWORDSEARCH);
	die();
}

$tbl1 = $db->prefix('pa_resources');
$tbl2 = $db->prefix('pa_sections');

//Barra de navegacion

//Obtenemos el total de registros
if ($item){
	$sql="SELECT COUNT(*) FROM $tbl1 a, $tbl2 b WHERE (a.id_res=b.id_res) AND ";
}else{
	$sql="SELECT COUNT(*) FROM $tbl1 a WHERE ";
}
$sql1='';

if ($search=='' && $mode){
	$sql="SELECT COUNT(*) FROM $tbl1 a WHERE ";
	switch ($mode){
		//Publicaciones mejor votadas
		case 1:
			$sql1= " approved=1 AND public= 1 ORDER BY rating/votes DESC ";
			$results=_MS_AH_RESULTS1;

		break;
		//Publicaciones mas populares
		case 2:
			$sql1=" approved=1 AND public= 1 ORDER BY `reads` DESC";
			$results=_MS_AH_RESULTS2;
		break;
		//Publicaciones recientes
		case 3:
			$sql1=" approved=1 AND public= 1 ORDER BY created DESC ";
			$results=_MS_AH_RESULTS3;
		break;

	}

}else{
	//Comprobamos el tipo de búsqueda a realizar
	if ($type==0 || $type==1){
		if ($type==0) $query = "AND"; 
		else $query="OR";
	
		//Separamos la frase en palabras para realizar la búsqueda
		$words = explode(" ",$search);
	
		foreach($words as $k){
			//Verificamos el tamaño de la palabra
			if (strlen($k) <= 2) continue;
		
			if ($item){
				$sql1.=($sql1=='' ? '' : $query). " ((b.title LIKE '%$k%' AND b.id_res=a.id_res) OR (b.content LIKE '%$k%' AND b.id_res=a.id_res))";
			}else{
				$sql1.=($sql1=='' ? '' : $query). " (a.title LIKE '%$k%' OR a.description LIKE '%$k%') ";
			}
		
		}
		
	}else{	
		if ($item){
			$sql1.="((b.title LIKE '%$search%' AND b.id_res=a.id_res) OR (b.content LIKE '%$search%' AND b.id_res=a.id_res))";

		}else{
			$sql1.="(a.title LIKE '%$search%'  OR a.description LIKE '%$search%') ";

		}
	}	
	$sql1.=" AND approved=1 AND public=1 ORDER BY a.created DESC ";
	
}	

list($num)=$db->fetchRow($db->queryF($sql.$sql1));

	
$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
$limit = $xoopsModuleConfig['search_limit'];
$limit = $limit<=0 ? 15 : $limit;
	
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
    
if ($tpages > 1) {
	$nav = new XoopsPageNav($num, $limit, $start, 'pag', 'limit='.$limit.'&search='.$search.'&type='.$type.'&mode='.$mode.'&item='.$item, 0);
    $tpl->assign('resNavPage', $nav->renderNav(4, 1));
}

$showmax = $start + $limit;
$showmax = $showmax > $num ? $num : $showmax;
$tpl->assign('lang_showing', sprintf(_MS_AH_RESULT, $start + 1, $showmax, $num));
$tpl->assign('limit',$limit);
$tpl->assign('pag',$pactual);
	
//Fin Barra de navegación

if ($item){
	$sql=str_replace("COUNT(*)","a.id_res,a.title,a.description,a.image,a.created,a.public,a.nameid,a.reads,
	a.votes,a.rating,a.owner,a.owname,a.approved,b.id_sec,b.title AS stitle,b.content,b.id_res AS sid_res,
	b.nameid AS snameid,b.uid,b.uname,b.created AS screated ",$sql);
}else{
	$sql=str_replace("COUNT(*)","a.*",$sql);	
}

$sql2 = " LIMIT $start,$limit";
$result=$db->queryF($sql.$sql1.$sql2);

while ($rows=$db->fetchArray($result)){
	$res=new AHResource();
	$res->assignVars($rows);

	$sec=new AHSection();
	$sec->assignVars($rows);
	$sec->assignVar('title',$item ? $rows['stitle'] : 0);
	$sec->assignVar('id_res',$item ? $rows['sid_res'] : 0);
	$sec->assignVar('nameid',$item ? $rows['snameid'] : 0);
	$sec->assignVar('created',$item ? $rows['screated'] :0);
		
	$url = ah_make_link($res->nameId().'/');
	$url_sec = ah_make_link($res->nameId().'/'.$sec->nameId().'/');
	
	$tpl->append('items',array('id'=>$res->id(),'title'=>$res->title(),'url'=>$url,'reads'=>$res->reads(),
	'desc'=>substr($util->filterTags($res->desc()),0,80)."...",'img'=>$res->image(),'vote'=>$res->votes(),
	'rating'=>@intval($res->rating()/$res->votes()),
	'created'=>date($xoopsConfig['datestring'],$res->created()),'owner'=>$res->owner(),'owname'=>$res->owname(),
	'sizeimg'=>$xoopsModuleConfig['image'],'id_sec'=>$sec->id(),'title_sec'=>$sec->title(),
	'content'=>substr($util->filterTags($sec->content()),0,80)."...",'nameid_sec'=>$sec->nameId(),
	'created_sec'=>date($xoopsConfig['datestring'],$sec->created()),'url_sec'=>$url_sec,'uid'=>$sec->uid(),
	'uname'=>$sec->uname()));

}

$tpl->assign('lang_results',$search ? sprintf(_MS_AH_RESULTS,$search) : $results);
$tpl->assign('lang_home',_MS_AH_HOME);
$tpl->assign('lang_searchs',_MS_AH_SEARCHS);
$tpl->assign('lang_owner',_MS_AH_OWNER);
$tpl->assign('lang_created',_MS_AH_CREATED);
$tpl->assign('lang_search',_MS_AH_SEARCH);
$tpl->assign('lang_type',_MS_AH_TYPE);
$tpl->assign('lang_all',_MS_AH_ALLWORDS);
$tpl->assign('lang_any',_MS_AH_ANYWORDS);
$tpl->assign('lang_phrase',_MS_AH_PHRASE);
$tpl->assign('lang_submit',_SUBMIT);
$tpl->assign('lang_resulted',_MS_AH_RESULTED);
$tpl->assign('lang_votes',_MS_AH_VOTES);
$tpl->assign('lang_reads',_MS_AH_READS);
$tpl->assign('lang_in',_MS_AH_IN);
$tpl->assign('lang_resources',_MS_AH_RESOURCES);
$tpl->assign('lang_sections',_MS_AH_SECTIONS);
$tpl->assign('search',$search);
$tpl->assign('type',$type);
$tpl->assign('item',$item);
$tpl->assign('location_bar', '<a href="'.ah_make_link().'">'._MS_AH_HOME.'</a> &raquo; '._MS_AH_SEARCHS);

makeHeader();
makeFooter();
include 'footer.php';
