<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('GS_LOCATION','search');
include '../../mainfile.php';

if ($xoopsModuleConfig['search_format_mode']){
	$xoopsOption['template_main'] = 'gs_searchformat.html';
}else{
	$xoopsOption['template_main'] = 'gs_search.html';
}
$xoopsOption['module_subpage'] = 'search';
include 'header.php';

GSFunctions::makeHeader();

$myts =& MyTextSanitizer::getInstance();

$search = isset($_REQUEST['search']) ? $myts->addSlashes($_REQUEST['search']) : ($search!=1?$search:'');

//Dividimos la búsqueda en palabras
$words = explode(" ",$search);


//Barra de Navegación
$sql = "SELECT COUNT(DISTINCT c.id_image) FROM ".$db->prefix('gs_tags')." a INNER JOIN ".$db->prefix('gs_tagsimages')." b INNER JOIN ";
$sql.= $db->prefix('gs_images')." c ON (";
$sql.= "a.id_tag=b.id_tag AND b.id_image=c.id_image AND c.public=2 AND (";
$sql1 = '';
foreach($words as $k){

	if(strlen($k)<=2) continue;	

	$sql1 .= $sql1=='' ? " (a.tag LIKE '%$k%' OR c.title LIKE '%$k%') " : " OR (tag LIKE '%$k%' OR c.title LIKE '%$k%')";
}
$sql1.="))";
	
/**
* @desc Formato para el manejo de las imágenes
*/
if ($mc['search_format_mode']){
	$format = $mc['search_format_values'];
	$limit = $format[3];
	$cols = $format[4];
	$showdesc = @$format[5];
    $tpl->assign('width_img', $format[1]);
} else {
	$limit = $mc['num_search'];
	$cols = $mc['cols_search'];
	$showdesc=0;
	$width = $mc['image_ths'][0];
}


list($num)=$db->fetchRow($db->query($sql.$sql1));
		
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
   	
   	$nav = new RMPageNav($num, $limit, $pactual, 5);
   	$nav->target_url($mc['urlmode'] ? GSFunctions::get_url().'/search/'.$search.'/pag/{PAGE_NUM}/' : '?search='.$search.'&amp;pag={PAGE_NUM}');
    $tpl->assign('searchNavPage', $nav->render(false));
}

$showmax = $start + $limit;
$showmax = $showmax > $num ? $num : $showmax;
$tpl->assign('lang_showing', sprintf(__('Showing pictures %u to %u from %u'), $start + 1, $showmax, $num));
$tpl->assign('limit',$limit);
$tpl->assign('pag',$pactual);
//Fin de barra de navegación




$sql = "SELECT DISTINCT c.* FROM ".$db->prefix('gs_tags')." a INNER JOIN ".$db->prefix('gs_tagsimages')." b INNER JOIN ";
$sql.= $db->prefix('gs_images')." c ON (";
$sql.= "a.id_tag=b.id_tag AND b.id_image=c.id_image AND c.public=2 AND (";
$sql1 = '';
foreach($words as $k){

	if(strlen($k)<=2) continue;	

	$sql1 .= $sql1=='' ? " (a.tag LIKE '%$k%' OR c.title LIKE '%$k%') " : " OR (tag LIKE '%$k%' OR c.title LIKE '%$k%')";
}
$sql1.="))";
$sql2 = " GROUP BY c.id_image ORDER BY c.created DESC LIMIT $start,$limit";
$result = $db->query($sql.$sql1.$sql2);
$users = array();

$tpl->assign('images', GSFunctions::process_image_data($result));

$tpl->assign('max_cols',$cols);
$tpl->assign('lang_found',sprintf(__('Found %s pictures for "%s"','galleries'), '<strong>'.$num.'</strong>', $search));

include 'footer.php';
