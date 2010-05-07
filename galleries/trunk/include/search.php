<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* @desc Realiza una búsqueda en el módulo desde EXM
*/
function gsSearch($queryarray, $andor, $limit, $offset, $userid){
    global $myts;
    
    include_once (XOOPS_ROOT_PATH."/modules/galleries/class/gsimage.class.php");
    include_once (XOOPS_ROOT_PATH."/modules/galleries/class/gsuser.class.php");

    $mc = RMUtilities::module_config('galleries');
    $db = Database::getInstance();
    
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
