// $Id$
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$(document).ready(function(){
	
	var url=encodeURIComponent("http://redmexico.com.mx/modules/vcontrol/?id=5&type=json");

	$.post('<?php echo XOOPS_URL; ?>/modules/rmcommon/include/proxy.php', {url: url}, function(data){
		if(data.indexOf("<html")>0 && data.indexOf("</html>")>0) return;
        $("#mw-recent-news").append(data);
	}, 'html');
	
});