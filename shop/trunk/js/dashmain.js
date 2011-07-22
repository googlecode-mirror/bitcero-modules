$(document).ready(function(){
    
    var url=encodeURIComponent("http://redmexico.com.mx/modules/vcontrol/?id=6&type=json");

    $.post(xurl+'/modules/rmcommon/include/proxy.php', {url: url}, function(data){
        if(data.indexOf("<html")>0 && data.indexOf("</html>")>0) return;
        
        $("#shop-dsh-news div").fadeOut('fast', function(){
            $("#shop-dsh-news").html(data);
        });
        
    }, 'html');
    
    var url=encodeURIComponent("http://redmexico.com.mx/modules/vcontrol/?id=6&action=info");
    
    $.post(xurl+'/modules/rmcommon/include/proxy.php', {url: url}, function(data){
        if(data.indexOf("<html")>0 && data.indexOf("</html>")>0) return;
        
        $("#mini-info div").fadeOut('fast', function(){
            $("#mini-info").html(data);
        });
        
    }, 'html');
    
});