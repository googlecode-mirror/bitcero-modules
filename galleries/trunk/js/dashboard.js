var redmex = {
    
    news : function(){
        var url=encodeURIComponent("http://www.redmexico.com.mx/modules/vcontrol/?id=7&type=json&limit=3");

        $.post(xurl+'/modules/rmcommon/include/proxy.php', {url: url}, function(data){
            if(data.indexOf("<html")>0 && data.indexOf("</html>")>0) return;
            
            $("#gs-news div").fadeOut('fast', function(){
                $("#gs-news").html(data);
            });
            
            redmex.about();
            
        }, 'html');
    },
    
    about : function(){
        
        var url=encodeURIComponent("http://redmexico.com.mx/modules/vcontrol/?id=7&action=info");
    
        $.post(xurl+'/modules/rmcommon/include/proxy.php', {url: url}, function(data){
            
            //if(data.indexOf("<html")>0 && data.indexOf("</html>")>0) return;
            if(data.description==undefined) return;
            
            $("#gs-desc div").fadeOut('fast', function(){
                $("#gs-desc").html(data.description);
                $("#gs-credits").html(data.credits);
            });
            
        }, 'json');
        
    }
    
}

$(document).ready(function(){
    
    var w = $("#last-pics").width();
    w = (w/85).toFixed(0);
    if((w * 85)>$("#last-pics").width()) w = w-1;
    
    var ws = $("#last-sets").width();
    ws = (ws/85).toFixed(0);
    if((ws * 85)>$("#last-sets").width()) ws = ws-1;

    
    $.get('index.php', {action:'pictures',limit:w}, function(data){
        
        $("#last-pics div").fadeOut('slow', function(){
            if(data.indexOf("<html")>0 && data.indexOf("</html>")>0) return;
            $("#last-pics div").html(data);
            $("#last-pics div").fadeIn('slow');
            
            // Get recent albums
            $.get('index.php', {action:'sets',limit:ws}, function(rtn){
                $("#last-sets div").fadeOut('slow', function(){
                    if(rtn.indexOf("<html")>0 && rtn.indexOf("</html>")>0) return;
                    $("#last-sets div").html(rtn);
                    $("#last-sets div").fadeIn('slow');
                    
                    redmex.news();
                    
                });
            },'html');
            
        });
        
        
    }, 'html');
    
});
