<?php
// $Id$
// --------------------------------------------------------
// Gallery System
// Manejo y creación de galerías de imágenes
// CopyRight © 2008. Red México
// Autor: gina
// http://www.redmexico.com.mx
// http://www.exmsystem.org
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
// --------------------------------------------------------
// @copyright: 2008 Red México

define('GS_LOCATION','search');
include '../../mainfile.php';

if ($xoopsModuleConfig['search_format_mode']){
	$xoopsOption['template_main'] = 'gs_searchformat.html';
}else{
	$xoopsOption['template_main'] = 'gs_search.html';
}
$xoopsOption['module_subpage'] = 'search';
include 'header.php';

GSFunctions::makeHeader();

$myts =& MyTextSanitizer::getInstance();

$search = isset($_REQUEST['search']) ? $myts->addSlashes($_REQUEST['search']) : '';
$page = isset($_REQUEST['pag']) ? intval($_REQUEST['pag']) : '';


//Dividimos la búsqueda en palabras
$words = explode(" ",$search);


//Barra de Navegación
$sql = "SELECT COUNT(DISTINCT c.id_image) FROM ".$db->prefix('gs_tags')." a INNER JOIN ".$db->prefix('gs_tagsimages')." b INNER JOIN ";
$sql.= $db->prefix('gs_images')." c ON (";
$sql.= "a.id_tag=b.id_tag AND b.id_image=c.id_image AND c.public=2 AND (";
$sql1 = '';
foreach($words as $k){

	if(strlen($k)<=2) continue;	

	$sql1 .= $sql1=='' ? " (a.tag LIKE '%$k%' OR c.title LIKE '%$k%') " : " OR (tag LIKE '%$k%' OR c.title LIKE '%$k%')";
}
$sql1.="))";
	
/**
* @desc Formato para el manejo de las imágenes
*/
if ($mc['search_format_mode']){
	$format = $mc['search_format_values'];
	$crop = $format[0]; // 0 = Redimensionar, 1 = Cortar
	$width = $format[1];
	$height = $format[2];
	$limit = $format[3];
	$cols = $format[4];
	$showdesc = @$format[5];
} else {
	$limit = $mc['num_search'];
	$cols = $mc['cols_search'];
	$showdesc=0;
	$width = $mc['image_ths'][0];
}


list($num)=$db->fetchRow($db->query($sql.$sql1));
		
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
   
    $nav = new XoopsPageNav($num, $limit, $start, 'pag','search='.$search,0);
    $tpl->assign('searchNavPage', $nav->renderNav(4, 1));
}

$showmax = $start + $limit;
$showmax = $showmax > $num ? $num : $showmax;
$tpl->assign('lang_showing', sprintf(_MS_GS_SHOWING, $start + 1, $showmax, $num));
$tpl->assign('limit',$limit);
$tpl->assign('pag',$pactual);
//Fin de barra de navegación




$sql = "SELECT DISTINCT c.* FROM ".$db->prefix('gs_tags')." a INNER JOIN ".$db->prefix('gs_tagsimages')." b INNER JOIN ";
$sql.= $db->prefix('gs_images')." c ON (";
$sql.= "a.id_tag=b.id_tag AND b.id_image=c.id_image AND c.public=2 AND (";
$sql1 = '';
foreach($words as $k){

	if(strlen($k)<=2) continue;	

	$sql1 .= $sql1=='' ? " (a.tag LIKE '%$k%' OR c.title LIKE '%$k%') " : " OR (tag LIKE '%$k%' OR c.title LIKE '%$k%')";
}
$sql1.="))";
$sql2 = " GROUP BY c.id_image ORDER BY c.created DESC LIMIT $start,$limit";
$result = $db->query($sql.$sql1.$sql2);
$users = array();
while ($rows = $db->fetchArray($result)){

	$img = new GSImage();
	$img->assignVars($rows);

	if (!isset($users[$img->owner()])) $users[$img->owner()] = new GSUser($img->owner(), 1);	
	
	// Conversion de los formatos
	if (!$img->searchFormat() && $mc['search_format_mode']){
		GSFunctions::resizeImage($crop, $users[$img->owner()]->filesPath().'/'.$img->image(),$users[$img->owner()]->filesPath().'/formats/srh_'.$img->image(), $width, $height);
		$img->setSearchFormat(1, 1);
	}
	
	$urlimg = $users[$img->owner()]->filesURL().'/'.($mc['search_format_mode'] ? 'formats/srh_' : 'ths/').$img->image();
	$tags = $img->tags(false, 'tag');
	$tagurl = GS_URL.'/'.($mc['urlmode'] ? 'explore/tags/tag/' : 'explore.php?by=explore/tags/tag/');
	$strtag = '';
	foreach ($tags as $tag){
		$strtag .= $strtag=='' ? "<a href='$tagurl$tag/'>$tag</a>" : ", <a href='$tagurl$tag/'>$tag</a>";
	}
	
	$tpl->append('images',array('id'=>$img->id(),'title'=>$img->title(),'by'=>sprintf(_MS_GS_BY, $users[$img->owner()]->userURL(), $users[$img->owner()]->uname()),
	'viemore'=>sprintf(_MS_GS_VIEMORE, $users[$img->owner()]->userURL(), XOOPS_URL.'/user.php?uid='.$users[$img->owner()]->uid()),
	'avatar'=>$users[$img->owner()]->userVar('user_avatar'),'uid'=>$img->owner(),'desc'=>($showdesc ? $img->desc() : ''),
	'linkuser'=>$users[$img->owner()]->userURL(),'image'=>$urlimg,'created'=>sprintf(_MS_GS_CREATED, formatTimestamp($img->created(),'string')),
	'bigimage'=>$users[$img->owner()]->filesURL().'/'.$img->image(),'userurl'=>$users[$img->owner()]->userURL(),
	'link'=>$users[$img->owner()]->userURL()."img/".$img->id(),'comments'=>sprintf(_MS_GS_COMMENTS, $img->comments()),
	'tags'=>$strtag));

}


$tpl->assign('max_cols',$cols);
$tpl->assign('lang_quickview',_MS_GS_QUICK);
$tpl->assign('lang_found',sprintf(_MS_GS_FOUND, $num, $search));
$tpl->assign('width', round(100/$cols));
$tpl->assign('width_img', $width);

$util =& RMUtils::getInstance();
$xmh .= "\n<link href='".GS_URL."/include/css/lightbox.css' type='text/css' media='screen' rel='stylesheet' />\n
	<script type='text/javascript'>\nvar gs_url='".GS_URL."';\n</script>";
$util->addScript('prototype');
$util->addScript('scriptaeffects');
global $xoTheme;
$xoTheme->addScript(GS_URL."/include/js/lightbox.js");	


include 'footer.php';
?>
