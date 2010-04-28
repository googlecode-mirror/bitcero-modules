$(document).ready(function(){
	
	var url=encodeURIComponent("http://redmexico.com.mx/modules/vcontrol/?id=10&type=json");

	$.post('<?php echo XOOPS_URL; ?>/modules/rmcommon/include/proxy.php', {url: url}, function(data){
		$("#pw-recent-news").append(data);
	}, 'html');
	
});