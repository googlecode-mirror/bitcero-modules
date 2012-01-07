<?php
// $Id: category.php 41 2008-04-04 17:44:28Z ginis $
// --------------------------------------------------------------
// D-Transport
// Módulo para la administración de descargas
// http://www.redmexico.com.mx
// http://www.exmsystem.net
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

define('DT_LOCATION','category');
include '../../mainfile.php';

$id = isset($_GET['id']) ? addslashes($_GET['id']) : '';
if ($id==''){
	redirect_header(XOOPS_URL.'/modules/dtransport', 2, _MS_DT_ERRID);
	die();
}

$myts =& MyTextSanitizer::getInstance();

// Obtenemos las diferentes partes de los parámetros
$params = explode('/', $id);
$id = intval($params[0]);
$page = isset($params[1]) ? intval($params[1]) : 1;
$order = isset($params[2]) ? $myts->addSlashes($params[2]) : 'recent';
$ad = isset($params[3]) ? $myts->addSlashes($params[3]) : 'recent';

if ($id<=0){
	redirect_header(XOOPS_URL.'/modules/dtransport', 2, _MS_DT_ERRID);
	die();
}

$xoopsOption['template_main'] = 'dtrans_category.html';
$xoopsOption['module_subpage'] = 'category';
include 'header.php';

DTFunctionsHandler::makeHeader();
$category = new DTCategory($id);

// Descargas en esta categoría
$sql = "SELECT COUNT(*) FROM ".$db->prefix("dtrans_software")." WHERE approved='1' AND id_cat='$id'";
list($num) = $db->fetchRow($db->query($sql));

$limit = $mc['xpage'];
$limit = $limit<=0 ? 10 : $limit;

if ($page > 0) $page -= 1;
$tpages = (int)($num / $limit);
if ($tpages<$page) $page=$tpages;
if($num % $limit > 0) $tpages++;
$pactual = $page + 1;
if ($pactual>$tpages){
	$rest = $pactual - $tpages;
    $pactual = $pactual - $rest + 1;
    $start = ($pactual - 1) * $limit;
}

DTFunctionsHandler::createNavigation($num, $mc['xpage'], $pactual);	

$start = $page * $limit;

$showmax = $start + $limit;
$showmax = $showmax > $num ? $num : $showmax;
$tpl->assign('lang_showing', sprintf(_MS_DT_SHOWING, $start + 1, $showmax, $num));
$tpl->assign('limit',$limit);
$tpl->assign('pag',$pactual);

// Seleccionamos los registros
$sql = str_replace('COUNT(*)', '*', $sql);

switch ($order){
	case 'created':
		$sql .= " ORDER BY created DESC";
		break;
	case 'name':
		$sql .= " ORDER BY name";
		break;
	case 'rateown':
		$sql .= " ORDER BY siterate DESC";
		break;
	case 'rateuser':
		$sql .= " ORDER BY `rating`/`votes` DESC";
		break;
	default:
		$sql .= " ORDER BY modified DESC";
		break;
}

$sql .= " LIMIT $start, $limit";

$result = $db->queryF($sql);
while ($row = $db->fetchArray($result)){
	$item = new DTSoftware();
	$item->assignVars($row);
	$tpl->append('recents', DTFunctionsHandler::createItemData($item));
}

// Descargas del Día
$tpl->assign('show_daydowns', $mc['dadydowncat']);
$link = DT_URL;

if ($mc['dadydowncat'] && $page<1){
	$tpl->assign('lang_daydown', sprintf(_MS_DT_DAYDOWN, $category->name()));
	
	$sql = "SELECT * FROM ".$db->prefix("dtrans_software")." WHERE approved='1' AND daily='1' AND id_cat='$id' ORDER BY RAND() LIMIT 0,$mc[limit_daydownload]";
	$result = $db->query($sql);
	$tpl->assign('day_num', $db->getRowsNum($result));
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
$tpl->assign('show_marked', $mc['featured_categos']);
if ($mc['featured_categos'] && $page<1){
	$tpl->assign('lang_marked',_MS_DT_MARKED);
	
	$sql = "SELECT * FROM ".$db->prefix("dtrans_software")." WHERE approved='1' AND mark='1' AND id_cat='$id' ORDER BY ".($mc['mode_download'] ? "RAND()" : "modified DESC")." LIMIT 0,$mc[limit_destdown]";
	$result = $db->query($sql);
	$tpl->assign('marked_count', $db->getRowsNum($result));
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

$tpl->assign('lang_readmore', _MS_DT_READMORE);
$tpl->assign('lang_download', _MS_DT_DOWNLOAD);
$tpl->assign('xoops_pagetitle', $category->name() . " &raquo; " . $xoopsModule->name());
$tpl->assign('lang_in', sprintf(_MS_DT_DOWNIN, $category->name()));
$tpl->assign('lang_info', _MS_DT_INFO);
$tpl->assign('lang_downs', _MS_DT_DOWNS);
$tpl->assign('lang_rate', _MS_DT_RATE);
$tpl->assign('lang_lic', _MS_DT_LIC);
$tpl->assign('lang_os', _MS_DT_OS);
$tpl->assign('lang_created', _MS_DT_CREATED);
$tpl->assign('lang_modified', _MS_DT_MODIFIED);
$tpl->assign('lang_total', _MS_DT_TOTAL);
$tpl->assign('lang_rateusers', _MS_DT_USERS);
$tpl->assign('lang_screens', _MS_DT_SCREENS);

// Script para mostrar categorías
$xmh .= "\n<script type='text/javascript'>\n
function dtShowCategos(){\n
	if ($('dtCategosListH').style.display=='block'){\n
		$('dtCategosListH').style.display='none';\n
		$('dtViewCats').style.background='url(".DT_URL."/images/adown.png) no-repeat right';\n
	}else{\n
		$('dtCategosListH').style.display='block';\n
		$('dtViewCats').style.background='url(".DT_URL."/images/aup.png) no-repeat right';\n
	}\n
}\n</script>";

// Subcategorías
$tpl->assign('show_cats', $mc['showcats']);
if ($mc['showcats']){
	$categos = array();
	DTFunctionsHandler::getCategos($categos, 0, $category->id(), array(), true);
	$i = 0;
	foreach ($categos as $row){
		$cat =& $row['object'];
		$link = $mc['urlmode'] ? DT_URL . '/category/'.$cat->id() : DT_URL.'/category.php?id='.$cat->id();
		$tpl->append('categos', array('id'=>$cat->id(), 'name'=>$cat->name(), 'jumps'=>$row['jumps'],'link'=>$link));
		$i++;
	}
}

// Opciones para el orden
$tpl->assign('order', $order);
$tpl->assign('lang_name', _MS_DT_NAME);
$tpl->assign('lang_corder', _MS_DT_CORDER);
$tpl->assign('lang_morder', _MS_DT_CMODIFIED);
$tpl->assign('lang_orderby', _MS_DT_ORDERBY);
$tpl->assign('lang_users', _MS_DT_ORDERU);
$tpl->assign('lang_categos', _MS_DT_CATS);
$tpl->assign('lang_subcategos', _MS_DT_SUBCATS);

// Datos de la categoría
$link = DT_URL.'/'.($mc['urlmode'] ? 'category/'.$category->id() : 'category.php?id='.$category->id());
$tpl->assign('category', array('id'=>$category->id(),'name'=>$category->name(),'link'=>$link));

// LOcalización
$loc = "<strong>"._MS_DT_YOUREHERE."</strong> <a href='".DT_URL."'>".$xoopsModule->name()."</a> &raquo; ".DTFunctionsHandler::getCatLocation($category);
$tpl->assign('dt_location', $loc);

include 'footer.php';
?>
