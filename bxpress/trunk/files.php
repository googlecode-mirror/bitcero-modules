<?php
// $Id: files.php 45 2007-12-15 03:17:26Z BitC3R0 $
// --------------------------------------------------------------
// Reporte de Errores
// CopyRight  2007 - 2008. Red México
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.xoopsmexico.net
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
// @copyright:  2007 - 2008. Red México
// @author: BitC3R0


/**
* @desc Archivo para procesar la entrega de archivos adjuntos
*/
define('BB_LOCATION','files');
include '../../mainfile.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$topic=isset($_GET['topic']) ? intval($_GET['topic']) : 0;

if ($id<=0){
	redirect_header('topic.php?id='.$topic, 2, _MS_EXMBB_NOTID);
	die();
}

$attach = new BBAttachment($id);
if ($attach->isNew()){
	redirect_header('topic.php?id='.$topic, 2, _MS_EXMBB_NOEXISTS);
	die();
}


if (!file_exists(XOOPS_UPLOAD_PATH.'/exmbb/'.$attach->file())){
	redirect_header('topics.php', 2, _MS_EXMBB_NOEXISTS);
	die();
}
$ext = substr($attach->file(), strrpos($attach->file(), '.'));
header('Content-type: '.$attach->mime());
header('Cache-control: no-store');
header('Expires: '.gmdate("D, d M Y H:i:s",time()+31536000).'GMT');
header('Content-disposition: filename='.urlencode($attach->name().$ext));
header('Content-Lenght: '.filesize(XOOPS_UPLOAD_PATH.'/exmbb/'.$attach->file()));
header('Last-Modified: '.gmdate("D, d M Y H:i:s",filemtime(XOOPS_UPLOAD_PATH.'/exmbb'.$attach->file())).'GMT');
readfile(XOOPS_UPLOAD_PATH.'/exmbb/'.$attach->file());


?>
