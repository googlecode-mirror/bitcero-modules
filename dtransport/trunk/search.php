<?php
// $Id: search.php 41 2008-04-04 17:44:28Z ginis $
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

define('DT_LOCATION','search');
include '../../mainfile.php';

$xoopsOption['template_main'] = 'dtrans_search.html';
$xoopsOption['module_subpage'] = 'search';
include 'header.php';

DTFunctions::makeHeader();

$keyw = isset($_GET['keyw']) ? $_GET['keyw'] : '';
$page = isset($_GET['pag']) ? intval($_GET['pag']) : 1;
$order = isset($_GET['order']) ? $myts->addSlashes($_GET['order']) : '';

//Verificamos si nos proporcionaron una palabra o un orden
if (!$keyw && !$order){
	redirect_header(XOOPS_URL."/modules/dtransport/",2,_MS_DT_ERRSEARCH);
	die();
}


$words=explode(" ",$keyw);
$sql = "SELECT COUNT(*) FROM ".$db->prefix("dtrans_software");
$sql1="";
foreach ($words as $k){
	$k=trim($k);
	if (strlen($k)<=2 || $k==""){
		continue;
	}
	$sql1.= ($sql1=="" ? " WHERE " : " OR ")."name LIKE '%$k%' OR shortdesc LIKE '%$k%' OR uname LIKE '%$k%'";
}
if ($sql1){
	list($num) = $db->fetchRow($db->query($sql.$sql1));
}
else{
	$num=0;
}
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


if ($num>0 || $order){

//Verificamos si la busqueda pertenece a alguna etiqueta para incrementar sus hits
$sql2="SELECT * FROM ".$db->prefix('dtrans_tags')." WHERE ";
$sql3="";
foreach ($words as $k){
	$k=trim($k);
	if ($k=="" || (strlen($k)<$xoopsModuleConfig['caracter_tags'])){
		continue;
	}

	$sql3.= ($sql3=="" ? " " :  " OR ")." tag='$k'";
}
$result=$db->query($sql2.$sql3);
while($row = $db->fetchArray($result)){
	$tag=new DTTag();
	$tag->assignVars($row);
	
	$tag->setHit($tag->hit()+1);
	$tag->save();
}

$sql = str_replace("COUNT(*)",'*',$sql.$sql1);

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
	case 'popular':
		$sql.= " ORDER BY hits DESC";
		break;
	default:
		$sql .= " ORDER BY modified DESC";
		break;
}

$sql .= " LIMIT $start,$limit";

$result = $db->queryF($sql);
while ($row = $db->fetchArray($result)){
	$item = new DTSoftware();
	$item->assignVars($row);

	$tpl->append('recents', DTFunctions::createItemData($item));
}
}

$link=XOOPS_URL."/modules/dtransport/search.php?order=";

// Opciones para el orden
$tpl->assign('order', $order);
$tpl->assign('keyw', $keyw);
$tpl->assign('lang_name', _MS_DT_NAME);
$tpl->assign('lang_corder', _MS_DT_CORDER);
$tpl->assign('lang_morder', _MS_DT_CMODIFIED);
$tpl->assign('lang_orderby', _MS_DT_ORDERBY);
$tpl->assign('lang_users', _MS_DT_ORDERU);

$tpl->assign('lang_readmore', _MS_DT_READMORE);
$tpl->assign('lang_download', _MS_DT_DOWNLOAD);
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
$tpl->assign('recents_link',$link."created");
$tpl->assign('popular_link',$link."popular");
$tpl->assign('rated_link',$link."rateuser");

// Ubicación Actual
$location = "<strong>"._MS_DT_YOUREHERE."</strong> <a href='".DT_URL."'>".$xoopsModule->name()."</a> &raquo; ";
switch ($order){
	case 'created':
		$location .= "<strong>"._MS_DT_DOWNSRECENT."</strong>";
	break;
	case 'popular':
		$location .= "<strong>"._MS_DT_DOWNSPOPULAR."</strong>";
	break;
	case 'rateuser':
		$location .= "<strong>"._MS_DT_DOWNSRATED."</strong>";
	break;
	default:
		$location .= "<strong>"._MS_DT_SEARCHLOC."</strong>";
}
$tpl->assign('dt_location', $location);

include 'footer.php';

?>
