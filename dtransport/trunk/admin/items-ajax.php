<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include 'header.php';

global $xoopsLogger;
error_reporting(0);
$xoopsLogger->renderingEnabled = false;
$xoopsLogger->activated = false;

/**
 * Send a message in json format
 * @param string Message to be sent
 * @param int Indicates if message is an error
 * @param int Indicates if token must be sent
 */
function dt_send_message($message, $e = 0, $t = 1){
    global $xoopsSecurity;

    if($e){
        $data = array(
            'message' => $message,
            'error' => 1,
            'token' => $t?$xoopsSecurity->createToken():''
        );
    } else {

        $data = array(
            'error' => 0,
            'token' => $t?$xoopsSecurity->createToken():'',
        );
        $data = array_merge($data, $message);
    }

    echo json_encode($data);
    die();

}

/**
 * Save new o existing download
 * @param int Indicates if edit or create a download
 */
function dt_save_download($edit = 0){
    global $xoopsSecurity;

    foreach($_POST as $k => $v){
        ${$k} = $v;
    }

    // Check xoops security
    if(!$xoopsSecurity->check())
        dt_send_message(__('Session token not valid!','dtransport'), 1,0);

    // Check data
    if($name=='')
        dt_send_message(__('Name must be specified!','dtransport'), 1, 1);

    if($shortdesc=='' || $desc=='')
        dt_send_message(__('A short description and full description must be specified for this download!', 'dtransport'), 1, 1);

    if(empty($catids))
        dt_send_message(__('Select a category to assign this download!', 'dtransport'), 1, 1);

    if(empty($lics))
        dt_send_message(__('A license must be selected at least!', 'dtransport'), 1, 1);

    if(empty($platforms))
        dt_send_message(__('A platform must be selected at least!', 'dtransport'), 1, 1);

    if(empty($groups))
        dt_send_message(__('A group must be selected at least!', 'dtransport'), 1, 1);

    if($edit){

        if($id<=0)
            dt_send_message(__('You must specified a download item to be edited!', 'dtransport'), 1, 1);

        $down = new DTSoftware($id);
        if($down->isNew())
            dt_send_message(__('Sepecified download item does not exists!', 'dtransport'), 1, 1);

    } else {

        $down = new DTSoftware();

    }

    $tc = TextCleaner::getInstance();

    $down->setVar('name', $name);
    $down->setVar('nameid', $tc->sweetstring($name));
    $down->setVar('version', $version);
    $down->setVar('shortdesc', $shortdesc);
    $down->setVar('desc', $desc);
    $down->setVar('image', $image);
    $down->setVar('limits', $limits);
    if(!$edit) $down->setVar('created', time());
    $down->setVar('modified', time());
    $down->setVar('uid', $user);
    $down->setVar('secure', $secure);
    $down->setVar('groups', $groups);
    $down->setVar('approved', $approved);
    $down->setVar('featured', $mark);
    $down->setVar('siterate', $siterate);
    $down->setVar('author_name', $author);
    $down->setVar('author_email', $email);
    $down->setVar('author_url', $url);
    $down->setVar('author_contact', $contact);
    $down->setVar('langs', $langs);

    // Categories
    $down->setCategories($catids);
    // Licences
    $down->setLicences($lics);
    // Platforms
    $down->setPlatforms($platforms);
    // Tags
    $down->setTags($tags);

    // Alert
    if($alert){
        $down->createAlert();
        $down->setLimit($limitalert);
        $down->setMode($mode);
    }

    global $xoopsDB;
    $db = $xoopsDB;
    // Check if exists another download with same name
    $sql = "SELECT COUNT(*) FROM ".$db->prefix("dtrans_software")." WHERE name='$name' AND nameid='".$down->getVar('nameid')."'";
    if($edit) $sql .= " AND id_soft<>".$down->id();

    list($num) = $db->fetchRow($db->query($sql));
    if($num>0)
        dt_send_message(__('Another item with same name already exists!','dtransport'), 1, 1);

    $ev = RMEvents::get();
    $ev->run_event('dtransport.saving.item', $down);

    // Save item
    if(!$down->save() && $down->isNew())
        dt_send_message(__('Item could not be saved!','dtransport').'<br />'.$down->errors(), 1, 1);
    elseif(!$down->save() && !$down->isNew())
        dt_send_message(__('Item saved but with some errors!','dtransport').'<br />'.$down->errors(), 1, 1);

    $ev->run_event('dtransport.item.saved', $down);

    $ret['id'] = $down->id();
    $ret['created'] = $down->getVar('created');
    $ret['modified'] = $down->getVar('modified');
    $ret['link'] = $down->permalink(1);
    $ret['message'] = $edit ? __('Changes saved successfully!','dtransport') : __('Item created successfully!','dtransport');
    dt_send_message($ret, 0, 1);

}

/**
 * Change the name used in permalinks
 */
function dt_change_nameid(){
    global $xoopsSecurity;

    if(!$xoopsSecurity->check())
        dt_send_message(__('Session token expired!','dtransport'), 1, 0);

    $id = rmc_server_var($_POST, 'id', '');
    if($id<=0)
        dt_send_message(__('No item ID has been provided!','dtransport'), 1, 1);

    $sw = new DTSoftware($id);
    if($sw->isNew())
        dt_send_message(__('Provided item ID is not valid!','dtransport'), 1, 1);

    $nameid = rmc_server_var($_POST, 'nameid', '');
    if($nameid=='')
        dt_send_message(__('Please provide new permalink name!','dtransport'), 1, 1);

    $tc = TextCleaner::getInstance();
    $nameid = $tc->sweetstring($nameid);

    global $xoopsDB;
    $db = $xoopsDB;

    $sql = "SELECT COUNT(*) FROM ".$db->prefix("dtrans_software")." WHERE nameid='$nameid' AND id_soft<>$id";
    list($num) = $db->fetchRow($db->query($sql));

    if($num>0)
        dt_send_message(__('Another item with same permalink name already exists! Please provide another one.','dtransport'), 1, 1);

    $sql = "UPDATE ".$db->prefix("dtrans_software")." SET nameid='$nameid' WHERE id_soft=$id";

    if(!$db->queryF($sql))
        dt_send_message(__('New name could not be saved!','dtransport').'<br />'.$db->error(), 1, 1);

    $sw->setVar('nameid', $nameid);
    $ret['link'] = $sw->permalink(1);
    $ret['nameid'] = $nameid;
    $ret['message'] = __('Changes saved successfully!','dtransport');
    dt_send_message($ret, 0, 1);

}


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    case 'save':
        dt_save_download();
        break;
    case 'saveedit':
        dt_save_download(1);
        break;
    case 'permaname':
        dt_change_nameid();
        break;
}