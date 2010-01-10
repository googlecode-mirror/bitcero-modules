<?php
// $Id: header.php 13 2009-08-31 00:45:24Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------
include XOOPS_ROOT_PATH.'/modules/rmcommon/loader.php';

include XOOPS_ROOT_PATH."/header.php";
include_once 'include/general.func.php';

$mc =& $xoopsModuleConfig;
$db =& Database::getInstance();
$myts =& MyTextSanitizer::getInstance();

define('MW_PATH',XOOPS_ROOT_PATH.'/modules/mywords');
define('MW_URL',MWFunctions::get_url());

$xoopsTpl->assign('mw_url', MW_URL);

$xmh = '';
if ($mc['css']){
    $xmh = '<link rel="stylesheet" type="text/css" media="screen" href="'.XOOPS_URL.'/modules/mywords/styles/main.css" />';
    $xmh .= '<link rel="stylesheet" type="text/css" media="screen" href="'.XOOPS_URL.'/modules/mywords/styles/editor.css" />';
}

$xoopsTpl->assign('lang_postedin', _MS_MW_CATEGOS);

// Redes Sociales
$sql = "SELECT * FROM ".$db->prefix("mw_bookmarks")." WHERE `active`='1'";
$result = $db->query($sql);

$socials = array();
$i = 0;
while ($row = $db->fetchArray($result)){
    $socials[$i] = new MWBookmark();
    $socials[$i]->assignVars($row);
    $i++;
}

?>