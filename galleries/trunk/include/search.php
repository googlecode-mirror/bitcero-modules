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


/**
* @desc Realiza una búsqueda en el módulo desde EXM
*/
function gsSearch($queryarray, $andor, $limit, $offset, $userid){
    global $db, $myts;
    
    include_once (XOOPS_ROOT_PATH."/modules/galleries/class/gsimage.class.php");
    include_once (XOOPS_ROOT_PATH."/modules/galleries/class/gsuser.class.php");

    $util =& RMUtils::getInstance();
    $mc = $util->moduleConfig('galleries');
    
    $sql = "SELECT DISTINCT c.* FROM ".$db->prefix('gs_tags')." a INNER JOIN ".$db->prefix('gs_tagsimages')." b INNER JOIN ";
    $sql.= $db->prefix('gs_images')." c ON (";
    $sql.= "a.id_tag=b.id_tag AND b.id_image=c.id_image AND c.public=2 AND (";
    $sql1 = '';
    foreach($queryarray as $k){

	$sql1 .= ($sql1=='' ? "" : "$andor"). " (a.tag LIKE '%$k%' OR c.title LIKE '%$k%') ";
    }
    $sql1.="))";
    
    $sql1.= " ORDER BY c.created DESC LIMIT $offset, $limit";
    $result = $db->queryF($sql.$sql1);
    
    $ret = array();
    $users = array();
    while ($row = $db->fetchArray($result)){
	
	$img = new GSImage();
	$img->assignVars($row);

	if (!isset($users[$img->owner()])) $users[$img->owner()] = new GSUser($img->owner(), 1);

	$rtn = array();
	$rtn['image'] = 'images/images.png';
	$link = ($mc['urlmode'] ? "usr/".$users[$img->owner()]->uname()."/img/".$img->id() : "user.php?id="."usr/".$users[$img->owner()]->uname()."/img/".$img->id());
	
	
        $rtn['title'] = $img->title();
        $rtn['time'] = $img->created();
        $rtn['uid'] = $img->owner();
        $rtn['desc'] = $img->desc();
        $rtn['link'] = $link;
        $ret[] = $rtn;
    }
    
    return $ret;
}
?>
