<?php
// $Id: proxy.php 216 2010-02-13 23:15:16Z i.bitcero $
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

if (!isset($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER']=='') die("Not Allowed");

$url = isset($_REQUEST['url']) ? $_REQUEST['url'] : '';
$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'text/html';

include_once '../class/proxy.php';
$proxy = new RMProxy($url, $type);
echo $proxy->get();
