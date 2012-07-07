<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include '../../../mainfile.php';
XoopsLogger::getInstance()->activated = false;
XoopsLogger::getInstance()->renderingEnabled = false;

/**
 * Send error message to client
 */
function error($message){
    $data['error'] = 1;
    $data['message'] = __('Error:','dtransport').' '.$message;
    echo json_encode($data);
    die();
}


function dt_upload_screenshots(){
    global $xoopsSecurity;

    $item = rmc_server_var($_REQUEST, 'item', 0);
    $data = rmc_server_var($_REQUEST, 'data', '');

    $rmu = RMUtilities::get();
    $mc = $rmu->module_config('dtransport');

    $tc = TextCleaner::getInstance();

    $data = explode("|", $tc->decrypt($data));

    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $ses = new XoopsSessionHandler($db);
    session_decode($ses->read($data[1]));

    $_SERVER['HTTP_USER_AGENT'] = trim($data[0]);

    if(!$xoopsSecurity->check())
        error(__('Session token not valid!','dtransport'));

    if($item<=0)
        error(__('Download item ID not provided!','dtransport'));

    require_once XOOPS_ROOT_PATH.'/modules/dtransport/class/dtsoftware.class.php';

    $sw = new DTSoftware($item);
    if($sw->isNew())
        error(__('Specified download item does not exists!','dtransport'));

    if($sw->getVar('screens')>=$mc['limit_screen'])
        error(__('You have reached the limit screens number for this download item!','dtransport'));

    // Directorio de almacenamiento
    $dir = XOOPS_UPLOAD_PATH.'/screenshots';
    if (!is_dir($dir))
        mkdir($dir, 511);

    $dir .= '/'.date('Y', time());
    if (!is_dir($dir))
        mkdir($dir, 511);

    $dir .= '/'.date('m',time());
    if (!is_dir($dir))
        mkdir($dir, 511);

    if (!is_dir($dir.'/ths'))
        mkdir($dir.'/ths', 511);

    if(!is_dir($dir))
        error(__('Directory for store screenshots does not exists!','dtransport'));

    include RMCPATH.'/class/uploader.php';

    $uploader = new RMFileUploader($dir, $mc['image']*1024, array('jpg','gif','png'));

    $err = array();
    if (!$uploader->fetchMedia('Filedata'))
        error($uploader->getErrors());

    if (!$uploader->upload())
        error($uploader->getErrors());

    // Saving image
    require_once XOOPS_ROOT_PATH.'/modules/dtransport/class/dtscreenshot.class.php';
    $img = new DTScreenshot();
    $img->setDesc('');
    $img->setTitle($uploader->getSavedFileName());
    $img->setImage($uploader->getSavedFileName());
    $img->setDate(time());
    $img->setSoftware($item);

    if(!$img->save()){
        unlink($dir.'/'.$img->image());
        error(__('Screenshot could not be saved!','dtransport'));
    }

    // Resize image
    $thumb = explode(":",$mc['size_ths']);
    $big = explode(":",$mc['size_image']);
    $sizer = new RMImageResizer($dir.'/'.$img->getVar('image'), $dir.'/ths/'.$img->getVar('image'));

    // Thumbnail
    if(!isset($thumb[2]) || $thumb[2]=='crop'){
        $sizer->resizeAndCrop($thumb[0], $thumb[1]);
    } else {
        $sizer->resizeWidthOrHeight($thumb[0], $thumb[1]);
    }

    // Full size image
    $sizer->setTargetFile($dir.'/'.$img->image());
    if(!isset($big[2]) || $big[2]=='crop'){
        $sizer->resizeAndCrop($big[0], $big[1]);
    } else {
        $sizer->resizeWidthOrHeight($big[0], $big[1]);
    }

    $ret = array(
        'image'  => $uploader->getSavedFileName(),
        'dir'   => str_replace(XOOPS_UPLOAD_PATH, XOOPS_UPLOAD_URL, $dir),
        'token' => $data[4],
        'type'  => $uploader->getMediaType(),
        'error' => 0,
        'id'    => $img->id()
    );
    echo json_encode($ret);
    die();
}


function dt_get_information(){
    global $xoopsSecurity, $xoopsConfig, $xoopsModule, $xoopsUser;

    $rmf = RMFunctions::get();
    $xoopsModule = $rmf->load_module('dtransport');

    include_once '../../../include/cp_header.php';

    $func = new DTFunctions();

    if(!$xoopsSecurity->check())
        $func->dt_send_message(__('Session token not valid!','dtransport'), 1, 0);

    $id = rmc_server_var($_GET, 'id', 0);
    $sc = new DTScreenshot($id);
    if($sc->isNew())
        $func->dt_send_message(__('Specified screenshot does not exists!','dtransport'), 1, 1);

    $ret = array(
        'title' => $sc->title(),
        'description' => $sc->desc(),
        'id' => $sc->id()
    );

    $func->dt_send_message($ret, 0, 1);

}

function dt_save_screen_info(){
    global $xoopsSecurity, $xoopsConfig, $xoopsModule, $xoopsUser;

    $rmf = RMFunctions::get();
    $xoopsModule = $rmf->load_module('dtransport');

    include_once '../../../include/cp_header.php';

    $func = new DTFunctions();

    if(!$xoopsSecurity->check())
        $func->dt_send_message(__('Session token not valid!','dtransport'), 1, 0);

    $id = rmc_server_var($_POST, 'id', 0);
    $sc = new DTScreenshot($id);
    if($sc->isNew())
        $func->dt_send_message(__('Specified screenshot does not exists!','dtransport'), 1, 1);

    $title = rmc_server_var($_POST, 'title', '');
    $desc = rmc_server_var($_POST, 'desc', '');

    if($title=='')
        $func->dt_send_message(__('You must provide a title for this screenshot!','dtransport'), 1, 1);

    $sc->setTitle($title);
    $sc->setDesc($desc);

    if(!$sc->save())
        $func->dt_send_message(__('Screenshot changes could not be saved!','dtransport').'<br />'.$sc->errors(), 1, 1);

    $ret = array(
        'title' => $sc->title(),
        'description' => $sc->desc(),
        'id' => $sc->id()
    );

    $func->dt_send_message($ret, 0, 1);
}

function dt_delete_screen(){
    global $xoopsSecurity, $xoopsConfig, $xoopsModule, $xoopsUser;

    $rmf = RMFunctions::get();
    $xoopsModule = $rmf->load_module('dtransport');

    include_once '../../../include/cp_header.php';

    $func = new DTFunctions();

    if(!$xoopsSecurity->check())
        $func->dt_send_message(__('Session token not valid!','dtransport'), 1, 0);

    $id = rmc_server_var($_POST, 'id', 0);
    $sc = new DTScreenshot($id);
    if($sc->isNew())
        $func->dt_send_message(__('Specified screenshot does not exists!','dtransport'), 1, 1);

    if(!$sc->delete())
        $func->dt_send_message(__('Screenshot could not be deleted!','dtransport').'<br />'.$sc->errors(), 1, 1);

    $ret = array(
        'id' => $sc->id()
    );

    $func->dt_send_message($ret, 0, 1);
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    case 'upload':
        dt_upload_screenshots();
        break;
    case 'get-info':
        dt_get_information();
        break;
    case 'save-screen-data':
        dt_save_screen_info();
        break;
    case 'delete-screen':
        dt_delete_screen();
        break;
}