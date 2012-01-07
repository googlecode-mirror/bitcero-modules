<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------


define('DT_LOCATION', 'index');
include 'header.php';

		
xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a>");

$url = "http://redmexico.com.mx/modules/vcontrol/?id=4";
$cHead = "<script type='text/javascript'>
			var url = '".XOOPS_URL."/include/proxy.php?url=' + encodeURIComponent('$url');
         	new Ajax.Updater('versionInfo',url);
		 </script>\n";
$cHead .= '<link href="../styles/admin.css" media="all" rel="stylesheet" type="text/css" />';
RMTemplate::get()->add_head($cHead);
xoops_cp_header();

include RMTemplate::get()->get_template('admin/dtrans_index.php', 'module', 'dtransport');

xoops_cp_footer();
