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
    
    var eles = $("#rmc-left-widgets div.rmc_widget_title");
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

        var eles = $("#rmc-left-widgets div.rmc_widget_title");

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
    
    $("a.rm_help_button").click(function(){
        if($("#rm-help-wd").length>0){
            $("#rm-help-wd").slideUp('fast', function(){
                $("#rm-help-wd").remove();
            });
        } else {
            var html = '<div id="rm-help-wd">';
            html += '<div class="rm_hwd_title"><span>'+$(this).attr('title')+'<span class="close"></span></span></div>';
            html += '<div class="rm_hwd_cont"><iframe src="'+$(this).attr("href")+'" name="rmdocs"></iframe></div></div>';
            $("body").append(html);
            $("#rm-help-wd iframe").height($("#rm-help-wd").height()-$("#rm-help-wd .rm_hwd_title").height()-7);
            $("#rm-help-wd iframe").width($("#rm-help-wd").width()-10);
            $("#rm-help-wd").slideDown('slow');
        }
        
        $("#rm-help-wd .rm_hwd_title .close").click(function(){
            $("#rm-help-wd").slideUp('fast', function(){
                $("#rm-help-wd").remove();
            });
        });
        
        $(window).resize(function(){
            $("#rm-help-wd iframe").height($("#rm-help-wd").height()-$("#rm-help-wd .rm_hwd_title").height()-7);
            $("#rm-help-wd iframe").width($("#rm-help-wd").width()-10);
        });
        
        return false;
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