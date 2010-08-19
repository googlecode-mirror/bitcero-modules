<?php
// $Id$
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

require '../../mainfile.php';
define('INCLUDED_INDEX',1);

/**
* This file redirects all petions directly to his content
*/

if ($xoopsModuleConfig['permalinks']){
    
    // If friendly urls are activated
    $path = str_replace(XOOPS_URL, '', RMFunctions::current_url());
    $path = str_replace($xoopsModuleConfig['htpath'], '', $path);
    $path = trim($path, '/');
    
    $params = explode("/", $path);
    
    
} else {
    
    // If friendly urls are disabled
    $path = parse_url(RMFunctions::current_url());
    if(isset($path['query']))
        parse_str($path['query']);
    
    if(!isset($page) || $page==''){
        require 'mainpage.php';
        die();
    }
    
    $file = $page.'.php';
    if(!file_exists(XOOPS_ROOT_PATH.'/modules/docs/'.$file)){
        RDfunctions::error_404();
    }
    
    include $file;
    
    die();
    
}

// Mainpage
if(!isset($params[0]) || $params[0]==''){
    include 'mainpage.php';
    die();
}

// PDF Book
if($params[0]=='pdfbook'){
    $id = $params[1];
    $_GET['action'] = 'pdfbook';
    include 'content.php';
    die();
}

// Print Book
if($params[0]=='printbook'){
    $id = $params[1];
    $_GET['action'] = 'printbook';
    include 'content.php';
    die();
}

// Print Book
if($params[0]=='pdfsection'){
    $id = $params[1];
    $_GET['action'] = 'pdfsection';
    include 'content.php';
    die();
}

// Print Section
if($params[0]=='printsection'){
    $id = $params[1];
    $_GET['action'] = 'printsection';
    include 'content.php';
    die();
}

if($params[0]=='edit'){
    $id = $params[1];
    $res = $params[2];
    $action = 'edit';
    include 'edit.php';
    die();
}

// Section
if (count($params)==2){
    $res = new RDResource($params[0]);
    if(!$res->isNew()){
        $res = $res->id();
        $id = $params[1];
        include 'content.php';
        die();
    }
}

// Once all verifications has been passed then the resource
// param is present, then we will show it

$id = $params[0];
require 'resource.php';
