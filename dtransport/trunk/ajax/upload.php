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

$action = rmc_server_var($_REQUEST, 'action', '');
$item = rmc_server_var($_REQUEST, 'item', 0);
$data = rmc_server_var($_REQUEST, 'data', '');

function error($message){
    $data['error'] = 1;
    $data['message'] = __('Error:','dtransport').' '.$message;
    echo json_encode($data);
    die();
}

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

if($sw->getVar('secure')){
    $dir = $mc['directory_secure'];
    if(!is_dir($dir)){
        if(!mkdir($dir, 511))
            error(__('Directory for protected downloads does not exists!','dtransport'));
    }
} else {
    $dir = $mc['directory_insecure'];
    if(!is_dir($dir)){
        if(!mkdir($dir, 511))
            error(__('Directory for downloads does not exists!','dtransport'));
    }
}

include RMCPATH.'/class/uploader.php';

$uploader = new RMFileUploader($dir, $mc['size_file']*1024*1024, $mc['type_file']);

$err = array();
if (!$uploader->fetchMedia('Filedata'))
    error($uploader->getErrors());

if (!$uploader->upload())
    error($uploader->getErrors());

$ret = array(
    'file'  => $uploader->getSavedFileName(),
    'dir'   => $uploader->getSavedDestination(),
    'token' => $data[4],
    'size'  => $rmu->formatBytesSize($uploader->getMediaSize()),
    'fullsize' => $uploader->getMediaSize(),
    'type'  => $uploader->getMediaType(),
    'secure'=> $sw->getVar('secure')?__('Protected Download','dtransport'):__('Normal Download','dtransport'),
    'error' => 0
);
echo json_encode($ret);
die();