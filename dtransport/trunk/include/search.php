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

function dtransSearch($queryarray, $andor, $limit, $offset, $userid){
	global $db, $myts;
    
	include_once (XOOPS_ROOT_PATH."/modules/dtransport/class/dtsoftware.class.php");

    $util =& RMUtils::getInstance();
    $mc = $util->moduleConfig('dtransport');
    
    $tbl1 = $db->prefix("dtrans_software");
    
    $sql = "SELECT * FROM $tbl1 ";
    $sql1 = '';
    foreach ($queryarray as $k){
        $sql1 .= ($sql1=='' ? '' : " $andor ")." name LIKE '%$k%' OR 
        	 shortdesc LIKE '%$k%'";
    }
    $sql .= $sql1!='' ? "WHERE $sql1" : '';
    
    $sql .= ($sql1!='' ? " AND " : " WHERE ")."approved=1 ORDER BY modified DESC, created DESC LIMIT $offset, $limit";
    $result = $db->queryF($sql);
    
    $ret = array();
    $link = XOOPS_URL.'/modules/dtransport/';
    while ($row = $db->fetchArray($result)){
	
		$item = new DTSoftware();
		$item->assignVars($row);

		$rtn = array();
        $rtn['image'] = 'images/download16.png';
		$ilink = ($mc['urlmode'] ? "item/".$item->nameId().'/' : 'item.php?id='.$item->id());
	
        $rtn['title'] = $item->name();
        $rtn['time'] = $item->created();
        $rtn['uid'] = $item->uid();
        $rtn['desc'] = $item->shortdesc();
        $rtn['link'] = $ilink;
        $ret[] = $rtn;
    }
    
    return $ret;
}
?>
