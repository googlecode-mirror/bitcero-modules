$(document).ready(function(){
    var url=encodeURIComponent("http://www.redmexico.com.mx/modules/vcontrol/?id=8");

    $.post(xoops_url+'/modules/rmcommon/include/proxy.php', {url: url}, function(data){
        $("#bx-news").html(data);
        $(".news .rd_loading_image").hide('slow');
    }, 'html');
    
    var url = encodeURIComponent("http://www.redmexico.com.mx/modules/vcontrol/?id=8&action=info");
    $.post(xoops_url+'/modules/rmcommon/include/proxy.php', {url: url}, function(data){
        $("#bx-info .credits").html(data.credits);
        $("#bx-info .description").html(data.description);
        $(".info .rd_loading_image").hide('slow');
    }, 'json');
    
});