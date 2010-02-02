<?php
// $Id$
// --------------------------------------------------------------
// XOOPS EXM
// Nueva Versión Mejorada de XOOPS
// CopyRight  2007 - 2008. Red México
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
// @copyright:  2007 - 2008 Red México
// @author: BitC3R0

/**
 * Presenta los diferentes iconos existentes en EXM
 */

$path = str_replace("\\", "/", __FILE__);
$path = str_replace("/rmcommon/tinymce/plugins/exmsystem/exmicons.php", "", $path);

function displayPage(){
	global $tpl, $plugPath;
	
	echo $tpl->fetch($plugPath . "exmicons.html");
	die();
}

require $path . '/mainfile.php';
global $exmLogger, $exmConfig;
$exmConfig['debug_mode'] = 0;
$exmLogger->renderingEnabled = false;
error_reporting(0);
$exmLogger->activated = false;
// include Smarty template engine and initialize it
require_once ABSPATH . '/class/theme.php';
require_once ABSPATH . '/class/theme_blocks.php';

$tpl = new EXMTpl();
$db =& $exmDB;
$plugPath = str_replace("exmicons.php","",__FILE__);

$result = $db->query("SELECT * FROM ".$db->prefix("icons")." ORDER BY id");
while ($row = $db->fetchArray($result)){
	$tpl->append('exmicons', array('id'=>$row['id'], 'file'=>ABSURL.'/uploads/icons/'.$row['icon_url'],'desc'=>$row['desc']));
}

displayPage();

?>