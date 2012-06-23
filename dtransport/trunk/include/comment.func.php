<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Módulo para la administración de descargas
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
// @copyright: 2007 - 2008 Red México

/**
* @desc Función para incrementar el número de comentarios
*/
function dt_com_update($item, $total){
	include_once XOOPS_ROOT_PATH.'/modules/dtransport/class/dtsoftware.class.php';
	
	$db =& XoopsDatabaseFactory::getDatabaseConnection();
	$sql = "UPDATE ".$db->prefix("dtrans_software")." SET comments='$total' WHERE id_soft='$item'";
	$db->queryF($sql);
}

?>