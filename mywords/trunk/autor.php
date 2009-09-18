<?php
// $Id: autor.php 13 2009-08-31 00:45:24Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$xoopsOption['template_main'] = 'mywords_author.html';
$xoopsOption['module_subpage'] = 'author';
include 'header.php';

if (!is_numeric($author)){
	
	$sql = "SELECT uid FROM ".$db->prefix("users")." WHERE uname='$author'";
	list($author) = $db->fetchRow($db->query($sql));
	if ($author=='') $author = 0;
	
}

$page = isset($_REQUEST['page']) ? $_REQUEST['page']: 0;	
if ($page<=0){
	$path = explode("/", $request);
	$srh = array_search('page', $path);
	if (isset($path[$srh]) && $path[$srh]=='page')	if (!isset($path[$srh])){ $page = 0; } else { $page = $path[$srh +1]; }
}

$request = substr($request, 0, strpos($request, 'page')>0 ? strpos($request, 'page') - 1 : strlen($request));
$request = $util->addSlash($request);

/**
 * Paginamos los resultados
 */
list($num) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mw_posts")." WHERE autor='$author' AND estado=1 AND aprovado='1'"));

if ($page > 0){ $page -= 1; }
$start = $page * $mc['limite'];
$tpages = (int)($num / $mc['limite']);
if($num % $mc['limite'] > 0) $tpages++;
$pactual = $page + 1;
if ($pactual>$tpages){
	$rest = $pactual - $tpages;
	$pactual = $pactual - $rest + 1;
	$start = ($pactual - 1) * $limit;
}

$tpl->assign('pactual', $pactual);

if ($pactual > 1){
	$tpl->append('pages', array('id'=>'anterior', 'link'=>mw_get_url().($mc['permalinks']==1 ? '?author='.$author.'&amp;page='.($pactual-1) : ($mc['permalinks']==2 ? $request."page/".($pactual - 1)."/" : $request."page/".($pactual - 1)))));
}
for ($i=1;$i<=$tpages;$i++){
	$plink = mw_get_url();
	$plink .= $mc['permalinks']==1 ? '?author='.$author.'&amp;page='.$i : ($mc['permalinks']==2 ? $request."page/$i/" : $request."page/$i");
	$tpl->append('pages', array('id'=>$i,'link'=>$plink));
}

if ($pactual < $tpages && $tpages > 1){
	$tpl->append('pages', array('id'=>'siguiente', 'link'=>mw_get_url().($mc['permalinks']==1 ? '?author='.$author.'&amp;page='.($pactual+1) : ($mc['permalinks']==2 ? $request."page/".($pactual + 1)."/" : $request."page/".($pactual + 1)))));
}

$tpl->assign('lang_next', _MS_MW_NEXTPAGE);
$tpl->assign('lang_prev', _MS_MW_PREVPAGE);
$tpl->assign('lang_comments', _MS_MW_COMS);

$uauth = new XoopsUser($author);
$tpl->assign('lang_fromauthor', sprintf(_MS_MW_POSTSFROMAUTHOR, $uauth->uname()));

$result = $db->query("SELECT * FROM ".$db->prefix("mw_posts")." WHERE autor='$author' AND estado=1 AND aprovado='1' ORDER BY fecha DESC LIMIT $start,$mc[limite]");

require 'post_data.php';

include 'footer.php';
?>