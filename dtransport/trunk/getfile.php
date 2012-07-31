<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage files for download in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

if ($id<=0){
	header('location: '.DT_URL);
	die();
}

$file = new DTFile($id);
if ($file->isNew() && $mc['permalinks'])
	$dtfunc->error_404();
elseif($file->isNew())
    redirect_header(DT_URL, 1, __('File not found!','dtransport'));

$item = new DTSoftware($file->software());
if ($item->isNew() || !$item->getVar('approved')){
	if($mc['permalinks'])
        $dtfunc->error_404();
    else
        redirect_header(DT_URL, 1, __('Software does not exists!','dtransport'));
}

if (!$item->canDownload($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS))
	redirect_header($item->permalink(),1, __('Sorry, you don\'t have permission to download this file!','dtransport'));

// Comprobamos los límites
if ($item->getVar('limits')>0)
	if ($item->downloadsCount()>=$item->getVar('limits'))
		redirect_header($item->permalink(), 1, __('You have reached your download limit for this file!','dtransport'));

// Verificamos si la descarga se debe realizar
$token = isset($_SESSION['dttoken']) ? $_SESSION['dttoken'] : '';

if($token=='' || !$xoopsSecurity->validateToken($token)){

    $_SESSION['dttoken'] = $xoopsSecurity->createToken();
    $xoopsOption['template_main'] = 'dtrans_getfile.html';
    $xoopsOption['module_subpage'] = 'getfile';

    include 'header.php';

    $img = new RMImage();
    $img->load_from_params($item->getVar('image'));

    $xoopsTpl->assign('item', array(
        'title' => $item->getVar('name'),
        'image' => $img->get_smallest(),
        'link' => $item->permalink()
    ));

    $xoopsTpl->assign('lang_message', sprintf(__('Your %s download will start shortly...', 'dtransport'), '<a href="'.$item->permalink().'">'.$item->getVar('name').'</a>'));
    $xoopsTpl->assign('lang_problems', sprintf(__('Problems with the download? Please %s to download immediately.', 'dtransport'), '<a href="'.$file->permalink().'">'.__('use this link','dtransport').'</a>'));

    $tpl->add_style('main.css', 'dtransport');

    $tpl->add_local_script('main.js', 'dtransport');
    $tpl->add_head_script('var down_message = "'.sprintf(__('Your %s download will start in {x} seconds...', 'dtransport'), '<a href=\''.$item->permalink().'\'>'.$item->getVar('name').'</a>').'";');
    $tpl->add_head_script('var timeCounter = '.$mc['pause'].";\nvar dlink = '".$file->permalink()."';");

    $dtfunc->makeHeader();

    include 'footer.php';

    die();

}

unset($_SESSION['dttoken']);

// Comprobamos si el archivo es seguro o no
if (!$item->getVar('secure')){
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
		$dir = rtrim($dir, '/');

        $path = $mc['directory_insecure'];
		$path = str_replace("\\", "/", $path);
		$path = rtrim($path, '/');

		if (!file_exists($path.'/'.$file->file()))
			redirect_header(DT_URL.'/report.php?item='.$item->id()."&amp;error=0", 2, __('We\'re sorry but specified file does not exists!','dtransport'));
		
		$st = new DTStatistics();
		$st->setDate(time());
		$st->setFile($file->id());
		$st->setSoftware($item->id());
		$st->setUid($xoopsUser ? $xoopsUser->uid() : 0);
		$st->setIp($_SERVER['REMOTE_ADDR']);
		$st->save();

		$alert = new DTAlert($item->id());
        if(!$alert->isNew()){
		    $alert->setLastActivity(time());
		    $alert->save();
        }

		$item->addHit();
		$file->addHit();
		header('location: '.$dir.'/'.$file->file());
		die();
	}
	
}

// Enviamos una descarga segura
$path = $mc['directory_secure'];
$path = str_replace("\\", "/", $path);
$path = rtrim($path, '/');

if (!file_exists($path.'/'.$file->file()))
	redirect_header(DT_URL.'/report.php?item='.$item->id()."&amp;error=0", 2, __('We\'re sorry but selected file does not exists!','dtransport'));

$st = new DTStatistics();
$st->setDate(time());
$st->setFile($file->id());
$st->setSoftware($item->id());
$st->setUid($xoopsUser ? $xoopsUser->uid() : 0);
$st->setIp($_SERVER['REMOTE_ADDR']);
$st->save();

$alert = new DTAlert($item->id());
if(!$alert->isNew()){
    $alert->setLastActivity(time());
    $alert->save();
}

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
