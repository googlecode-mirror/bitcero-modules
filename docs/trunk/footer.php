<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2

$xmh = "<link href='".XOOPS_URL."/modules/ahelp/styles/main.css' rel='stylesheet' type='text/css' media='screen' />\n";
$xmh .= "<link href='".XOOPS_URL."/modules/ahelp/styles/editor.css' rel='stylesheet' type='text/css' media='screen' />\n";
$xmh .= "<script type='text/javascript' src='".XOOPS_URL."/modules/ahelp/include/ahelp.js'></script>\n";

if ($mc['refs_method']){
	$xmh .= "<script type='text/javascript'>\n<!--\nvar xurl = '".XOOPS_URL."';\n
			var hideTipHtml = '<div id=\"ahelpRefsContent\"><strong>"._MS_AH_LOADING."</strong><br /><img src=\"".XOOPS_URL."/modules/ahelp/images/wait.gif\" border=\"0\" alt=\"\" /></div><br /><div align=\"center\"><a href=\"javascript:;\" onclick=\"hideTip();\" />"._MS_AH_CLOSE."</a></div>';\n-->\n</script>\n";
	$xmh .= "<script type='text/javascript'' src='".XOOPS_URL."/include/prototype.js'></script>\n";
	$xmh .= "<script type='text/javascript' src='".XOOPS_URL."/modules/ahelp/include/ahelptip.js'></script>\n";
} else {
	$xmh .= "<script type='text/javascript'>\n<!--\nvar xurl = '".XOOPS_URL."';\n-->\n</script>";
}

$tpl->assign('xoops_module_header', $xmh);
makeFooter();
include ('../../footer.php');
