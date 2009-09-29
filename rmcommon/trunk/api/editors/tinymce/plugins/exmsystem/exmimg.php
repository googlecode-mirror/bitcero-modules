<?php
// $Id: exmimg.php 416 2009-04-26 15:08:40Z BitC3R0 $
// --------------------------------------------------------------
// XOOPS IMAGE - TinyMCE Plugin
// Plugin para el manejo de imágenes XOOPS en TinyMCE
// CopyRight © 2005 - 2006. Red México Soft
// Autor: BitC3R0
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
// @copyright: 2007 - 2008. BitC3R0. Red México Soft
// @author: BitC3R0

$path = str_replace("\\", "/", __FILE__);
$path = str_replace("/modules/rmcommon/api/editors/tinymce/plugins/exmsystem/exmimg.php", "", $path);

function displayPage(){
	global $tpl, $plugPath;
	
	//echo $tpl->fetch($plugPath . "exmimg.html");
	die();
}

require $path . '/mainfile.php';
require_once $path.'/modules/rmcommon/loader.php';
global $xoopsLogger, $xoopsConfig;
/*$xoopsConfig['debug_mode'] = 0;
$xoopsLogger->renderingEnabled = false;
error_reporting(0);
$xoopsLogger->activated = false;*/

$tpl = new RMTemplate();
$db = Database::getInstance();
$plugPath = str_replace("exmimg.php","",__FILE__);


$tpl->assign('xipage', ABSURL.'/viewimg.php');


// Cargamos las categor?as
$result = $db->query("SELECT id_cat FROM ".$db->prefix("images_category")." ORDER BY titulo");
while (list($id) = $db->fetchRow($result)){
	$catego = new EXMImagecategory($id);
	$read = false;
	if ($exmUser){
		$read = $catego->canRead($exmUser->getGroups());
		$write = $catego->canWrite($exmUser->getGroups());
	} else {
		$read = $catego->canRead(EXM_GROUP_GUESTS);
		$write = $catego->canWrite(EXM_GROUP_GUESTS);
	}
	if ($read) $tpl->append('categos', array('id'=>$catego->id(), 'title'=>$catego->title()));
	if ($write) $tpl->append('wcategos', array('id'=>$catego->id(), 'title'=>$catego->title()));
}

// Seleccionamos las im?genes de la categor?as especificada
foreach ($_GET as $k => $v){
	$$k = $v;
}

if (!isset($cat) || $cat<=0) displayPage();

$catego = new EXMImagecategory(intval($cat));
if ($catego->isNew()) displayPage();

// Paginamos
$limit = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
$limit = $limit<=0 ? 10 : $limit;
$tpl->assign('limite', $limit);

$sql = "SELECT COUNT(*) FROM ".$db->prefix("images").($cat>0 ? " WHERE category='$cat'" : '');
list($num) = $db->fetchRow($db->query($sql));

$page = isset($_REQUEST['pag']) ? intval($_REQUEST['pag']) : 0;
if ($page > 0){ $page -= 1; }
$tpages = (int)($num / $limit);
if ($page>$tpages) $page = $tpages;
$start = $page * $limit;
if($num % $limit > 0) $tpages++;
$pactual = $page + 1;
if ($pactual>$tpages){
	$rest = $pactual - $tpages;
	$pactual = $pactual - $rest + 1;
	$start = ($pactual - 1) * $limit;
}

$tpl->assign('pactual', $pactual);
$tpl->assign('tpages', $tpages);
$showmax = $start + $limit;
$showmax = $showmax > $num ? $num : $showmax;

if ($pactual > 1){
	if ($pactual>2 && $tpages > 11){
		$tpl->append('pages', array('id'=>'primera', 'num'=>1));
	}
	$tpl->append('pages', array('id'=>'anterior', 'num'=>($pactual-1)));
}

$pstart = $pactual-4>0 ? $pactual-4 : 1;
$pend = ($pstart + 8)<=$tpages ? ($pstart + 8) : $tpages;

if ($pstart > 3 && $tpages>11){
	$tpl->append('pages', array('id'=>3,'salto'=>1,'num'=>3));
}

if ($tpages > 0){
	for ($i=$pstart;$i<=$pend;$i++){
		$tpl->append('pages', array('id'=>$i,'num'=>$i));
	}
}

if ($pend < $tpages-3 && $tpages>11){
	$tpl->append('pages', array('id'=>$tpages-3,'salto'=>2,'num'=>($tpages - 3)));
}

if ($pactual < $tpages && $tpages > 1){
	$tpl->append('pages', array('id'=>'siguiente', 'num'=>($pactual+1)));
	if ($pactual < $tpages-1 && $tpages > 11){
		$tpl->append('pages', array('id'=>'ultima', 'num'=>$tpages));
	}
}
	
$sql = "SELECT * FROM ".$db->prefix("images").($cat>0 ? " WHERE category='$cat'" : '')." ORDER BY fecha DESC LIMIT $start, $limit";
$result = $db->queryF($sql);

if ($db->getRowsNum($result)<=0) displayPage();

$caturl = $catego->url();
// Archivo despachador de im?genes
$imgfile = ABSURL.'/image.php';

while ($row = $db->fetchArray($result)){
	if ($catego->thumbnails()){
		$file = $row['ondb'] ? $imgfile.'?type=t&id='.$row['id_img'] : ($catego->hotlink() ? $imgfile.'?type=t&id='.$row['id_img'].'&p=1' : $caturl . '/ths/' . $row['archivo']);
		$bigfile = $row['ondb'] ? $imgfile.'?type=n&id='.$row['id_img'] : ($catego->hotlink() ? $imgfile.'?type=n&id='.$row['id_img'].'&p=1' : $caturl . '/' . $row['archivo']);
	} else {
		$file = $row['ondb'] ? $imgfile.'?type=n&id='.$row['id_img'] : ($catego->hotlink() ? $imgfile.'?type=n&id='.$row['id_img'].'&p=1' : $caturl . '/' . $row['archivo']);
		$bigfile = $file;
	}
	$tpl->append('images', array('id'=>$row['id_img'], 'title'=>$row['titulo'], 
			'file'=>$file, 'big'=>$bigfile, 'date'=>date($exmConfig['datestring'], $row['fecha']), 
			'desc'=>$row['desc']!='' ? 1 : 0, 'filename'=>$row['archivo'],));
}

$tpl->assign('catego', array('id'=>$catego->id(), 'title'=>$catego->title(), 'thumbnails'=>$catego->thumbnails(),
			'url'=>$catego->url(), 'thw'=>$catego->thumbWidth(), 'thh'=>$catego->thumbHeight(), 
			'imgw'=>$catego->imageWidth(), 'imgh'=>$catego->imageHeight(),'protect'=>$catego->hotlink()));
$tpl->assign('cat', $cat);

$cols = isset($_GET['cols']) ? intval($_GET['cols']) : 3;
$cols = $cols<=0 ? 3 : $cols;
$tpl->assign('cols', $cols);
	
// Mostramos las im?genes
displayPage();

?>