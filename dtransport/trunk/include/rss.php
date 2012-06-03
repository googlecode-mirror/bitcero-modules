<?php
// $Id: rss.php 49 2009-02-11 12:01:08Z BitC3R0 $
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

function dtrans_rssdesc(){
	global $util;
	
	return _MI_DT_RSSDESC;
}

/**
* @desc Muestra el menu de opciones para sindicación
* @param int $limit Limite de resultados solicitados. 0 Indica ilimitado
* @param bool $more Referencia. Debe devolver true si existen mas resultados que el límite deseado
* @return array
*/
function &dtrans_rssfeed($limit, &$more){
	global $db;
	
	$ret[0]['name'] = _MI_DT_RSSRECENT;
	$ret[0]['desc'] = _MI_DT_RSSRECENTDESC;
	$ret[0]['params'] = "show=recent";
	
	$ret[1]['name'] = _MI_DT_RSSPOP;
	$ret[1]['desc'] = _MI_DT_RSSPOPDESC;
	$ret[1]['params'] = "show=pops";
	
	$ret[2]['name'] = _MI_DT_RSSRATE;
	$ret[2]['desc'] = _MI_DT_RSSRATEDESC;
	$ret[2]['params'] = "show=rate";
	
	return $ret;
	
}

/**
* @desc Genera la información para mostrar la Sindicación
* @param int Limite de resultados
* @return Array
*/
function &dtrans_rssshow($limit){
	global $util, $mc;
	
	$db =& XoopsDatabaseFactory::getDatabaseConnection();
	include_once XOOPS_ROOT_PATH.'/modules/dtransport/class/dtsoftware.class.php';
	
	foreach ($_GET as $k => $v){
		$$k = $v;
	}
	
	$feed = array();		// Información General
	$ret = array();
	$mc =& $util->moduleConfig('dtransport');
	
	$sql = "SELECT * FROM ".$db->prefix("dtrans_software")." WHERE approved='1'";
	
	switch($show){
		case 'pops':
			$feed['title'] = htmlspecialchars(_MI_DT_RSSPOP);
			$feed['link'] = XOOPS_URL.'/modules/dtransport';
			$feed['description'] = htmlspecialchars(_MI_DT_RSSPOPDESC);
			$sql .= " ORDER BY hits DESC";
			break;
		case 'rate':
			$feed['title'] = htmlspecialchars(_MI_DT_RSSRATE);
			$feed['link'] = XOOPS_URL.'/modules/dtransport';
			$feed['description'] = htmlspecialchars(_MI_DT_RSSRATEDESC);
			$sql .= " ORDER BY `rating`/`votes` DESC";
			break;
		default:
			$feed['title'] = htmlspecialchars(_MI_DT_RSSRECENT);
			$feed['link'] = XOOPS_URL.'/modules/dtransport';
			$feed['description'] = htmlspecialchars(_MI_DT_RSSRECENTDESC);
			$sql .= " ORDER BY created DESC, modified DESC";
			break;
	}
	
	$sql .= " LIMIT 0, 15";
	
	// Generamos los elementos
	$result = $db->query($sql);
	$items = array();
	$link = XOOPS_URL.'/modules/dtransport/';
	while ($row = $db->fetchArray($result)){
		$item = new DTSoftware();
		$item->assignVars($row);
		$rtn = array();
		$rtn['title'] = htmlspecialchars($item->name());
		$ilink = $link.($mc['urlmode'] ? "item/".$item->nameId()."/" : "item.php?id=".$item->id());
		$rtn['link'] = htmlspecialchars($ilink, ENT_QUOTES);
		$rtn['description'] = $item->shortdesc();
		$rtn['date'] = formatTimestamp($item->created());
		$items[] = $rtn;
	}

	
	$ret = array('feed'=>$feed, 'items'=>$items);
	return $ret;
	
}
?>
