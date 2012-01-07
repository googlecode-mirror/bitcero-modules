<?php
// $Id: index.php 41 2008-04-04 17:44:28Z ginis $
// --------------------------------------------------------------
// D-Transport
// Módulo para la administración de descargas
// http://www.redmexico.com.mx
// http://www.exmsystem.com
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
// @copyright: 2007 - 2008 Red México

define('DT_LOCATION','index');
require '../../mainfile.php';

$xoopsOption['template_main'] = 'dtrans_index.html';
$xoopsOption['module_subpage'] = 'index';

include 'header.php';

DTFunctionsHandler::makeHeader();

$tpl->assign('show_daydowns', $mc['daydownload']);
$link = DT_URL;

if ($mc['daydownload']){
	$tpl->assign('lang_daydown',_MS_DT_DAYDOWN);
	
	$sql = "SELECT * FROM ".$db->prefix("dtrans_software")." WHERE approved='1' AND daily='1' LIMIT 0,$mc[limit_daydownload]";
	$result = $db->query($sql);
	while ($row = $db->fetchArray($result)){
		$item = new DTSoftware();
		$item->assignVars($row);
		$slink = $mc['urlmode'] ? $link .'/item/'.$item->nameId().'/' :  $link .'/item.php?id='.$item->id();
		$dlink = $mc['urlmode'] ? $link .'/item/'.$item->nameId().'/download/' :  $link .'/item.php?id='.$item->id().'/download';
		$tpl->append('daily', array('id'=>$item->id(),'name'=>$item->name(),'desc'=>$item->shortdesc(),
				'votes'=>$item->votes(),'rating'=>DTFunctionsHandler::createRatingGraph($item->votes(), $item->rating()),
				'img'=>$item->image(),'link'=>$slink, 'dlink'=>$dlink));
	}
}

// Descargas destacadas
$tpl->assign('show_marked', $mc['dest_download']);
if ($mc['dest_download']){
	$tpl->assign('lang_marked',_MS_DT_MARKED);
	
	$sql = "SELECT * FROM ".$db->prefix("dtrans_software")." WHERE approved='1' AND mark='1' ORDER BY ".($mc['mode_download'] ? "RAND()" : "modified DESC")." LIMIT 0,$mc[limit_destdown]";
	$result = $db->query($sql);
	while ($row = $db->fetchArray($result)){
		$item = new DTSoftware();
		$item->assignVars($row);
		$slink = $mc['urlmode'] ? $link .'/item/'.$item->nameId().'/' :  $link .'/item.php?id='.$item->id();
		$dlink = $mc['urlmode'] ? $link .'/item/'.$item->nameId().'/download/' :  $link .'/item.php?id='.$item->id().'/download';
		$tpl->append('marked', array('id'=>$item->id(),'name'=>$item->name(),'desc'=>$item->shortdesc(),
				'votes'=>$item->votes(),'rating'=>DTFunctionsHandler::createRatingGraph($item->votes(), $item->rating()),
				'img'=>$item->image(),'link'=>$slink, 'dlink'=>$dlink));
	}
}

// Novedades
$sql = "SELECT * FROM ".$db->prefix("dtrans_software")." WHERE approved='1' ORDER BY modified DESC LIMIT 0,".($mc['limit_recents']>0 ? $mc['limit_recents'] : '10');
$result = $db->query($sql);
while ($row = $db->fetchArray($result)){
	$item = new DTSoftware();
	$item->assignVars($row);
	$tpl->append('recents', DTFunctionsHandler::createItemData($item));
}

// Categorías
$tpl->assign('show_cats', $mc['showcats']);
if ($mc['showcats']){
	$categos = array();
	DTFunctionsHandler::getCategos($categos, 0, 0, array(), true);
	$i = 0;
	foreach ($categos as $row){
		$cat =& $row['object'];
		$link = $mc['urlmode'] ? DT_URL . '/category/'.$cat->id() : DT_URL.'/category.php?id='.$cat->id();
		$tpl->append('categos', array('id'=>$cat->id(), 'name'=>$cat->name(), 'jumps'=>$row['jumps'],'link'=>$link));
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
	$link=XOOPS_URL."/modules/dtransport/".($mc['urlmode'] ? "tag/".$tag->tag() : "tags.php?id=".$tag->tag());
	
	$size=intval($tag->hit()*$sz);
	if ($size<10){
		$size=10;
	}
	
	$tpl->append('tags',array('id'=>$tag->id(),'tag'=>$tag->tag(),'hit'=>$tag->hit(),'link'=>$link,'size'=>$size));	
	
}

$tpl->assign('lang_recents', _MS_DT_RECENTSLIST);
$tpl->assign('lang_info', _MS_DT_INFO);
$tpl->assign('lang_downs', _MS_DT_DOWNS);
$tpl->assign('lang_rate', _MS_DT_RATE);
$tpl->assign('lang_lic', _MS_DT_LIC);
$tpl->assign('lang_readmore', _MS_DT_READMORE);
$tpl->assign('lang_download', _MS_DT_DOWNLOAD);
$tpl->assign('lang_os', _MS_DT_OS);
$tpl->assign('lang_created', _MS_DT_CREATED);
$tpl->assign('lang_modified', _MS_DT_MODIFIED);
$tpl->assign('lang_total', _MS_DT_TOTAL);
$tpl->assign('lang_rateusers', _MS_DT_USERS);
$tpl->assign('lang_screens', _MS_DT_SCREENS);
$tpl->assign('lang_ratesite', $xoopsConfig['sitename']);
$tpl->assign('lang_categos', _MS_DT_CATEGOS);
$tpl->assign('lang_tagspopular',_MS_DT_TAGSPOPULAR);
$tpl->assign('active_tags',$mc['active_tags']);
$tpl->assign('lang_hits',_MS_DT_HITS);
include 'footer.php';

?>
