$(document).ready(function(){
	
	var url=encodeURIComponent("http://redmexico.com.mx/modules/vcontrol/?id=3&type=json");

	$.post('<?php echo XOOPS_URL; ?>/modules/rmcommon/include/proxy.php', {url: url}, function(data){
        if(data.indexOf("<html")>0 && data.indexOf("</html>")>0) return;
		$("#pw-recent-news").append(data);
	}, 'html');
	
});