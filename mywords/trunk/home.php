<?php
// $Id: home.php 13 2009-08-31 00:45:24Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$xoopsOption['template_main'] = 'mywords_index.html';
$xoopsOption['module_subpage'] = 'index';
include 'header.php';

/**
 * Paginacion de Resultados
 */
list($num) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_posts")." WHERE estado='1' AND aprovado='1'"));
$page = isset($_REQUEST['page']) ? $_REQUEST['page']: 0;

if ($page<=0){
	$path = explode("/", $request);
	$srh = array_search('page', $path);
	if (isset($path[$srh]) && $path[$srh]=='page')	if (!isset($path[$srh])){ $page = 0; } else { $page = $path[$srh +1]; }
}

if ($page > 0){ $page -= 1; }
$start = $page * $mc['limite'];
$tpages = (int)($num / $mc['limite']);
if ($num % $mc['limite'] > 0) $tpages++;

$pactual = $page + 1;
if ($pactual>$tpages){
	$rest = $pactual - $tpages;
	$pactual = $pactual - $rest + 1;
	$start = ($pactual - 1) * $limit;
}

$tpl->assign('pactual', $pactual);

if ($pactual > 1){
	$tpl->append('pages', array('id'=>'anterior', 'link'=>mw_get_url().($mc['permalinks']==1 ? '?page='.($pactual-1) : ($mc['permalinks']==2 ? "page/".($pactual - 1)."/" : "page/".($pactual - 1)))));
}
for ($i=1;$i<=$tpages;$i++){
	$plink = mw_get_url();
	$plink .= $mc['permalinks']==1 ? '?page='.$i : ($mc['permalinks']==2 ? "page/$i/" : "page/$i");
	$tpl->append('pages', array('id'=>$i,'link'=>$plink));
}

if ($pactual < $tpages && $tpages > 1){
	$tpl->append('pages', array('id'=>'siguiente', 'link'=>mw_get_url().($mc['permalinks']==1 ? '?page='.($pactual+1) : ($mc['permalinks']==2 ? "page/".($pactual + 1)."/" : "page/".($pactual + 1)))));
}

$tpl->assign('lang_next', _MS_MW_NEXTPAGE);
$tpl->assign('lang_prev', _MS_MW_PREVPAGE);
$tpl->assign('lang_comments', _MS_MW_COMS);

$result = $db->query("SELECT * FROM ".$db->prefix("mw_posts")." WHERE estado=1 AND aprovado='1' ORDER BY fecha DESC LIMIT $start,$mc[limite]");
require 'post_data.php';

include 'footer.php';
?>