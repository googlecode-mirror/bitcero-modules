<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage files for download in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

require '../../mainfile.php';

$xoopsOption['template_main'] = 'dtrans_index.html';
$xoopsOption['module_subpage'] = 'index';

include 'header.php';

$tpl->add_style('main.css','dtransport');

$dtfunc->makeHeader();

$xoopsTpl->assign('show_daydowns', $mc['daydownload']);
$link = DT_URL;

if ($mc['daydownload']){
	$xoopsTpl->assign('lang_daydown',_MS_DT_DAYDOWN);
	
	$sql = "SELECT * FROM ".$db->prefix("dtrans_software")." WHERE approved='1' AND daily='1' LIMIT 0,$mc[limit_daydownload]";
	$result = $db->query($sql);
	while ($row = $db->fetchArray($result)){
		$item = new DTSoftware();
		$item->assignVars($row);
		$slink = $mc['permalinks'] ? $link .'/item/'.$item->nameId().'/' :  $link .'/item.php?id='.$item->id();
		$dlink = $mc['permalinks'] ? $link .'/item/'.$item->nameId().'/download/' :  $link .'/item.php?id='.$item->id().'/download';
		$xoopsTpl->append('daily', array('id'=>$item->id(),'name'=>$item->name(),'desc'=>$item->shortdesc(),
				'votes'=>$item->votes(),'rating'=>DTFunctions::createRatingGraph($item->votes(), $item->rating()),
				'img'=>$item->image(),'link'=>$slink, 'dlink'=>$dlink));
	}
}

if($mc['dest_download'])
    $dtfunc->featured_items();

// Recent downloads
$dtfunc->recent_items();

// Categorías
$xoopsTpl->assign('show_cats', $mc['showcats']);
if ($mc['showcats']){
	$categos = array();
	$dtfunc->getCategos($categos, 0, 0, array(), true);
	$i = 0;
	foreach ($categos as $row){
		$cat =& $row['object'];
		$link = $cat->permalink();
		$xoopsTpl->append('categos', array('id'=>$cat->id(), 'name'=>$cat->name(), 'jumps'=>$row['jumps'],'link'=>$link));
		$i++;
	}
}

//Búsqueda populares
$sql="SELECT MAX(hits) FROM ".$db->prefix('dtrans_tags');
list($maxhit)=$db->fetchRow($db->query($sql));
$sql="SELECT * FROM ".$db->prefix('dtrans_tags')." LIMIT 0,".$mc['limit_tagspopular'];
$result=$db->query($sql);
 $sz=$mc['size_fonttags']/$maxhit;
while ($row = $db->fetchArray($result)){
	$tag=new DTTag();
	$tag->assignVars($row);
	$link=XOOPS_URL."/modules/dtransport/".($mc['permalinks'] ? "tag/".$tag->tag() : "tags.php?id=".$tag->tag());
	
	$size=intval($tag->hit()*$sz);
	if ($size<10){
		$size=10;
	}
	
	$xoopsTpl->append('tags',array('id'=>$tag->id(),'tag'=>$tag->tag(),'hit'=>$tag->hit(),'link'=>$link,'size'=>$size));	
	
}

$xoopsTpl->assign('lang_recents', __('New Downloads','dtransport'));
$xoopsTpl->assign('lang_bestrated', __('Best Rated','dtransport'));
$xoopsTpl->assign('lang_updated', __('Updated','dtransport'));

$xoopsTpl->assign('lang_info', _MS_DT_INFO);
$xoopsTpl->assign('lang_downs', _MS_DT_DOWNS);
$xoopsTpl->assign('lang_rate', _MS_DT_RATE);
$xoopsTpl->assign('lang_lic', _MS_DT_LIC);
$xoopsTpl->assign('lang_readmore', _MS_DT_READMORE);
$xoopsTpl->assign('lang_download', _MS_DT_DOWNLOAD);
$xoopsTpl->assign('lang_os', _MS_DT_OS);
$xoopsTpl->assign('lang_created', _MS_DT_CREATED);
$xoopsTpl->assign('lang_modified', _MS_DT_MODIFIED);
$xoopsTpl->assign('lang_total', _MS_DT_TOTAL);
$xoopsTpl->assign('lang_rateusers', _MS_DT_USERS);
$xoopsTpl->assign('lang_screens', _MS_DT_SCREENS);
$xoopsTpl->assign('lang_ratesite', $xoopsConfig['sitename']);
$xoopsTpl->assign('lang_categos', _MS_DT_CATEGOS);
$xoopsTpl->assign('lang_tagspopular',_MS_DT_TAGSPOPULAR);
$xoopsTpl->assign('active_tags',$mc['active_tags']);
$xoopsTpl->assign('lang_hits',_MS_DT_HITS);
include 'footer.php';

