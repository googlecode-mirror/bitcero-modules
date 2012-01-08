<?php
// $Id$
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

define('DT_LOCATION','tags');
include '../../mainfile.php';

$id = isset($_GET['id']) ? addslashes($_GET['id']) : '';
if ($id==''){
	redirect_header(XOOPS_URL.'/modules/dtransport', 2, _MS_DT_ERRID);
	die();
}

$myts =& MyTextSanitizer::getInstance();

// Obtenemos las diferentes partes de los parámetros
$params = explode('/', $id);
$id = $params[0];
$page = isset($params[1]) ? intval($params[1]) : 1;
$order = isset($params[2]) ? $myts->addSlashes($params[2]) : 'recent';
$ad = isset($params[3]) ? $myts->addSlashes($params[3]) : 'recent';

if ($id==''){
	redirect_header(XOOPS_URL.'/modules/dtransport', 2, _MS_DT_ERRID);
	die();
}

$xoopsOption['template_main'] = 'dtrans_tags.html';
$xoopsOption['module_subpage'] = 'tags';

include 'header.php';

DTFunctions::makeHeader();
$tag = new DTTag($id);

//Incrementamos los hits
$tag->setHit($tag->hit()+1);
$tag->save();

// Descargas en esta etiqueta
$sql = "SELECT COUNT(*) FROM ".$db->prefix('dtrans_softtag')." a INNER JOIN ".$db->prefix('dtrans_software')." b ON (a.id_tag=".$tag->id()." AND a.id_soft=b.id_soft) WHERE approved='1'";
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

DTFunctions::createNavigation($num, $mc['xpage'], $pactual);	

$start = $page * $limit;

$showmax = $start + $limit;
$showmax = $showmax > $num ? $num : $showmax;
$tpl->assign('lang_showing', sprintf(_MS_DT_SHOWING, $start + 1, $showmax, $num));
$tpl->assign('limit',$limit);
$tpl->assign('pag',$pactual);

// Seleccionamos los registros
$sql = str_replace('COUNT(*)', 'b.*', $sql);

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
	$tpl->append('recents', DTFunctions::createItemData($item));
}

$tpl->assign('lang_readmore', _MS_DT_READMORE);
$tpl->assign('lang_download', _MS_DT_DOWNLOAD);
$tpl->assign('xoops_pagetitle', $tag->tag() . " &raquo; " . $xoopsModule->name());
$tpl->assign('lang_in', sprintf(_MS_DT_DOWNIN, $tag->tag()));
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



// Opciones para el orden
$tpl->assign('order', $order);
$tpl->assign('lang_name', _MS_DT_NAME);
$tpl->assign('lang_corder', _MS_DT_CORDER);
$tpl->assign('lang_morder', _MS_DT_CMODIFIED);
$tpl->assign('lang_orderby', _MS_DT_ORDERBY);
$tpl->assign('lang_users', _MS_DT_ORDERU);
$tpl->assign('lang_tags', _MS_DT_TAGS);

// Datos de la etiqueta
$link = DT_URL.'/'.($mc['urlmode'] ? 'tag/'.$tag->tag() : 'tags.php?id='.$tag->tag());
$tpl->assign('tags', array('id'=>$tag->id(),'name'=>$tag->tag(),'link'=>$link));

// LOcalización
$loc = "<strong>"._MS_DT_YOUREHERE."</strong> <a href='".DT_URL."'>".$xoopsModule->name()."</a> &raquo; ".$tag->tag();
$tpl->assign('dt_location', $loc);

include 'footer.php';
?>
