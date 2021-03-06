var lwid = '';
var fwid = '';
var swid = '';
var twid = 0;

$(document).ready(function(){

    // Show or hide widgets content
    if($.cookie('widgets')==null)
        saveCookieTitles();
        
    var vals = $.cookie('widgets').split("|");

    for(i=0;i<vals.length;i++){
        id = vals[i].split(':');
        if(i==vals.length - 1){
            if(id[1]=='false') $("#wid-title-"+id[0]).css('border-radius', '0 0 5px 5px');
        }else if(i==0){
            if(id[1]=='false') $("#wid-title-"+id[0]).css('border-radius', '5px');
        }
    }
    
    eles = $("div.rmc_widget_title");
    twid = eles.length;
    $(eles).each(function(i){
        
        if(i==eles.length - 1){
            lwid = $(eles[i]).attr("id").replace("wid-title-",'');
        }else if(i==0){
            fwid = $(eles[i]).attr("id").replace("wid-title-",'');
        }else if(i==1){
            swid = $(eles[i]).attr("id").replace("wid-title-",'');
        }
        
    })

    eles = $("div.rmc_widget_content:visible div.menu");
    $(eles).each(function(i){

        if ($("span.toggle", $(eles[i])).height()==null) return;

        if ($("span.toggle", $(eles[i])).height($(eles[i]).height()));

    });

    resizeCenterContent();

    $(".toggle").click(function(){
        id = $(this).attr("id").replace("switch-",'');
        $("#container-"+id).slideToggle('slow');
    });

    $("form table td.head").removeAttr('style');
    $("form table td.head").css('width','300px');
    $("table.outer").attr("cellspacing",'0');

    $(".msg-close").click(function(){
        $(this).parent(".errorMsg").slideUp();
        $(this).parent(".infoMsg").slideUp();
    });

    $("div.rmc_widget_title").click(function(){
        if($(this).attr("id")==undefined) return;
        id = $(this).attr("id").replace("wid-title-",'');

        var eles = $("div.rmc_widget_title");

        $("#wid-content-"+id).slideToggle('fast', function(){
            if($(this).is(":visible")){
                $("#wid-title-"+id).removeClass("title-collapsed");
    
                if(twid>2){
                    if(id==fwid){
                        $("#wid-title-"+id).css('border-radius', '5px 5px 0 0');
                    } else if(id==swid){
                        $("#wid-title-"+id).css('border-radius', '5px 5px 0 0');
                    } else{
                        $("#wid-title-"+id).css('border-radius', '0');
                    }
                
                } else {
                    
                    if(id==fwid){
                        $("#wid-title-"+id).css('border-radius', '5px 5px 0 0');
                    } else {
                        $("#wid-title-"+id).css('border-radius', '0');
                    }
                    
                }

            } else {

                $("#wid-title-"+id).addClass("title-collapsed");
                
                if(twid>2){
                    
                    if(id==lwid){
                        $("#wid-title-"+id).css('border-radius', '0 0 5px 5px');
                    }else if(id==fwid){
                        $("#wid-title-"+id).css('border-radius', '5px');
                    }else if(id==swid){
                        $("#wid-title-"+id).css('border-radius', '5px 5px 0 0');
                    } else{
                        $("#wid-title-"+id).css('border-radius', '0');
                    }
                    
                } else {
                    
                    if(id==fwid){
                        $("#wid-title-"+id).css('border-radius', '5px 5px 0 0');
                    } else {
                        $("#wid-title-"+id).css('border-radius', '0 0 5px 5px');
                    }
                    
                }

            }
            saveCookieTitles();
            resizeCenterContent();
        });

    });

    //setTimeout("hideMessages();", 10000);
});

function hideMessages(){
    $("div.errorMsg").slideUp('slow');
    $("div.infoMsg").slideUp('slow');
}

function saveCookieTitles(){

    var eles = $("div.rmc_widget_title");
    var ts = '';

    try{
    $(eles).each(function(i){

        id = $(eles[i]).attr('id').replace("wid-title-",'');
        ts += ts==''?'':'|';
        ts += id+":"+($("#wid-content-"+id).is(':visible'));

    });
    }catch(err){

    }

    $.cookie('widgets', ts, {expires: 365, path: '/'});


}

function resizeCenterContent(){
    if ($("#rmc-left-widgets").height() > $(window).height()-240){
        $("#rmc-center-content").css('min-height', ($("#rmc-left-widgets").height())+'px');
    } else {
        $("#rmc-center-content").css('min-height', ($(window).height()-240)+'px');
    }
}