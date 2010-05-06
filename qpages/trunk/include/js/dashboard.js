$(document).ready(function(){
	var url=encodeURIComponent("http://redmexico.com.mx/modules/vcontrol/?id=9&type=json");

	$.post(xoops_url+'/modules/rmcommon/include/proxy.php', {url: url}, function(data){
		$("#qp-news-content .even").append(data);
		$("#qp-news-content .qp_loading_image").hide('slow');
	}, 'html');
	
});