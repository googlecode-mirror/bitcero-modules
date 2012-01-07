<?php
// $Id: getfile.php 39 2008-03-03 22:13:59Z ginis $
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

define('DT_LOCATION','download');
include '../../mainfile.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

define('DT_URL',XOOPS_URL.'/modules/dtransport');
define('DT_PATH',XOOPS_ROOT_PATH.'/modules/dtransport');

if ($id<=0){
	header('location: '.DT_URL);
	die();
}

$file = new DTFile($id);
if ($file->isNew()){
	redirect_header(DT_URL, 2, _MS_DT_NOFILE);
	die();
}

$item = new DTSoftware($file->software());
if ($item->isNew() || !$item->approved()){
	redirect_header(DT_URL,2,_MS_DT_NOITEM);
	die();
}

$mc =& $xoopsModuleConfig;
$link = DT_URL.($mc['urlmode'] ? '/item/'.$item->nameId().'/' : 'item.php?id='.$item->id());

if (!$item->canDownload($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS)){
	redirect_header(DT_URL,2,_MS_DT_NODOWN);
	die();
}

// Comprobamos los límites
if ($item->limits()>0){
	if ($item->downloadsCount()>=$item->limit()){
		redirect_header($link,2,_MS_DT_DOWNLIMIT);
		die();
	}
}

// Comprobamos si el archivo es seguro o no
if (!$item->secure()){
	// Comprobamos si es un archivo remoto o uno local	
	if ($file->remote()){
		// Almacenamos las estadísticas
		$st = new DTStatistics();
		$st->setDate(time());
		$st->setFile($file->id());
		$st->setSoftware($item->id());
		$st->setUid($xoopsUser ? $xoopsUser->uid() : 0);
		$st->setIp($_SERVER['REMOTE_ADDR']);
		$st->save();
		$item->addHit();
		$file->addHit();
		header('location: '.$file->file());
		die();
	} else {
		$dir = str_replace(XOOPS_ROOT_PATH, XOOPS_URL, $mc['directory_insecure']);
		$dir = str_replace("\\","/",$dir);
		if (substr($dir, strlen($dir)-1, 1)=='/'){
			$dir = substr($dir, 0, strlen($dir)-1);
		}
		$path = $mc['directory_insecure'];
		$path = str_replace("\\", "/", $path);
		if (substr($path, strlen($path)-1, 1)=='/'){
			$path = substr($path, 0, strlen($path)-1);
		}
		if (!file_exists($path.'/'.$file->file())){
			redirect_header(DT_URL.'/report.php?item='.$item->id()."&amp;error=0", 2, _MS_DT_NOEXISTSFILE);
			die();
		}
		
		$st = new DTStatistics();
		$st->setDate(time());
		$st->setFile($file->id());
		$st->setSoftware($item->id());
		$st->setUid($xoopsUser ? $xoopsUser->uid() : 0);
		$st->setIp($_SERVER['REMOTE_ADDR']);
		$st->save();
		$alert = new DTAlert();
		$alert->setLastActivity(time());
		$alert->save();
		$item->addHit();
		$file->addHit();
		header('location: '.$dir.'/'.$file->file());
		die();
	}
	
}

// Enviamos una descarga segura
$path = $mc['directory_secure'];
$path = str_replace("\\", "/", $path);
if (substr($path, strlen($path)-1, 1)=='/'){
	$path = substr($path, 0, strlen($path)-1);
}
if (!file_exists($path.'/'.$file->file())){
	redirect_header(DT_URL.'/report.php?item='.$item->id()."&amp;error=0", 2, _MS_DT_NOEXISTSFILE);
	die();
}

$st = new DTStatistics();
$st->setDate(time());
$st->setFile($file->id());
$st->setSoftware($item->id());
$st->setUid($xoopsUser ? $xoopsUser->uid() : 0);
$st->setIp($_SERVER['REMOTE_ADDR']);
$st->save();
$alert = new DTAlert();
$alert->setLastActivity(time());
$alert->save();
$item->addHit();
$file->addHit();
header('Content-type: '.$file->mime());
header('Cache-control: no-store');
header('Expires: '.gmdate("D, d M Y H:i:s",time()+31536000).'GMT');
header('Content-disposition: filename='.urlencode($file->file()));
header('Content-Lenght: '.filesize($path.'/'.$file->file()));
header('Last-Modified: '.gmdate("D, d M Y H:i:s",filemtime($path.'/'.$file->file())).'GMT');
readfile($path.'/'.$file->file());
die();
?>
