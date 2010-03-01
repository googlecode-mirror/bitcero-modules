<?php
// $Id$
// --------------------------------------------------------
// Professional Works
// Manejo de Portafolio de Trabajos
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
function pwSearch($queryarray, $andor, $limit, $offset, $userid){
    global $db, $myts;
    
    include_once (XOOPS_ROOT_PATH."/modules/works/class/pwwork.class.php");

    $util =& RMUtils::getInstance();
    $mc = $util->moduleConfig('works');
    
    $sql = "SELECT a.* FROM ".$db->prefix('pw_works')." a INNER JOIN ".$db->prefix('pw_clients')." b ON (a.public=1 AND a.client=b.id_client AND (";
    $sql1 = '';
    foreach($queryarray as $k){

	$sql1 .= ($sql1=='' ? "" : "$andor"). " (a.title LIKE '%$k%' OR a.short LIKE '%$k%' OR b.name LIKE '%$k%' OR b.business_name LIKE '%$k%') ";
    }
    $sql1 .="))";
    
    $sql1.= " GROUP BY a.id_work ORDER BY a.created DESC LIMIT $offset, $limit";
    $result = $db->queryF($sql.$sql1);
    
    $ret = array();
    while ($row = $db->fetchArray($result)){
	
	$work = new PWWork();
	$work->assignVars($row);

	$rtn = array();
	$rtn['image'] = 'images/works.png';
	$link = ($mc['urlmode'] ? "work/".$work->id() : "work.php?id=".$work->id());
	
	
        $rtn['title'] = $work->title();
        $rtn['time'] = $work->created();
	$rtn['uid'] = '';
        $rtn['desc'] = $work->descShort();
        $rtn['link'] = $link;
        $ret[] = $rtn;
    }
    
    return $ret;
}
?>
