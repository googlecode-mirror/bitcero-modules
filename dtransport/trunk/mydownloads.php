<?php
// $Id$
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



define('DT_LOCATION','mydowns');
include '../../mainfile.php';

$mc=& $xoopsModuleConfig;


$xoopsOption['template_main'] = 'dtrans_mydownloads.html';
$xoopsOption['module_subpage'] = 'mydowns';
include 'header.php';
	
DTFunctions::makeHeader();

//Navegadot de páginas
$sql = "SELECT COUNT(*) FROM ".$db->prefix('dtrans_software'). " WHERE uid=".($xoopsUser ? $xoopsUser->uid() : 0);
list($num) = $db->fetchRow($db->query($sql));

if ($num<=0){
	redirect_header(XOOPS_URL."/modules/dtransport/",2,_MS_DT_NOTDOWNS);
	die();
}

$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
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
    $nav = new XoopsPageNav($num, $limit, $start, 'pag', 'limit='.$limit, 0);
    $tpl->assign('itemsNavPage', $nav->renderNav(4, 1));
}

$showmax = $start + $limit;
$showmax = $showmax > $num ? $num : $showmax;
$tpl->assign('lang_showing', sprintf(_MS_DT_SHOWING, $start + 1, $showmax, $num));
$tpl->assign('limit',$limit);
$tpl->assign('pag',$pactual);

//Fin de barra de navegación



$sql = str_replace('COUNT(*)', '*', $sql);
$sql.= " LIMIT $start,$limit";
$result = $db->query($sql);
while ($rows = $db->fetchArray($result)){

	$item = new DTSoftware();
	$item->assignVars($rows);
	
	$rtn = DTFunctions::createItemData($item);
	$rtn['modified'] = formatTimestamp($rows['modified'], 's');	
	$tpl->append('items', $rtn);	
}

$tpl->assign('lang_name',_MS_DT_NAME);
$tpl->assign('lang_date',_MS_DT_DATE);
$tpl->assign('lang_downs',_MS_DT_NUMDOWNS);
$tpl->assign('lang_approved',_MS_DT_APPROVED);
$tpl->assign('lang_edit',_EDIT);
$tpl->assign('lang_options',_OPTIONS);
$tpl->assign('lang_legend',_MS_DT_LEGEND);
$tpl->assign('lang_screens',_MS_DT_SCREEN);
$tpl->assign('lang_features',_MS_DT_FEATURES);
$tpl->assign('lang_files',_MS_DT_FILES);
$tpl->assign('lang_logs',_MS_DT_LOGS);
$tpl->assign('urlmode',$mc['urlmode']);

$link = XOOPS_URL."/modules/dtransport/".($mc['urlmode'] ? "item/" : "item.php?id=");
$tpl->assign('link',$link);




// Ubicación Actual
$location = "<strong>"._MS_DT_YOUREHERE."</strong> <a href='".DT_URL."'>".$xoopsModule->name()."</a> &raquo; ";
$location .= _MS_DT_MYDOWNS;
$tpl->assign('dt_location', $location);


include 'footer.php';
?>
