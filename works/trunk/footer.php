<?php
// $Id$
// --------------------------------------------------------
// Professional Works
// Manejo de Portafolio de Trabajos
// CopyRight © 2008. Red México
// Autor: BitC3R0
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

$xmh .= "<link href='".XOOPS_URL."/modules/works/styles/main.css' type='text/css' media='all' rel='stylesheet' />";
$xmh .=  '
	<script type="text/javascript">
			   
	     function showCategos(){
		var pos = exmAbsoluteElementPosition("pwShowCategos");
		document.getElementById("showCategos").style.top= pos.top + 15 +"px";	
		document.getElementById("showCategos").style.left= pos.left +"px";				
		if ($("showCategos").style.display=="block"){
			$("showCategos").style.display="none";
		}else{
			$("showCategos").style.display="block";
		}
	}
	</script>
	';
$tpl->assign('xoops_module_header', $xmh);
include '../../footer.php';

?>