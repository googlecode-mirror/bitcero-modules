$(document).ready(function(){
    var url=encodeURIComponent("http://www.redmexico.com.mx/modules/vcontrol/?id=5&type=json");

    $.post(xoops_url+'/modules/rmcommon/include/proxy.php', {url: url}, function(data){
        $("#rd-news").append(data);
        $(".rd_loading_image").hide('slow');
    }, 'html');
    
});