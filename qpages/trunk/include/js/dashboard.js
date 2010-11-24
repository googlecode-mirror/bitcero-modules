$(document).ready(function(){
	var url=encodeURIComponent("http://www.redmexico.com.mx/modules/vcontrol/?id=4&type=json");

	$.post(xoops_url+'/modules/rmcommon/include/proxy.php', {url: url}, function(data){
		$("#qp-news-content .even").append(data);
		$("#qp-news-content .qp_loading_image").hide('slow');
	}, 'html');
	
});