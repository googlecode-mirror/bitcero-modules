<?php
// $Id: categories.php 26 2009-09-13 20:26:35Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$xoopsOption['template_main'] = 'mywords_cats.html';
$xoopsOption['module_subpage'] = 'catego';
include 'header.php';

$tbl1 = $db->prefix("mw_categories");
$tbl2 = $db->prefix("mw_catpost");
$tbl3 = $db->prefix("mw_posts");

$page = isset($_REQUEST['page']) ? $_REQUEST['page']: 0;
	
if ($page<=0){
	$path = explode("/", $request);
	$srh = array_search('page', $path);
	if (isset($path[$srh]) && $path[$srh]=='page')	if (!isset($path[$srh])){ $page = 0; } else { $page = $path[$srh +1]; }
}

/**
 * Antes que nada debemos buscar la categoría
 * si esta ha sido pasada como una ruta
 */
if (@$categotype){
	array_shift($path);
	/**
	 * Comprobamos si el primer indice corresponde a la id de la categoría
	 */
	if (is_numeric($path[0])){
		$category = $path[0];
	} else {
	
		$idp = 0; # ID de la categoria padre
		foreach ($path as $k){
			if ($k=='') continue;
			$sql = "SELECT id_cat FROM $tbl1 WHERE shortname='$k' AND parent='$idp'";
			$result = $db->query($sql);
			if ($db->getRowsNum($result)>0) list($idp) = $db->fetchRow($result);
			
		}
		$category = $idp;
	}
	
}

$catego = new MWCategory($category);
if ($catego->isNew()){
	redirect_header(MWFunctions::get_url(), 2, __('Specified category could not be found','mywords'));
	die();
}

// Datos de la Categoría
$xoopsTpl->assign('category', array('id'=>$catego->id(),'name'=>$catego->getVar('name')));
$xoopsTpl->assign('lang_postsincat', sprintf(__('Posts in &#8216;%s&#8217; Category','mywords'), $catego->getVar('name')));

$request = substr($request, 0, strpos($request, 'page')>0 ? strpos($request, 'page') - 1 : strlen($request));
//$request = 

// Select all posts from relations table
//$sql = "SELECT post FROM ".$db->prefix("mw_catpost")." WHERE cat='$category'";
//$result = $db->query($sql);

/**
 * Paginacion de Resultados
 */
$limit = $mc['posts_limit'];
list($num) = $db->fetchRow($db->query("SELECT COUNT($tbl2.post) FROM $tbl2, $tbl3 WHERE $tbl2.cat='$category' 
		AND $tbl3.id_post=$tbl2.post AND $tbl3.status='publish' AND 
		(($tbl3.visibility='public' OR $tbl3.visibility='password') OR ($tbl3.visibility='private' AND 
		$tbl3.author=".($xoopsUser ? $xoopsUser->uid() : -1)."))"));

$page = isset($page) ? $page : 0;

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

$xoopsTpl->assign('pactual', $pactual);

if ($pactual > 1){
	$xoopsTpl->append('pages', array('id'=>'anterior', 'link'=>mw_get_url().($mc['permalinks']==1 ? '?cat='.$category.'&amp;page='.($pactual-1) : ($mc['permalinks']==2 ? $request."page/".($pactual - 1)."/" : $request."page/".($pactual - 1)))));
}
if ($tpages > 1){
	for ($i=1;$i<=$tpages;$i++){
		$plink = mw_get_url();
		$plink .= $mc['permalinks']==1 ? '?cat='.$category.'&amp;page='.$i : ($mc['permalinks']==2 ? $request."page/$i/" : $request."page/$i");
		$xoopsTpl->append('pages', array('id'=>$i,'link'=>$plink));
	}
}

if ($pactual < $tpages && $tpages > 1){
	$xoopsTpl->append('pages', array('id'=>'siguiente', 'link'=>mw_get_url().($mc['permalinks']==1 ? '?cat='.$category.'&amp;page='.($pactual+1) : ($mc['permalinks']==2 ? $request."page/".($pactual + 1)."/" : $request."page/".($pactual + 1)))));
}

$xoopsTpl->assign('lang_permalink',__('Permalink to this post','mywords'));

$result = $db->query("SELECT $tbl3.* FROM $tbl2, $tbl3 WHERE $tbl2.cat='$category' 
		AND $tbl3.id_post=$tbl2.post AND $tbl3.status='publish' AND 
		(($tbl3.visibility='public' OR $tbl3.visibility='password') OR ($tbl3.visibility='private' AND 
		$tbl3.author=".($xoopsUser ? $xoopsUser->uid() : -1).")) ORDER BY $tbl3.pubdate DESC LIMIT $start,$limit");

require 'post_data.php';

include 'footer.php';
